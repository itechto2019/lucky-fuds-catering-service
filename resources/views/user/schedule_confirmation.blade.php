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
                            <b>Mode of payment: </b>{{ $reservation->payment->payment_method ? "Online Payment" : "Cash Payment"; }}
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
                        <td>
                            @if ($reservation->reserve->status == "approved" && $reservation->payment->payment_method)
                                @if (!$reservation->online_payment->image && !$reservation->online_payment->payment_status)
                                    <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                        <p style="margin: 5px">Your reservation is approved, pay via GCASH and submit <br>proof
                                            of payment here</p>
                                        <div class="input-group">
                                            <button onclick="payment({{ $reservation->id }})"
                                                style="padding: 10px; display:flex;justify-content: center;">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form" id="form-reserve-{{ $reservation->id }}" class="form-rents"
                                        style="display:none">
                                        <div class="form-data">
                                            <form action="{{ route('user_pay_reservation', $reservation->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                <h3>Proof of Payment</h3>
                                                <div style="padding: 10px">Admin GCash Number: <b>{{$admin->admin_info ? $admin->admin_info->contact : 'Not set'  }}</b></div>
                                                <div style="padding: 10px">Admin GCASH Name: <b>{{$admin->admin_info ? $admin->admin_info->name : 'Not set'  }}</b></div>
                                                <div class="input-group">
                                                    <label for="payment" style="font-size: 14px;user-select:none">Full payment</label>
                                                    <input type="radio" name="payment" value="full payment" style="cursor: pointer"
                                                        required checked>
                                                    <label for="payment" style="font-size: 14px;user-select:none">25% of the full Payment</label>
                                                    <input type="radio" style="cursor: pointer" value="25% of the full Payment" name="payment"
                                                        required>
                                                </div>
                                                @csrf
                                                <div class="input-group">
                                                    <input type="text" name="ref" placeholder="Reference number">
                                                </div>
                                                <div class="input-group">
                                                    <input type="file" name="image">
                                                </div>
                                                <div class="input-group" style="gap: 10px">
                                                    <button type="submit" style="width: auto">Submit</button>
                                                    <button class="cancel" type="button"
                                                        onclick="cancelPayment({{ $reservation->id }})"
                                                        style="background-color: gray; width: auto">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    @if (!$reservation->online_payment->payment_status)
                                        <p>Proof of payment submitted, wait for admin to received</p>
                                    @else
                                        <p>Proof of payment received</p>
                                    @endif
                                @endif
                            @else
                                {{$reservation->reserve->status}}
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
</div>
<script>
    function payment(id) {
        $('#form-reserve-' + id).show()
    }
    function cancelPayment(id) {
        $('#form-reserve-' + id).hide()
    }
</script>
@endsection