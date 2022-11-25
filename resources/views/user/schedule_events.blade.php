@extends('index')
@section('user_schedule_events')
<div class="for-schedule-events">
    <div class="for-page-title">
        <h3>Schedule Events</h3>
        @if (!Auth::user()->info || !Auth::user()->validate)
            <div style="color:#FF1E1E;display: flex; align-items:center">
                <div>
                    <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div><p>Please complete your profile to access other features</p></div>
            </div>
        @endif
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
                                {{ $day  }}
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
                            <p><b>{{ $prev->client }}</b></p>
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
                            <p><b>{{ $upcoming->client }}</b></p>
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
    // function getEvent(e,event) {
    //     e.preventDefault()
    //     $.ajax({
    //         type: "get",
    //         url: "/user/get-event/" + event,
    //         data: `_token = {{ csrf_token() }}`,
    //         success: function (response) {
    //             $('.note-event span').html(`<h3>${response.event.event}</h3>` + "<br>" + response.event.date)
    //             $('#ordinal-date span').html(response.event.date)
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