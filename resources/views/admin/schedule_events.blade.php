@extends('index')

@section('schedule_events')
<div class="for-schedule-events">
    <div class="for-page-title">
        <h3>Schedule Events</h3>
    </div>
    <div class="notes">
        <div class="form-schedule">
            <div class="note-page">
                <h2 style="padding: 10px;font-size: 30px;color: #F7F7F7;text-align: center;" id="ordinal-date">
                    @foreach ($filterEvent->slice(0,1) as $event)
                        @if ($event)
                            <span>{{date('l', strtotime($event->date)) . ' ' . date('F', strtotime($event->date)) . ' ' . date('jS', strtotime($event->date))}}</span>
                        @endif
                    @endforeach
                </h2>
                <div class="note-avail">
                    <div class="note-event">
                        @foreach ($filterEvent as $event)
                            <span><h3>{{$event->event}}</h3></span>
                            <br>
                            <span>{{date('l', strtotime($event->date)) . ' ' . date('F', strtotime($event->date)) . ' ' . date('jS', strtotime($event->date))}}</span>
                            <hr style="border: 1px solid #D0D0D0;">
                        @endforeach
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
                            <div class="calendar-month">
                                <table>
                                    <tr>
                                        @foreach ($months as $month)
                                        <th class="{{ $selectMonth && date('M', strtotime($selectMonth)) == $month ? 'months cur' : (date('M', strtotime($selectMonth)) == $month && date('M') == $month ? 'months cur' : 'months') }}"
                                            style="cursor: pointer; padding: 10px" onclick="getDateEvent('{{$month}}','{{date('Y-m-d', strtotime($month))}}')">
                                            {{$month}}</th>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>

                        </div>
                        @foreach ($formatWeek as $day)
                        <span class="day-label">
                            {{ $day }}
                        </span>
                        @endforeach
                        @if($events->isEmpty())
                        @while ($startOfCalendar <= $endOfCalendar ) <span class="day today">
                            <div class="day-to-day" style="position: relative">
                                {{ $startOfCalendar->addDays()->format('j') }}
                                <span style="position: absolute; top: 0%;left: 50%;">
                                    @if (Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('M') !=
                                    date('M', strtotime($selectMonth)))
                                    <div style="position: absolute; top: 0%;left: 50%;font-size: 10px;color:#344D67">
                                        {{ Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('M') }}
                                        @if (Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('Y') !=
                                        $endOfCalendar->format('Y'))
                                        {{Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('Y')}}
                                        @endif
                                    </div>
                                    @endif
                                </span>
                            </div>
                            </span>
                            @endwhile
                            @else
                                @while ($startOfCalendar <= $endOfCalendar )
                                <span style="position: relative;">                                    
                                    <div
                                        @foreach ($events as $event)
                                            style="{{ Carbon\Carbon::createFromDate($event->date)->addDays(-1)->format('m-d') == $startOfCalendar->format('m-d') ? 'cursor:pointer' : ''  }}"
                                            class="{{ Carbon\Carbon::createFromDate($event->date)->addDays(-1)->format('m-d') == $startOfCalendar->format('m-d') ? 'day-to-day today' : 'day-to-day'  }}"
                                            @if (Carbon\Carbon::createFromDate($event->date)->addDays(-1)->format('m-d') == $startOfCalendar->format('m-d'))
                                                onclick="getDateEvent('{{Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('M')}}','{{Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('Y-m-d')}}')"
                                            @endif
                                        @endforeach >
                                        @if (Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('M') !=
                                            date('M', strtotime($selectMonth)))
                                            <div style="position: absolute; top: 0%;left: 50%;font-size: 10px;color:#344D67;">
                                                {{ Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('M') }}
                                                
                                                @if (Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('Y') != $endOfCalendar->format('Y'))
                                                    {{Carbon\Carbon::createFromDate($startOfCalendar)->addDay()->format('Y')}}
                                                @endif
                                            </div>
                                        @endif
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
                            <small>{{ date('Y-m-d', strtotime($upcoming->date)) == date('Y-m-d') ? 'Today' :
                                $upcoming->date }}</small>
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
    // function getEvent(event) {
    //     $.ajax({
    //         type: "get",
    //         success: function (response) {
    //             location.href = "?&filter=" + event
    //         }
    //     });
    // }
    function getDateEvent(month, date) {
        $.ajax({
            type: "get",
            success: function (response) {
                location.href = `?filter=${date}&month_of=${month}`
            }
        });
    }
</script>