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
                        <th>Item</th>
                        <th>Client</th>
                        <th>Item</th>
                        <th>Amount</th>
                        <th>Date for use</th>
                        <th>Extended use</th>
                        <th>Date for return</th>
                        <th>Extended return</th>
                    </tr>
                    @foreach ($rents as $rent)
                        <tr>
                            <td>{{ $rent->id }}</td>
                            <td>{{ $rent->items }}</td>
                            <td>{{ $rent->client }}</td>
                            <td>{{ $rent->returns->item }}</td>
                            <td>₱{{ $rent->amount }}</td>
                            <td><small>{{ $rent->return }}</small></td>
                            <td><small>{{ $rent->extends ? $rent->extends->date : $rent->date }}</small></td>
                            <td><small>{{ $rent->return }}</small></td>
                            <td><small>{{ $rent->extends ? $rent->extends->return : $rent->return }}</small></td>
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