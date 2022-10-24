@extends('index')
@section('user_dashboard')
<div class="for-page-title">
    <h1>Dashboard</h1>
    <div style="padding: 5px"><small>Welcome {{ Auth::user()->name }}</small></div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <div class="home-control-body">
        <div class="input-group a">
            <a type="button" href="{{ route('user_schedule_reservation') }}">Reservation</a>
        </div>
        <div class="input-group a">
            <a type="button" href="{{ route('user_inventory_for_rents') }}">Rent</a>
        </div>
    </div>
    <div class="home-control-body">
        <div class="input-group a">
            <a type="button" href="{{ route('user_schedule_events') }}">Confirmation Request</a>
        </div>
        <div class="input-group a">
            <a type="button" href="{{ route('user_inventory_rents') }}">Extend Request</a>
        </div>
    </div>
</div>
<div class="for-event-dates">
    <div class="event-dates">
        <h1>Event Dates</h1>
    </div>
    <div style="padding: 10px">
        <h3>{{ date('F') }}</h3>
    </div>
    <div class="events">
        <div class="dates">
            @if(!$reserves->isEmpty())
            @for($i = 0; $i <= $noOfDays; $i++) <div class="@foreach ($reserves as $reserve)
                        {{ date('j', strtotime($reserve->date)) - 1 == $i? 'date active' : 'date'}}
                    @endforeach">
                <div>{{ Carbon\Carbon::now()->days($i + 1)->format('D') }}</div>
                <div>{{ Carbon\Carbon::now()->days($i + 1)->format('j')}}</div>
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
@endsection