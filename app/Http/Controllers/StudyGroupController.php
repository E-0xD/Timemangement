<?php

namespace App\Http\Controllers;

use App\Enums\GroupRole;
use App\Http\Requests\StudyGroup\StoreStudyGroupRequest;
use App\Http\Requests\StudyGroup\StoreMessageRequest;
use App\Http\Requests\StudyGroup\UpdateStudyGroupRequest;
use App\Models\Message;
use App\Models\StudyGroup;
use App\Models\StudyGroupMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StudyGroupController extends Controller
{
    public function index(): View
    {
        try {
            $myGroups = StudyGroup::whereHas('memberRecords', fn ($q) => $q->where('user_id', Auth::id()))
                ->withCount('members')
                ->orderBy('name')
                ->get();

            $publicGroups = StudyGroup::where('is_public', true)
                ->whereDoesntHave('memberRecords', fn ($q) => $q->where('user_id', Auth::id()))
                ->withCount('members')
                ->orderBy('name')
                ->get();

            return view('study-groups.index', compact('myGroups', 'publicGroups'));
        } catch (\Exception $e) {
            Log::error('StudyGroupController@index failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function create(): View
    {
        return view('study-groups.create');
    }

    public function store(StoreStudyGroupRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, &$group) {
                $group = StudyGroup::create([
                    ...$request->validated(),
                    'owner_id' => Auth::id(),
                ]);

                StudyGroupMember::create([
                    'study_group_id' => $group->id,
                    'user_id'        => Auth::id(),
                    'role'           => GroupRole::Owner->value,
                    'joined_at'      => now(),
                ]);
            });

            return redirect()->route('groups.show', $group)->with('success', 'Study group created.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@store failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function show(StudyGroup $group): View
    {
        try {
            $isMember = $group->hasMember(Auth::id());

            abort_unless($isMember || $group->is_public, 403);

            $myRole   = $isMember ? $group->getMemberRole(Auth::id()) : null;
            $members  = $group->memberRecords()->with('user')->orderByRaw("CASE role WHEN 'owner' THEN 0 WHEN 'admin' THEN 1 ELSE 2 END")->get();
            $messages = $group->messages()->with('user')->latest()->paginate(30);

            return view('study-groups.show', compact('group', 'isMember', 'myRole', 'members', 'messages'));
        } catch (\Exception $e) {
            Log::error('StudyGroupController@show failed', [
                'user_id'  => Auth::id(),
                'group_id' => $group->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function edit(StudyGroup $group): View
    {
        $myRole = $group->getMemberRole(Auth::id());
        abort_unless($myRole?->canManageMembers(), 403);

        return view('study-groups.edit', compact('group'));
    }

    public function update(UpdateStudyGroupRequest $request, StudyGroup $group): RedirectResponse
    {
        $myRole = $group->getMemberRole(Auth::id());
        abort_unless($myRole?->canManageMembers(), 403);

        try {
            $group->update($request->validated());

            return redirect()->route('groups.show', $group)->with('success', 'Group updated.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@update failed', [
                'user_id'  => Auth::id(),
                'group_id' => $group->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function destroy(StudyGroup $group): RedirectResponse
    {
        $myRole = $group->getMemberRole(Auth::id());
        abort_unless($myRole?->canDeleteGroup(), 403);

        try {
            $group->delete();

            return redirect()->route('groups.index')->with('success', 'Group deleted.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@destroy failed', [
                'user_id'  => Auth::id(),
                'group_id' => $group->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function join(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'invite_code' => ['required', 'string', 'max:12'],
            ]);

            $group = StudyGroup::where('invite_code', strtoupper($validated['invite_code']))->firstOrFail();

            if ($group->hasMember(Auth::id())) {
                return redirect()->route('groups.show', $group)->with('info', 'You are already a member.');
            }

            StudyGroupMember::create([
                'study_group_id' => $group->id,
                'user_id'        => Auth::id(),
                'role'           => GroupRole::Member->value,
                'joined_at'      => now(),
            ]);

            return redirect()->route('groups.show', $group)->with('success', 'You joined the group.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['invite_code' => 'No group found with that invite code.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('StudyGroupController@join failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function leave(StudyGroup $group): RedirectResponse
    {
        try {
            $member = StudyGroupMember::where('study_group_id', $group->id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            abort_if($member->role === GroupRole::Owner->value, 403, 'Owner cannot leave. Transfer ownership or delete the group.');

            $member->delete();

            return redirect()->route('groups.index')->with('success', 'You left the group.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@leave failed', [
                'user_id'  => Auth::id(),
                'group_id' => $group->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function postMessage(StoreMessageRequest $request, StudyGroup $group): RedirectResponse
    {
        abort_unless($group->hasMember(Auth::id()), 403);

        try {
            Message::create([
                'study_group_id' => $group->id,
                'user_id'        => Auth::id(),
                'body'           => $request->validated('body'),
            ]);

            return back()->with('success', 'Message posted.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@postMessage failed', [
                'user_id'  => Auth::id(),
                'group_id' => $group->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteMessage(StudyGroup $group, Message $message): RedirectResponse
    {
        $myRole = $group->getMemberRole(Auth::id());
        $isOwner = $message->user_id === Auth::id();
        $canModerate = $myRole?->canManageMembers();

        abort_unless($isOwner || $canModerate, 403);

        try {
            $message->delete();

            return back()->with('success', 'Message deleted.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@deleteMessage failed', [
                'user_id'    => Auth::id(),
                'message_id' => $message->id,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function removeMember(StudyGroup $group, User $user): RedirectResponse
    {
        $myRole = $group->getMemberRole(Auth::id());
        abort_unless($myRole?->canManageMembers(), 403);
        abort_if($user->id === Auth::id(), 422);

        try {
            StudyGroupMember::where('study_group_id', $group->id)
                ->where('user_id', $user->id)
                ->delete();

            return back()->with('success', 'Member removed.');
        } catch (\Exception $e) {
            Log::error('StudyGroupController@removeMember failed', [
                'user_id'        => Auth::id(),
                'group_id'       => $group->id,
                'target_user_id' => $user->id,
                'error'          => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
