<?php

namespace App\Http\Controllers;

use App\Enums\EventType;
use App\Http\Requests\CalendarEvent\StoreCalendarEventRequest;
use App\Http\Requests\CalendarEvent\UpdateCalendarEventRequest;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CalendarEventController extends Controller
{
    public function index(Request $request): View
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $year  = max(2000, min(2100, $year));
        $month = max(1, min(12, $month));

        $date  = Carbon::create($year, $month, 1);
        $start = $date->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end   = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $events = CalendarEvent::where('user_id', Auth::id())
            ->where(function ($q) use ($start, $end) {
                // Events that start within the grid
                $q->whereBetween('start_datetime', [$start, $end->copy()->endOfDay()])
                  // Or multi-day events that started before but end within/after
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_datetime', '<', $start)
                         ->where('end_datetime', '>=', $start);
                  });
            })
            ->orderBy('start_datetime')
            ->get()
            ->groupBy(fn ($e) => $e->start_datetime->format('Y-m-d'));

        return view('calendar.index', compact('date', 'start', 'end', 'events', 'year', 'month'));
    }

    public function create(): View
    {
        $courses    = Auth::user()->courses()->orderBy('name')->get();
        $eventTypes = EventType::cases();

        return view('calendar.create', compact('courses', 'eventTypes'));
    }

    public function store(StoreCalendarEventRequest $request): RedirectResponse
    {
        try {
            CalendarEvent::create(array_merge($request->validated(), ['user_id' => Auth::id()]));

            return redirect()->route('calendar.index')->with('success', 'Event created successfully.');
        } catch (\Throwable $e) {
            Log::error('CalendarEvent store failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit(CalendarEvent $calendar): View
    {
        abort_unless($calendar->user_id === Auth::id(), 403);

        $courses    = Auth::user()->courses()->orderBy('name')->get();
        $eventTypes = EventType::cases();

        return view('calendar.edit', compact('calendar', 'courses', 'eventTypes'));
    }

    public function update(UpdateCalendarEventRequest $request, CalendarEvent $calendar): RedirectResponse
    {
        abort_unless($calendar->user_id === Auth::id(), 403);

        try {
            $calendar->update($request->validated());

            return redirect()->route('calendar.index')->with('success', 'Event updated successfully.');
        } catch (\Throwable $e) {
            Log::error('CalendarEvent update failed', ['id' => $calendar->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function destroy(CalendarEvent $calendar): RedirectResponse
    {
        abort_unless($calendar->user_id === Auth::id(), 403);

        try {
            $calendar->delete();

            return redirect()->route('calendar.index')->with('success', 'Event deleted.');
        } catch (\Throwable $e) {
            Log::error('CalendarEvent destroy failed', ['id' => $calendar->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
