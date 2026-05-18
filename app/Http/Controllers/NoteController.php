<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        try {
            $search   = $request->query('search');
            $courseId = $request->query('course_id');

            $query = Note::where('user_id', Auth::id())
                ->with('course')
                ->orderByDesc('updated_at');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . strip_tags($search) . '%')
                      ->orWhere('content', 'like', '%' . strip_tags($search) . '%');
                });
            }

            if ($courseId && is_numeric($courseId)) {
                $query->where('course_id', (int) $courseId);
            }

            $notes   = $query->paginate(20)->withQueryString();
            $courses = Auth::user()->courses()->orderBy('name')->get();

            return view('notes.index', compact('notes', 'courses', 'search', 'courseId'));
        } catch (\Exception $e) {
            Log::error('NoteController@index failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function create(): View
    {
        $courses = Auth::user()->courses()->orderBy('name')->get();

        return view('notes.create', compact('courses'));
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        try {
            $note = Note::create([
                ...$request->validated(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('notes.show', $note)->with('success', 'Note created.');
        } catch (\Exception $e) {
            Log::error('NoteController@store failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function show(Note $note): View
    {
        abort_unless($note->user_id === Auth::id(), 403);

        return view('notes.show', compact('note'));
    }

    public function edit(Note $note): View
    {
        abort_unless($note->user_id === Auth::id(), 403);

        $courses = Auth::user()->courses()->orderBy('name')->get();

        return view('notes.edit', compact('note', 'courses'));
    }

    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        abort_unless($note->user_id === Auth::id(), 403);

        try {
            $note->update($request->validated());

            return redirect()->route('notes.show', $note)->with('success', 'Note saved.');
        } catch (\Exception $e) {
            Log::error('NoteController@update failed', [
                'user_id' => Auth::id(),
                'note_id' => $note->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function destroy(Note $note): RedirectResponse
    {
        abort_unless($note->user_id === Auth::id(), 403);

        try {
            $note->delete();

            return redirect()->route('notes.index')->with('success', 'Note deleted.');
        } catch (\Exception $e) {
            Log::error('NoteController@destroy failed', [
                'user_id' => Auth::id(),
                'note_id' => $note->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
