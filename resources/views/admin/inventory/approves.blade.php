@extends('index')
@section('inventory_approves')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Approves</h1>
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
                    <th>Image</th>
                    <th>Item</th>
                    <th>Client</th>
                    <th>Method</th>
                    <th>Address</th>
                    <th>Date for use</th>
                    <th>Date for return</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td><img src="{{ asset('stocks/' . $rent->stock->image) }}"></td>
                    <td>{{ $rent->stock->item}}</td>
                    <td>{{ $rent->info->name }}</td>
                    <td>{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</td>
                    <td>{{ $rent->info->address }}</td>
                    <td>{{ $rent->extends ? $rent->extends->date : $rent->date }}</td>
                    <td>{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                    <td>₱{{ $rent->amount }}</td>
                    <td>{{ $rent->amount / $rent->stock->price }}</td>
                    <td>{{ $rent->status }}</td>
                    <td>
                        @if ($rent->status == "returned")
                            item is returned
                        @else
                            <div class="action-form">
                                <div class="action-button">
                                    <form action="{{ route('to_return', $rent->id) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <button class="action-print">
                                            Returned
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </td>
                    @endforeach
            </table>
            @else
            <div style="padding: 10px">
                <h3><i>No approve items</i></h3>
            </div>
            <a href="{{ route('inventory_rents') }}" style="text-decoration: none; color:#06283D">
                <- Check rented</a>
                    @endif

        </div>
    </div>
</div>
@endsection