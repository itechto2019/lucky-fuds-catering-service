@extends('index')
@section('schedule_events')
<div class="for-schedule-events">
    <div class="for-page-title">
        <h3>Schedule Events</h3>
    </div>

    <div class="notes">
        <div class="form-schedule">
            <div class="note-page">
                <h2 style="padding: 10px;font-size: 30px;color: #F7F7F7;text-align: center;" id="ordinal-date"><span>Date Event</span></h2>
                <div class="note-avail">
                    <div class="note-event">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="note-schedule">
                <div class="date-list">
                    <div class="dayList">
                        <div>
                            <h2 style="float: right">{{ date('Y') }}</h2> 
                        </div>
                        <div class="month-list">
                            @foreach ($months as $month)
                                <div class="{{ $selectMonth && date('M', strtotime($selectMonth)) == $month ? 'months cur' : (!$selectMonth && date('M') == $month ? 'months cur' : 'months') }}" style="cursor: pointer; padding: 10px" onclick="getDateEvent('{{$month}}')">{{ $month }}</div>
                            @endforeach
                        </div>
                        @foreach ($formatWeek as $day)
                            <span class="day-label">
                                {{ $day  }}
                            </span>
                        @endforeach
                        @if($events->isEmpty())
                            @while ($startOfCalendar <= $endOfCalendar )
                                <span class="day today">
                                    <div class="day-to-day">
                                        {{ $startOfCalendar->addDays()->format('j') }}
                                    </div>
                                </span>
                            @endwhile
                        @else
                            @while ($startOfCalendar <= $endOfCalendar )
                                <span>
                                    <div style="
                                        @foreach ($events as $event)
                                            {{ Carbon\Carbon::createFromDate($event->date)->addDay(-1)->format('m-j') == $startOfCalendar->format('m-j') ? 'cursor:pointer' : ''  }}
                                        @endforeach"class="
                                        @foreach ($events as $event)
                                            {{ Carbon\Carbon::createFromDate($event->date)->addDay(-1)->format('m-j') == $startOfCalendar->format('m-j') ? 'day-to-day today' : 'day-to-day'  }}
                                        @endforeach"onclick="
                                        @foreach ($events as $event)
                                            @if(Carbon\Carbon::createFromDate($event->date)->addDay(-1)->format('m-j') == $startOfCalendar->format('m-j'))
                                                getEvent(event,{{$event->id}})
                                            @endif
                                        @endforeach">
                                        {{ $startOfCalendar->addDays()->format('j') }}
                                    </div>
                                </span>
                            @endwhile
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="events-container">
        <div class="notif-container">
            <div class="event-page-title">
                <div style="padding: 10px">
                    <h3>Previous Events</h3>
                </div>
                <div class="event-list-coming">
                    @foreach ($previousEvents as $prev)
                        <div class="event-coming">
                            <p>Client: <b>{{ $prev->info->name }}</b></p>
                            <div>
                                <small>{{ $prev->event }}</small>
                            </div>
                            <div>
                                <small>{{ $prev->date }}</small>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="notif-container">
            <div class="event-page-title">
                <div style="padding: 10px">
                    <h3>Upcoming Events</h3>
                </div>
                <div class="event-list-coming">
                    @foreach ($upcomingEvents as $upcoming)
                        <div class="event-coming">
                            <p>Client: <b>{{ $upcoming->info->name }}</b></p>
                            <div>
                                <small>{{ $upcoming->event }}</small>
                            </div>
                            <div>
                                <small>{{ date('Y-m-d', strtotime($upcoming->date)) == date('Y-m-d') ? 'Today' : $upcoming->date }}</small>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
    function getEvent(e,event) {
        e.preventDefault()
        $.ajax({
            type: "get",
            url: "/admin/get-event/" + event,
            data: `_token = {{ csrf_token() }}`,
            success: function (response) {
                $('.note-event span').html(response.event.event + "<br>" + response.today)
                $('#ordinal-date span').html(response.event.date)
            }
        });
    }
    function getDateEvent(month) {
        $.ajax({
            type: "get",
            url: "{{route('schedule_events')}}",
            success: function (response) {
                location.href = "?month_of=" + month
            }
        });
    }
</script>
