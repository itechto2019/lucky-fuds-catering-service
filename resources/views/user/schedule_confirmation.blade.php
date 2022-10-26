@extends('index')
@section('user_schedule_confirmation')
<div class="for-schedule-reservation">
    <div class="for-page-title">
        <h1>Reservation Requests</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if($reservations->isNotEmpty())
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
                        <td>{{ $reservation->package->name }}</td>
                        <td>{{ $reservation->status }}</td>
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