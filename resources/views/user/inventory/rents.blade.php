@extends('index')
@section('inventory_rents')
<div class="for-inventory-rents">
    <div class="for-page-title">
        <h1>Rented</h1>
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
                        <th></th>
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
                                    @if ($rent->status == "approved")
                                    <button class="action-print" onclick="extend({{ $rent->id }})">
                                        Extend
                                    </button>
                                    @elseif($rent->status == "extended")
                                    <p>Waiting to return</p>
                                    @elseif($rent->status == "extending" || $rent->status == "pending")
                                    <p>Waiting for approval</p>
                                    @elseif($rent->status == "declined")
                                    <p>Admin declined your approval</p>
                                    @elseif($rent->status == "returned")
                                    <p>Successfully returned</p>
                                    @endif
                                </div>
                                <div class="form" id="form-extends-{{ $rent->id }}" class="form-rents" style="display:none">
                                    <div class="form-data">
                                        <form action="{{ route('user_extends', $rent->id) }}" method="POST">
                                            <h3>Extend Rental</h3>
                                            <div style="padding: 10px">Client: <b>{{ $rent->client }}</b></div>
                                            @csrf
                                            <div class="input-group">
                                                <label for="">Date to use(?): </label>
                                                <input type="date" name="date" value="{{ $rent->date }}">
                                            </div>
                                            <div class="input-group">
                                                <label for="">Date to return(?): </label>
                                                <input type="date" name="return" value="{{ $rent->return }}">
                                            </div>
                                            <div class="input-group">
                                                <button type="submit">Submit</button>
                                                <div class="input-group">
                                                    <button class="cancel" type="button"
                                                        onclick="cancelExtend({{ $rent->id }})">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3>No rented found!</h3>
                </div>
                <a href="{{ route('user_inventory_for_rents') }}" style="text-decoration: none; color:#06283D"> <- Rent a service?</a>
            @endif
        </div>
    </div>
</div>
@endsection
<script>
    function extend(id) {
        $(`#form-extends-${id}`).show()
    }
    function cancelExtend(id) {
        $(`#form-extends-${id}`).hide()
    }
   
</script>