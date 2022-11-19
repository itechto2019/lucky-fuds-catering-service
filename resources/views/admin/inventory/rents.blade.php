@extends('index')
@section('inventory_rents')
<div class="for-inventory-rents">
    <div class="for-page-title">
        <h1>Rented</h1>
    </div>
    <div class="table-reservation">
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
            @endforeach
        </div>
        <div class="table-form">
            @if (!$rents->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Client</th>
                    <th>Method</th>
                    <th>Date for use</th>
                    <th>Date for return</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td>{{$rent->items}}</td>
                    <td>{{ $rent->client }}</td>
                    <td>{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</td>
                    <td>{{ $rent->extends ? $rent->extends->date : $rent->date }}</td>
                    <td>{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                    <td>₱{{ $rent->amount }}</td>
                    <td>{{ $rent->status }}</td>
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
                <h3><i>No rented are available!</i></h3>
            </div>
            <a href="{{ route('inventory_for_rents') }}" style="text-decoration: none; color:#06283D">
                <- Check rented items</a>
                    @endif
        </div>
    </div>
</div>
@endsection