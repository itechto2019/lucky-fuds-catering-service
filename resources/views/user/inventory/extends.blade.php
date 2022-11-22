@extends('index')
@section('user_inventory_extends')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Extended</h1>
        @if (!Auth::user()->info)
            <div style="color:#FF1E1E;display: flex; align-items:center">
                <div>
                    <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div><p>Please complete your profile to access other features</p></div>
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
                        <th>Client</th>
                        <th>Method</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Date for use</th>
                        <th>Extended use</th>
                        <th>Date for return</th>
                        <th>Extended return</th>
                    </tr>
                    @foreach ($rents as $rent)
                        <tr>
                            <td>{{ $rent->id }}</td>
                            <td><img src="{{ asset('stocks/' . $rent->stock->image) }}" alt=""></td>
                            <td>{{ $rent->stock->item }}</td>
                            <td>{{ $rent->info->name }}</td>
                            <td>{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</td>
                            <td>{{ $rent->info->address }}</td>
                            <td>₱{{ $rent->amount }}</td>
                            <td>{{ $rent->return }}</td>
                            <td>{{ $rent->extends ? $rent->extends->date : $rent->date }}</td>
                            <td>{{ $rent->return }}</td>
                            <td>{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3><i>No extended</i></h3>
                </div>
                <a href="{{ route('user_inventory_for_rents') }}" style="text-decoration: none; color:#06283D"> <- Check approved items</a>
            @endif
        </div>
    </div>
</div>
@endsection