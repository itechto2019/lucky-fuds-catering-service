@extends('index')
@section('user_inventory_extends')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Extend Requests</h1>
        @if (!Auth::user()->info  || !Auth::user()->validate)
        <div style="color:#FF1E1E;display: flex; align-items:center">
            <div>
                <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
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
    <div class="table-reservation">
        <div class="table-form">
            @if (!$rents->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td><img src="{{ $rent->stock->image }}" alt=""></td>
                    <td>{{ $rent->stock->item }}</td>
                    <td style="width: 40%">
                        <p><b>Client:</b> {{ $rent->info->name }}</p>
                        <p><b>Method:</b> {{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</p>
                        <p><b>Mode of Payment:</b> {{ $rent->transaction->payment_method == 0 ? "Cash Payment" : "Online
                            Payment"}} / {{ $rent->transaction->extend_online_transaction ? "Online Payment" : "Cash Payment"}}</p>
                        <p><b>Address:</b> {{ $rent->address }}</p>
                        <p><b>Date for use:</b> {{ $rent->extends ? $rent->extends->date : $rent->date }}</p>
                        <p><b>Date for return:</b> {{ $rent->extends ? $rent->extends->return : $rent->return }}</p>
                        <p><b>Amount: ???</b>{{ $rent->amount }}</p>
                        <p><b>Quantity:</b> {{ $rent->quantity }}</p>
                    </td>
                    <td>{{ $rent->status }}</td>
                    <td>
                        @if($rent->extend_approve)
                            <p>Admin approve your extend</p>
                        @elseif($rent->extend_decline)
                            <p>Admin declined your extend</p>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <div style="padding: 10px">
                <h3><i>No extended</i></h3>
            </div>
            <a href="{{ route('user_inventory_for_rents') }}" style="text-decoration: none; color:#06283D">
                <- Check approved items</a>
                    @endif
        </div>
    </div>
</div>
@endsection