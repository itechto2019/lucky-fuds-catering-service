@extends('index')
@section('user_schedule_confirmation')
<div class="for-schedule-reservation">
    <div class="for-page-title">
        <h1>Reservation Requests</h1>
        @if (!Auth::user()->info || !Auth::user()->validate)
            <div style="color:#FF1E1E;display: flex; align-items:center">
                <div>
                    <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <p>Please complete your profile to access other features</p>
                </div>
            </div>
        @endif
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if(!$reservations->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Date | Time Reservation</th>
                    <th>Details</th>
                    <th>Status</th>
                </tr>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->date  . ' | ' . $reservation->time}}</td>
                        <td>
                            <b>Package: </b>{{ $reservation->package->name }}
                            <br>
                            <b>Client: </b>{{$reservation->info->name}}
                            <br>
                            <b>Contact: </b>{{ $reservation->info->contact }}
                            <br>
                            <b>Email: </b>{{ $reservation->email }}
                            <br>
                            <b>Prefered contact: </b>{{ $reservation->info->method == "email" ? $reservation->email : ($reservation->info->method == "contact" ? $reservation->info->contact : "Not Set") }}
                            <br>
                            <b>Address: </b>{{ $reservation->address }}
                            <br>
                            <b>Event: </b>{{ $reservation->event }}
                            <br>
                            <b>No. of guest/s: </b>{{ $reservation->guest }}
                            <br>
                        </td>
                        <td>{{ $reservation->reserve->status }}</td>
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
</div>
@endsection