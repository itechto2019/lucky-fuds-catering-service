@extends('index')
@section('inventory_approves')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Approves</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if (!$rents->isEmpty())

            <table>
                <tr>
                    <th>#</th>
                    <th>Date for use</th>
                    <th>Date for return</th>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td>{{ $rent->extends ? $rent->extends->date : $rent->date }}</td>
                    <td>{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                    <td>{{ $rent->client }}</td>
                    <td>₱{{ $rent->amount }}</td>
                    <td>{{ $rent->status }}</td>
                    <td>
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