@extends('index')
@section('user_dashboard')
<div class="for-page-title">
    <h1>Dashboard</h1>
    <div style="padding: 5px"><small>Welcome {{ Auth::user()->info ? Auth::user()->info->name : 'User' }}</small></div>
    @if (!Auth::user()->info)
    <div style="color:#FF1E1E;display: flex; align-items:center">
        <div>
            <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <div>
            <p>Please complete your profile to access other features</p>
        </div>
    </div>
    @endif
</div>

<div class="for-dashboard-data" style="position: relative">
    <div class="home-control-body">
        <div class="dashboard-group">
            <a type="button" href="{{ route('user_schedule_reservation') }}">Reservation</a>
            <span>
                <div class="notif-count">{{$reservationCount}}</div>
            </span>
        </div>
        <div class="dashboard-group">
            <a type="button" href="{{ route('user_inventory_for_rents') }}">Rent</a>
            <span>
                <div class="notif-count">{{$rentCount}}</div>
            </span>
        </div>
        <div class="dashboard-group">
            <a type="button" href="{{ route('user_schedule_confirmation') }}">Confirmation Request</a>
            <span>
                <div class="notif-count">{{$rentConfirmCount}}</div>
            </span>
        </div>
        <div class="dashboard-group">
            <a type="button" href="{{ route('user_inventory_rents') }}">Rent Request</a>
            <span>
                <div class="notif-count">{{$rentRequestCount}}</div>
            </span>
        </div>
        <div class="dashboard-group">
            <a type="button" href="{{ route('user_inventory_extends') }}">Extend Request</a>
            <span>
                <div class="notif-count">{{$extendRequestCount}}</div>
            </span>
        </div>
    </div>
</div>
<div class="for-event-dates">
    <div class="event-dates">
        <h1>Event Dates</h1>
    </div>
    <div style="padding: 15px; display: flex; gap: 10px;">
        @foreach ($months as $month)
            <div class="month-selection"  onclick="getDateEvent('{{$month}}')" style="{{ !$selectedMonth && date('M') == date('M', strtotime($month)) ? "background-color:#FA8281;" : (date('M', strtotime($selectedMonth)) == $month ? "background-color:#FA8281;" : "") }}">
                <h3>{{ $month }}</h3>
            </div>
        @endforeach
        
    </div>
    <div class="events">
        <div class="dates">
            @if(!$reserves->isEmpty())
                @for($i = 0; $i <= $noOfDays; $i++) <div class="@foreach ($reserves as $reserve){{ date('j', strtotime($reserve->date)) - 1 == $i ? 'date active' : 'date' }} @endforeach">
                    <div class="month">
                        <div>{{ Carbon\Carbon::now()->days($i + 1)->format('D') }}</div>
                        <div>{{ Carbon\Carbon::now()->days($i + 1)->format('j')}}</div>
                    </div>
                </div>
            @endfor
        @endif
    </div>
    <div class="event-items">
        <div class="event-card-body">
            <div class="event-item">
                @if (!$reserves->isEmpty())
                @foreach ($reserves as $event)
                <div class="event-item" style="background-color: #FFFFFF;border: 1px solid #D0D0D0D;border-radius: 5px">
                    <h3>{{ date('D', strtotime($event->date)) }}</h3>
                    <div>
                        {{ $event->event }}
                    </div>
                    <div>
                        <small>{{ $event->date }}</small>
                    </div>
                    <hr>
                </div>
                <br>
                @endforeach
                @else
                <div class="event-item">
                    <b>No upcoming events</b>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
</div>
<script>
    function getDateEvent(eventMonth) {
        window.location.href = `?month=${eventMonth}`
    }
</script>
@endsection