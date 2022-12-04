@extends('index')
@section('inventory_for_rents')
<div class="for-inventory-for-rents">
    <div class="for-page-title">
        <h1>For Rents</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            <form action="?search=" style="width:100%;display: flex;align-items:center">
                <div class="input-group" style="width:80%;position: relative">
                    <input type="text" name="search" style="padding-left: 15px;border-style:none;border-radius: 5px;background-color: #A8CD96" placeholder="Search item" />
                    <svg style="position: absolute;right: 0;padding: 23px;width: 25px; height: 25px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </form>
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
                        <tr>
                            <td>{{ $supply->id }}</td>
                            <td><img src="{{ $supply->stock->image }}" width="50" alt=""></td>
                            <td>{{ $supply->stock->item }}</td>
                            <td>{{ $supply->quantity }}</td>
                            <td>{{ $supply->quantity > 0 ? 'Active' : 'Out of Stock' }}</td>
                            <td>₱{{ $supply->stock->price }}</td>
                        </tr>
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