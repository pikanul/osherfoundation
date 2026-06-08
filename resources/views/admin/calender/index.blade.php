@extends('admin.layouts.app')

@section('title', 'Time Management')
@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">

                <h6>Event Type</h6>
                <button class="btn btn-outline-primary text-left w-100 mb-1" onclick="filterCalendar('all')">All</button>
                @foreach ($eventtypes as $key => $item)
                <button class="btn btn-outline-primary text-left w-100 mb-1" style="border-left: 20px solid {{ $item->color }} !important;" onclick="filterCalendar('{{ $item->id }}')">{{ $item->name }}</button>

                @endforeach

                <button type="button" class="btn btn-outline-secondary w-100 " onclick="resetCalendarFilters()">
                    Reset Filters
                </button>

            </div>
        </div>
    </div>




    <div class="col-md-9">
        <div class="bg-white p-1 rounded mb-3">
             <div id="calendar"></div>
        </div>
    </div>
</div>



<!-- Event Modal -->
    <button id="event_button" class="d-none"  data-title="" data-href="" onclick="button_ajax(this)" data-dialog=" modal-dialog-scrollable modal-lg" data-success="cal_deselect()"> </button>


    <link href="{{ asset('vendor/calendars/fullcalendar.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/calendar/fullcalendar.min.js') }}"></script>

<script>


document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    let event_button = document.getElementById('event_button');
    let filterDebounceTimer = null;
    const filterDebounceDelay = 700;

    const formatDate = (date) => date.toISOString().slice(0, 10);
    const formatTime = (date) => date.toTimeString().slice(0, 8);
    const minusOneDay = (date) => {
        const d = new Date(date);
        d.setDate(d.getDate() - 1);
        return d;
    };

    const normalizeEventRangeForSave = (eventLike) => {
        const start = eventLike.start;
        const endRaw = eventLike.end || eventLike.start;
        const allDay = Boolean(eventLike.allDay);

        const end = allDay ? minusOneDay(endRaw) : endRaw;

        return {
            start_date: formatDate(start),
            end_date: formatDate(end),
            start_time: allDay ? '00:00:00' : formatTime(start),
            end_time: allDay ? '00:00:00' : formatTime(end),
        };
    };

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'timeGridWeek dayGridMonth timeGridDay listWeek'
        },
        initialView: 'dayGridMonth',
        slotMinTime: '08:00:00',
        slotMaxTime: '22:00:00',
        nowIndicator: true,
        allDaySlot: true,
        height: 'auto',
        selectable: true,
        editable: true,
        dragable: true,
        events: function(fetchInfo, successCallback, failureCallback) {
            let type = window.currentFilterType || 'all';
            const qs = new URLSearchParams({
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
                type: type
            });

            fetch(`{{ route("admin.calender.events") }}?${qs.toString()}`)
                .then(async (response) => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(errorText || 'Failed to load events');
                    }
                    return response.json();
                })
                .then(data => successCallback(data))
                .catch(error => {
                    console.error('Calendar events fetch failed:', error);
                    failureCallback(error);
                });
        },


        // SELECT: Add new event via modal loaded by AJAX
        select: function(info) {
            const start = info.startStr;
            let end = info.endStr;

            // FullCalendar all-day selection end is exclusive; convert to inclusive date
            if (info.allDay && info.end) {
                end = formatDate(minusOneDay(info.end));
            }

            let url = `{{ route('admin.calender.modal') }}?start=${start}&end=${end}`;
                event_button.setAttribute('data-href', url);
                event_button.setAttribute('data-title', 'Add Event');
                event_button.click();
        },


        // CLICK: View or edit event via modal loaded by AJAX
        eventClick: function(info) {
            let eventId = info.event.id;

            let url = `{{ route('admin.calender.modal') }}/${eventId}`;
                event_button.setAttribute('data-href', url);
                event_button.setAttribute('data-title', 'Event Details');
                event_button.click();
        },


        // Optional: drag & drop
        eventDrop: function(info){
            let event = info.event;
            const payload = normalizeEventRangeForSave(event);

            fetch(`{{ url('admin/calender/calender_update_date_store') }}/${event.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            }).then(res => res.json())
              .then(data => {
                  if (data.type !== 'success') info.revert();
                  calendar.refetchEvents();
              })
              .catch(() => info.revert());
        },

        eventResize: function(info){
            let event = info.event;
            const payload = normalizeEventRangeForSave(event);

            fetch(`{{ url('admin/calender/calender_update_date_store') }}/${event.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            }).then(res => res.json())
              .then(data => {
                  if (data.type !== 'success') info.revert();
                  calendar.refetchEvents();
              })
              .catch(() => info.revert());
        }
    });

    calendar.render();

    window.cal_deselect = function() {
        calendar.unselect();
        calendar.refetchEvents();

    }



    // Filter function
    window.filterCalendar = function(type = null) {
        if (type) window.currentFilterType = type;
        clearTimeout(filterDebounceTimer);
        filterDebounceTimer = setTimeout(function () {
            calendar.refetchEvents();
        }, filterDebounceDelay);
    };

    window.resetCalendarFilters = function() {
        window.currentFilterType = 'all';
        clearTimeout(filterDebounceTimer);
        filterDebounceTimer = setTimeout(function () {
            calendar.refetchEvents();
        }, filterDebounceDelay);
    };
});





</script>
@endsection
