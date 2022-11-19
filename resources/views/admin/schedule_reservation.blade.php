@extends('index')
@section('schedule_reservation')
<div class="for-schedule-reservation">
    <div class="for-page-title">
        <h1>Reservation</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if($reservations->isNotEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->client }}</td>
                    <td>
                        <b>Package: </b>{{ $reservation->package->name }}
                        <br>
                        <b>Client: </b>{{ $reservation->client }}
                        <br>
                        <b>Contact: </b>{{ $reservation->contact }}
                        <br>
                        <b>Email: </b>{{ $reservation->email }}
                        <br>
                        <b>Prefered contact: </b>{{ $reservation->method }}
                        <br>
                        <b>Address: </b>{{ $reservation->address }}
                        <br>
                        <b>Event: </b>{{ $reservation->event }}
                        <br>
                        <b>No. of guest/s: </b>{{ $reservation->guest }}
                        <br>
                    </td>
                    <td>{{ $reservation->status }}</td>
                    <td>
                        @if($reservation->status == "pending")
                        <div class="action-form">
                            <div class="action-button">
                                <form action="{{ route('to_approve', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button>Approve</button>
                                </form>
                            </div>
                            <div class="action-button">
                                <form action="{{ route('to_reject_reserve', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <button>Decline</button>
                                </form>
                            </div>
                        </div>
                        @elseif($reservation->status == "approved")
                        <p>You've approved a reservation</p>
                        @elseif($reservation->status == "declined")
                        <p>You've declined a reservation</p>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <div style="padding: 10px">
                <h3><i>No such reservations are available!</i></h3>
            </div>
            @endif
        </div>
    </div>
    <hr>
    <div class="reservation-container">
        <div class="notif-container">
            <div class="event-page-title">
                <h3>Approved</h3>
                <br>
                <hr>
                <div class="event-list">
                    @if ($approves->isEmpty())
                        @foreach ($approves as $approve)
                            <div class="event-approved">
                                <p><b>Client: </b>{{$approve->client}}</p>
                                <p><b>Contact: </b> {{ $approve->contact }}</p>
                                <p><b>Email: </b> {{ $approve->email }}</p>
                                <p><b>Prefered: </b> {{ $approve->method }}</p>
                                <p><b>Event: </b> {{ $approve->event }}</p>
                                <p><b>Date: </b><small>({{ $approve->date }})</small></p>
                            </div>
                            <hr>
                        @endforeach
                    @else
                    <div class="event-approved">
                        <b>No approved reservation</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="notif-container">
            <div class="event-page-title">
                <h3>Declined</h3>
                <br>
                <hr>
                <div class="event-list">
                    @if (!$declines->isEmpty())
                    @foreach ($declines as $decline)
                    <div class="event-approved">
                        <small>({{ $decline->date }})</small>
                        <p><b>Client: </b>{{$decline->client}}</p>
                        <p><b>Contact: </b> {{ $decline->contact }}</p>
                        <p><b>Email: </b> {{ $decline->email }}</p>
                        <p><b>Prefered: </b> {{ $decline->method }}</p>
                        <p style="color: rgb(250, 83, 83)"><b>Event: </b> {{ $decline->event }}</p>
                        <p><b>Date: </b><small>({{ $decline->date }})</small></p>
                    </div>
                    <hr>
                    @endforeach
                    @else
                    <div class="event-approved">
                        <b>No declined reservation</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection