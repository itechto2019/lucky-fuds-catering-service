@extends('index')
@section('schedule_reservation')
<div class="for-schedule-reservation">
    <div class="for-page-title">
        <h1>Reservation</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if(session()->has('message'))
                <div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">{{ session()->get('message') }}</div>
            @endif
            @if(session()->has('reject'))
                <div style="padding: 15px; margin:5px; background-color: #F7A76C; color: #1a1a1a1">{{ session()->get('reject') }}</div>
            @endif
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
                        <b>Email: </b>{{ $reservation->email }}
                        <br>
                        <b>Prefered contact: </b>{{ $reservation->info->method == "email" ? $reservation->email :
                        ($reservation->info->method == "contact" ? $reservation->info->contact : "Not Set") }}
                        <br>
                        <b>Mode of payment: </b>{{ $reservation->payment->payment_method ? "Online Payment" : "Cash Payment"; }}
                        <br>
                        <b>Address: </b>{{ $reservation->address }}
                        <br>
                        <b>Event: </b>{{ $reservation->event }}
                        <br>
                        <br>
                        <b>No. of guest/s: </b>{{ $reservation->guest }}
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
                            @if ($reservation->payment->payment_method)
                                @if ($reservation->online_payment->image && !$reservation->online_payment->payment_status)
                                    <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                        <h3>Proof Of Payment</h3>
                                        <b>{{$reservation->variant->variant}}</b>
                                        <p><b>Reference number:</b><br>{{$reservation->online_payment->reference}}
                                        </p>
                                        <img src="{{$reservation->online_payment->image}}"
                                            style="width: 200px; cursor: pointer;"
                                            onclick="openImage({{$reservation->id}},'{{$reservation->online_payment->image}}')">
                                            <form action="{{ route('accept_reservation', $reservation->id) }}" method="POST">
                                                @csrf
                                                @method('patch')
                                                <div class="input-group">
                                                    <button class="action-print">
                                                        Receive
                                                    </button>
                                                </div>
                                            </form>
                                        <div class="show-ref" id="ref-{{$reservation->id}}" style="display: none">
                                            <svg onclick="hide('{{$reservation->id}}')"
                                                style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <img src="" id="proof-{{$reservation->id}}">
                                        </div>
                                    </div>
                                @else
                                    @if (!$reservation->online_payment->payment_status)
                                        <p>You've approved this reservation, wait for the proof of payment</p>
                                    @else
                                        <p>Proof of payment received</p>
                                    @endif
                                @endif
                            @else
                                <p>You've approved a reservation</p>
                            @endif
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
                            <p><b>Email: </b> {{ $approve->info->user->email }}</p>
                            <p><b>Prefered: </b> {{ $approve->info->method == "email" ? $approve->info->user->email :
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
                                <p><b>Email: </b> {{ $decline->email }}</p>
                                <p><b>Prefered: </b> {{ $decline->info->method == "email" ? $decline->email :
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
<script>
    function openImage(id,url) {
        $(`#ref-${id}`).show()
        $(`#proof-${id}`).attr('src', url)
    }
    function hide(id) {
        $(`#ref-${id}`).hide()
    }
</script>
@endsection