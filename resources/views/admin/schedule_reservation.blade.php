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
                    <td>{{ $reservation->info->name }}</td>
                    <td>
                        <b>Contact: </b>{{ $reservation->info->contact }}
                        <br>
                        <b>Email: </b>{{ $reservation->info->email }}
                        <br>
                        <b>Prefered contact: </b>{{ $reservation->info->method == "email" ? $reservation->info->email :
                        ($reservation->info->method == "contact" ? $reservation->info->contact : "Not Set") }}
                        <br>
                        <b>Address: </b>{{ $reservation->address }}
                        <br>
                        <b>Event: </b>{{ $reservation->event }}
                        <br>
                        <b>No. of guest/s: </b>{{ $reservation->guest }}
                        <br>
                        <br>
                        <b>Package: </b>{{ $reservation->package->name }}
                        <br>
                        <b>Price: </b> {{$reservation->package->price}}
                    </td>
                    <td>{{ $reservation->reserve->status }}</td>
                    <td>
                        @if($reservation->reserve->status == "pending")
                        <div class="action-form">
                            <div class="action-button">
                                <form action="{{ route('to_approve', $reservation->reserve->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button>Approve</button>
                                </form>
                            </div>
                            <div class="action-button">
                                <form action="{{ route('to_reject_reserve', $reservation->reserve->id) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <button>Decline</button>
                                </form>
                            </div>
                        </div>
                        @elseif($reservation->reserve->status == "approved")
                            <p>You've approved a reservation</p>
                        @elseif($reservation->reserve->status == "declined")
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
                    @if (!$approves->isEmpty())
                    @foreach ($approves as $approve)
                        <div class="event-approved">
                            <p><b>Client: </b>{{$approve->info->name}}</p>
                            <p><b>Contact: </b> {{ $approve->info->contact }}</p>
                            <p><b>Email: </b> {{ $approve->info->email }}</p>
                            <p><b>Prefered: </b> {{ $approve->info->method == "email" ? $approve->info->email :
                                ($approve->info->method == "contact" ? $approve->info->contact : "Not Set") }}</p>
                            <p><b>Event: </b> {{ $approve->user_reserve->event }}</p>
                            <p><b>Date: </b><small>({{ $approve->user_reserve->date }})</small></p>
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
                                <p><b>Client: </b>{{$decline->info->name}}</p>
                                <p><b>Contact: </b> {{ $decline->info->contact }}</p>
                                <p><b>Email: </b> {{ $decline->info->email }}</p>
                                <p><b>Prefered: </b> {{ $decline->info->method == "email" ? $decline->info->email :
                                    ($decline->info->method == "contact" ? $decline->info->contact : "Not Set") }}</p>
                                <p style="color: rgb(250, 83, 83)"><b>Event: </b> {{ $decline->user_reserve->event }}</p>
                                <p><b>Date: </b><small>({{ $decline->user_reserve->date }})</small></p>
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