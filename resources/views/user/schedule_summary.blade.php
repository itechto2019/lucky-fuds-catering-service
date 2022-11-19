@extends('index')
@section('user_reseravation_summary')
<div class="for-inventory-summary">
    <div class="for-page-title">
        <h1>Reservation Summary Page</h1>
    </div>
    <div class="table-summary">

        <div class="table-form" >
            <h3>Reservation Summary</h3>
            @if (!$reserves->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                    </tr>
                    @foreach ($reserves as $reserve)
                    <tr>
                        <td>{{ $reserve->id }}</td>
                        <td>{{ $reserve->created_at->format('Y-m-d') }}</td>
                        <td>
                            <b>Client: </b>{{ $reserve->client }}
                            <br> <b>Contact: </b>{{ $reserve->contact}}
                            <br> <b>Email: </b>{{ $reserve->email}}
                            <br> <b>Prefered Contact: </b>{{ $reserve->method == 'email' ? $reserve->email : $reserve->contact}}
                            <br> <b>Date address: </b> {{ $reserve->address }}
                            <br> <b>Event: </b> {{ $reserve->event }}
                        </td>
                    </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3>No reservation found!</h3>
                </div>
                <a href="{{ route('user_schedule_reservation') }}" style="text-decoration: none; color:#06283D"> <- Schedule reservation?</a>
            @endif
        </div>
        
    </div>
    
</div>
@endsection
