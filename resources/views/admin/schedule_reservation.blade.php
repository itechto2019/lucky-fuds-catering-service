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
                            {{ $reservation->contact }}
                            <br>
                            {{ $reservation->email }}
                            <br>
                            {{ $reservation->address }}
                            <br>
                            <b>Date: </b>{{ date('M d, Y', strtotime($reservation->date)) }}
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
                    @foreach ($approves as $approve)
                        <div class="event-approved">
                            <p>{{ $approve->event }}</p>
                            <small>({{ $approve->date }})</small>
                        </div>
                        <hr>
                    @endforeach
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
                                <p style="color: rgb(250, 83, 83)">{{ $decline->event }}</p>
                                <small>({{ $decline->date }})</small>
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