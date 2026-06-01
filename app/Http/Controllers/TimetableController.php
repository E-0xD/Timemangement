<?php

namespace App\Http\Controllers;

use App\Enums\DayOfWeek;
use App\Http\Requests\Timetable\StoreTimetableRequest;
use App\Http\Requests\Timetable\UpdateTimetableRequest;
use App\Models\Timetable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(): View
    {
        $entries = Timetable::where('user_id', Auth::id())
            ->with('course')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($e) => $e->day_of_week->value);

        $days       = DayOfWeek::cases();
        $hasWeekend = $entries->hasAny(['saturday', 'sunday']);
        $totalCount = Timetable::where('user_id', Auth::id())->count();

        return view('timetable.index', compact('entries', 'days', 'hasWeekend', 'totalCount'));
    }

    public function create(): View
    {
        $courses = Auth::user()->courses()->orderBy('name')->get();

        return view('timetable.create', compact('courses'));
    }

    public function store(StoreTimetableRequest $request): RedirectResponse
    {
        try {
            $data            = $request->validated();
            $data['user_id'] = Auth::id();

            $conflict = Timetable::where('user_id', Auth::id())
                ->where('day_of_week', $data['day_of_week'])
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time'])
                ->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'start_time' => 'This time slot conflicts with an existing timetable entry on that day.',
                ]);
            }

            Timetable::create($data);

            return redirect()->route('timetable.index')
                ->with('success', 'Timetable entry added successfully.');
        } catch (\Throwable $e) {
            Log::error('TimetableController@store error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit(Timetable $timetable): View
    {
        abort_unless((int) $timetable->user_id === (int) Auth::id(), 403);

        $courses = Auth::user()->courses()->orderBy('name')->get();

        return view('timetable.edit', compact('timetable', 'courses'));
    }

    public function update(UpdateTimetableRequest $request, Timetable $timetable): RedirectResponse
    {
        abort_unless((int) $timetable->user_id === (int) Auth::id(), 403);

        try {
            $data = $request->validated();

            $conflict = Timetable::where('user_id', Auth::id())
                ->where('id', '!=', $timetable->id)
                ->where('day_of_week', $data['day_of_week'])
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time'])
                ->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'start_time' => 'This time slot conflicts with an existing timetable entry on that day.',
                ]);
            }

            $timetable->update($data);

            return redirect()->route('timetable.index')
                ->with('success', 'Timetable entry updated successfully.');
        } catch (\Throwable $e) {
            Log::error('TimetableController@update error', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        abort_unless((int) $timetable->user_id === (int) Auth::id(), 403);

        $timetable->delete();

        return redirect()->route('timetable.index')
            ->with('success', 'Timetable entry deleted.');
    }
}
