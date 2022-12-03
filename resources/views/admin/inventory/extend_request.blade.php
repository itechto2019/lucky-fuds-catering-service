@extends('index')
@section('extend_request')
<div class="for-inventory-rents">
    <div class="for-page-title">
        <h1>Extend Request</h1>
    </div>
    <div class="table-reservation">
        <div class="error-message">
            @foreach ($errors->all() as $error)
            <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
            @endforeach
        </div>
        @if(session()->has('message'))
        <div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">{{ session()->get('message')
            }}</div>
        @endif
        @if(session()->has('decline'))
        <div style="padding: 15px; margin:5px; background-color: #F7A76C; color: #1a1a1a1">{{ session()->get('decline')
            }}</div>
        @endif
        <div class="table-form">
            @if (!$rents->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td><img src="{{ $rent->stock->image }}"></td>
                    <td>{{ $rent->stock->item}}</td>
                    <td style="width: 40%">
                        <p><b>Client:</b> {{ $rent->info->name }}</p>
                        <p><b>Method:</b> {{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</p>
                        <p><b>Mode of Payment:</b> {{ $rent->transaction->online_transaction ? "Online Payment" : "Cash Payment"}}</p>
                        <p><b>Address:</b> {{ $rent->address }}</p>
                        <p><b>Date for use:</b> {{ $rent->extends ? $rent->extends->date : $rent->date }}</p>
                        <p><b>Date for return:</b> {{ $rent->extends ? $rent->extends->return : $rent->return }}</p>
                        <p><b>Amount: ₱</b>{{ $rent->amount }}</p>
                        <p><b>Quantity:</b> {{ $rent->quantity }}</p>
                    </td>
                    <td>{{$rent->status}}</td>
                    <td>
                        <div class="action-form">
                            <div class="action-button">
                                <form action="{{ route('to_checkout', $rent->id) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <button class="action-print">
                                        Approve
                                    </button>
                                </form>
                            </div>
                            <div class="action-button">
                                <form action="{{ route('to_reject' , $rent->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="action-print">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach

            </table>
            @else
            <div style="padding: 10px">
                <h3><i>No Extends are available!</i></h3>
            </div>
            <a href="{{ route('inventory_for_rents') }}" style="text-decoration: none; color:#06283D">
                <- Check rented items</a>
                    @endif
        </div>
    </div>
</div>
@endsection