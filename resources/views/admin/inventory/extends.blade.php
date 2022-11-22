@extends('index')
@section('inventory_extends')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Extended</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if (!$rents->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Client</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Extended use</th>
                        <th>Extended return</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($rents as $rent)
                        <tr>
                            <td>{{ $rent->id }}</td>
                            <td><img src="{{ asset('stocks/'.$rent->stock->image) }}" alt=""></td>
                            <td>{{ $rent->stock->item }}</td>
                            <td>{{ $rent->info->name }}</td>
                            <td>{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</td>
                            <td>₱{{ $rent->amount }}</td>
                            <td>{{ $rent->amount / $rent->stock->price }}</td>
                            <td>{{ $rent->extends ? $rent->extends->date : $rent->date }}</td>
                            <td>{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                            <td>
                               Item is returned
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3><i>No extended</i></h3>
                </div>
                <a href="{{ route('inventory_for_rents') }}" style="text-decoration: none; color:#06283D"> <- Check approved items</a>
            @endif
        </div>
    </div>
</div>
@endsection