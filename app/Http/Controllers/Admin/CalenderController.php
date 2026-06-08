<?php

namespace App\Http\Controllers\Admin;

use App\Models\CalendarEvent;
use App\Models\EventType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class CalenderController extends Controller
{
    public function index()
    {
        $eventtypes = EventType::where('status', 1)->get();
        return view('admin.calender.index', ['eventtypes' => $eventtypes]);
    }

    public function events(Request $request)
    {
        $start = $this->normalizeCalendarDateParam(
            $request->input('start'),
            now()->startOfMonth()->toDateString()
        );
        $end = $this->normalizeCalendarDateParam(
            $request->input('end'),
            now()->endOfMonth()->toDateString()
        );

        $query = CalendarEvent::leftJoin('event_types', 'event_types.id', '=', 'calendar_events.type_id')
            ->whereDate('calendar_events.start_date', '<=', $end)
            ->whereDate('calendar_events.end_date', '>=', $start);

        if($request->filled('type') && $request->type !== 'all'){
           $query =  $query->where('calendar_events.type_id', $request->type);
        }

        if($request->filled('class_id') && $request->class_id !== 'all' && $request->class_id !== 0){
           $query = $query->where('calendar_events.class_id', $request->class_id);
        }
        if($request->filled('department') && $request->department !== 'all'){
           $query = $query->where('calendar_events.department_id', $request->department);
        }

        if($request->filled('section') && $request->section !== 'all'){
           $query = $query->where('calendar_events.section_id', $request->section);
        }

        if($request->filled('admin') && $request->admin !== 'all'){
           $query = $query->where('calendar_events.teacher_id', $request->admin);
        }

        $query = $query->select('calendar_events.*', 'event_types.name as type_name', 'event_types.color as color', 'event_types.type as type');

        $events = $query->get()->map(function($event){
            $startTime = $event->start_time ?: '00:00:00';
            $endTime = $event->end_time ?: '00:00:00';
            $isAllDay = $startTime === '00:00:00' && $endTime === '00:00:00';

            $title = $event->title;
            if ($event->type === 'Timeline' && !$isAllDay) {
                $title .= ' (' . $event->type . ') - (' . date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime)) . ')';
            }

            $start = $isAllDay
                ? $event->start_date
                : $event->start_date . 'T' . $startTime;

            // FullCalendar expects all-day "end" as exclusive date (next day).
            $end = $isAllDay
                ? Carbon::parse($event->end_date)->addDay()->toDateString()
                : $event->end_date . 'T' . $endTime;

            return [
                'id' => $event->id,
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                'allDay' => $isAllDay,
                'color' => $event->color ?: '#1d4ed8',
            ];
        });
        return response()->json($events);
    }

    private function normalizeCalendarDateParam($value, string $fallback): string
    {
        if (empty($value)) {
            return $fallback;
        }

        $raw = trim((string) $value);
        // Fix query-string decoded timezone offsets: "... 06:00" => "...+06:00"
        $raw = preg_replace('/\s(\d{2}:\d{2})$/', '+$1', $raw);

        try {
            return Carbon::parse($raw)->toDateString();
        } catch (\Throwable $e) {
            // Last-resort fallback for malformed values.
            return preg_match('/^\d{4}-\d{2}-\d{2}/', $raw)
                ? substr($raw, 0, 10)
                : $fallback;
        }
    }


    public function store(Request $request, $id = null){


        $validated = $request->validate([
                'title' => 'required',
                'type_id' => 'required',

                'start_date' => 'required',
                'start_time' => 'required',

                'end_date' => 'required',
                'end_time' => 'required',

                'teacher_id' => 'nullable',
                'class_id' => 'nullable',
                'section_id' => 'nullable',
                'department_id' => 'nullable',
                'day_start_time' => 'nullable',
                'day_end_time' => 'nullable',

        ]);


        if($id){
            $event = CalendarEvent::find($id);
            $event->update($validated);
            return response()->json(['type'=>'success', 'refresh'=>false,  'title'=>'Event Updated Successfully']);
        }else{
            $event = CalendarEvent::create($validated);
            return response()->json(['type'=>'success','refresh'=>false, 'title'=>'Event Created Successfully']);
        }
    }



    public function update_date(Request $request, $id = null){

        $event = CalendarEvent::find($id);
        if($event){
            $event->update([
                'start_date' => Carbon::createFromFormat('Y-m-d', $request->start_date),
                'end_date' => Carbon::createFromFormat('Y-m-d', $request->end_date),
                'start_time' => Carbon::createFromFormat('H:i:s', $request->start_time),
                'end_time' => Carbon::createFromFormat('H:i:s', $request->end_time)
            ]);

            return response()->json([
               'type'=>'success',
                'title' => 'Event Updated Successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'title' => 'Event Not Found'
            ]);
        }
    }




    public function modal (Request $request, $id = null){
        $event = CalendarEvent::find($id);

        $event_data = [
            'event' => $event,
            'start_date' => '',
            'end_date' => '',
            'start_time' => '',
            'end_time' => ''
        ];

        if($request->has('start') && $request->has('end')){
            $start = Carbon::parse( preg_replace('/\s(\d{2}:\d{2})$/', '+$1', $request->start));
            $end   = Carbon::parse( preg_replace('/\s(\d{2}:\d{2})$/', '+$1', $request->end));

            // Date
            $start_date = $start->toDateString(); // 2025-12-30
            $end_date   = $end->toDateString();   // 2025-12-31

            // Time
            $start_time = $start->format('H:i:s'); // 09:00:00
            $end_time   = $end->format('H:i:s');   // 10:30:00


            // different days


            $event_data = [
                'event' => $event,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time

            ];
        }


        $html = view('admin.calender.modal', $event_data)->render();
        return response()->json(['html'=>$html, 'success'=>true]);

    }


    public function delete($id){
        $event = CalendarEvent::find($id);
        if($event){
            $event->delete();
            return response()->json([
                'type'=>'success',
                'title' => 'Event Deleted Successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'title' => 'Event Not Found'
            ]);
        }
    }

    public function publicEventTypes(Request $request)
    {
        $items = EventType::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'color', 'type'])
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'color' => $type->color,
                    'type' => $type->type,
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    public function publicEvents(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:50',
            'before_id' => 'nullable|integer|min:1',
            'before_start_date' => 'nullable|date',
            'type_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $perPage = (int) ($request->input('per_page', 9));
        $beforeId = $request->input('before_id');
        $beforeStartDate = $request->input('before_start_date');
        $typeId = $request->input('type_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = CalendarEvent::query()
            ->leftJoin('event_types', 'event_types.id', '=', 'calendar_events.type_id')
            ->where('calendar_events.status', 1)
            ->where('calendar_events.visibility', 1);

        if (!is_null($typeId) && $typeId !== '' && $typeId !== 'all') {
            $query->where('calendar_events.type_id', $typeId);
        }

        if ($startDate && $endDate) {
            $query->whereDate('calendar_events.start_date', '<=', $endDate)
                ->whereDate('calendar_events.end_date', '>=', $startDate);
        } elseif ($startDate) {
            $query->whereDate('calendar_events.end_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('calendar_events.start_date', '<=', $endDate);
        }

        if ($beforeStartDate && $beforeId) {
            $query->where(function ($cursor) use ($beforeStartDate, $beforeId) {
                $cursor->whereDate('calendar_events.start_date', '<', $beforeStartDate)
                    ->orWhere(function ($sameDay) use ($beforeStartDate, $beforeId) {
                        $sameDay->whereDate('calendar_events.start_date', '=', $beforeStartDate)
                            ->where('calendar_events.id', '<', $beforeId);
                    });
            });
        }

        $rows = $query
            ->orderBy('calendar_events.start_date', 'desc')
            ->orderBy('calendar_events.id', 'desc')
            ->limit($perPage + 1)
            ->get([
                'calendar_events.id',
                'calendar_events.title',
                'calendar_events.description',
                'calendar_events.start_time',
                'calendar_events.end_time',
                'calendar_events.start_date',
                'calendar_events.end_date',
                'calendar_events.type_id',
                'event_types.name as type_name',
                'event_types.color as type_color',
            ]);

        $hasMore = $rows->count() > $perPage;
        $items = $rows->take($perPage)->values();
        $lastItem = $items->last();

        return response()->json([
            'items' => $items,
            'has_more' => $hasMore,
            'next_before_id' => $hasMore && $lastItem ? $lastItem->id : null,
            'next_before_start_date' => $hasMore && $lastItem ? $lastItem->start_date : null,
        ]);
    }

}
