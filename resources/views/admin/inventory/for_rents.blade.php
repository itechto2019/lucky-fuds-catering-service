@extends('index')
@section('inventory_for_rents')
<div class="for-inventory-for-rents">
    <div class="for-page-title">
        <h1>For Rents</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if (!$supplies->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Quantity available</th>
                        <th>Status</th>
                        <th>Price</th>
                    </tr>
                    @foreach ($supplies as $supply)
                        @foreach ($supply->for_rents as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><img src="{{ asset("stocks") }}/{{ $supply->image }}" width="50" alt=""></td>
                                <td>{{ $supply->item }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->quantity > 0 ? 'Active' : 'Out of Stock' }}</td>
                                <td>₱{{ $supply->price }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3><i>No items are available to rent</i></h3>
                </div>
                <a href="{{ route('inventory_stocks') }}" style="text-decoration: none; color:#06283D"> <- Check items</a>
            @endif
            
        </div>
    </div>

</div>
@endsection