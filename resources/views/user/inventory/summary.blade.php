@extends('index')
@section('user_inventory_summary')
<div class="for-inventory-summary">
    <div class="for-page-title">
        <h1>Summary page</h1>
    </div>
    <div class="table-summary">
        <div style="padding: 10px">
            <div style="width:fit-content;">
                <button class="b-option active-s" id="btn-reserve" type="button" style="padding: 10px; border: none;font-size: 18px; cursor: pointer" onclick='activePage("reserves")'>Reservation</button>
                <span>/</span>
                <button class="b-option" id="btn-rented" type="button" style="padding: 10px; border: none;font-size: 18px;cursor: pointer" onclick='activePage("rented")'>Rented</button>
                <span>/</span>
                <button class="b-option " id="btn-returned" type="button" style="padding: 10px; border: none;font-size: 18px; cursor: pointer" onclick='activePage("returned")'>Returned</button>
            </div>
        </div>
        <div class="table-form" id="table-reserve">
            <h3>Reservation Summary</h3>
            @if (!$reserves->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                    </tr>
                    @foreach ($reserves as $reserve)
                    <tr>
                        <td>{{ $reserve->id }}</td>
                        <td>{{ $reserve->created_at->format('Y-m-d') }}</td>
                        <td><b>Client: </b>{{ $reserve->client }} <br> <b>Contact: </b>{{ $reserve->method == 'email' ? $reserve->email : $reserve->contact}} <br> <b>Date address: </b> {{ $reserve->address }} <br> <b>Event: </b> {{ $reserve->event }} </td>
                    </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3>No reservation found!</h3>
                </div>
                <a href="{{ route('user_schedule_reservation') }}" style="text-decoration: none; color:#06283D"> <- Schedule reservation?</a>
            @endif
        </div>
        <div class="table-form" id="table-rented" style="display: none">
            <h3>Rented Summary</h3>
            @if (!$rents->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                    </tr>
                    @foreach ($rents as $rent)
                    <tr>
                        <td>{{ $rent->id }}</td>
                        <td>{{ $rent->created_at->format('Y-m-d') }}</td>
                        <td><b>Client: </b>{{ $rent->client }} <br> <b>Amount: </b>₱{{ $rent->amount }} <br> <b>Date reservation: </b> {{ $rent->date }} </td>
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
        <div class="table-form" id="table-returned" style="display: none">
            <h3>Returned Summary</h3>
            @if (!$rents->isEmpty())
                <table>
                    <tr>
                        <th>#</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                    </tr>
                    @foreach ($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->created_at->format('Y-m-d') }}</td>
                        <td><b>Item: </b>{{ $return->item }} <br> <b>Amount: </b>₱{{ $return->amount }}</td>
                    </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3>No returned found!</h3>
                </div>
                <a href="{{ route('user_inventory_rents') }}" style="text-decoration: none; color:#06283D"> <- Rent a service?</a>
            @endif
            
        </div>
    </div>
    
</div>
@endsection
<script>

    function activePage(activeState) {
        if(activeState === "reserves") {
            $('#table-reserve').show()
            $('#table-rented').hide();
            $('#table-returned').hide();

            $("#btn-reserve").addClass("active-s");
            $("#btn-returned").removeClass("active-s");
            $("#btn-rented").removeClass("active-s");
        }else if(activeState === "rented"){
            $('#table-rented').show();
            $('#table-returned').hide();
            $('#table-reserve').hide()

            $("#btn-rented").addClass("active-s");
            $("#btn-returned").removeClass("active-s");
            $("#btn-reserve").removeClass("active-s");
        }else if(activeState === "returned"){
            $('#table-returned').show();
            $('#table-rented').hide();
            $('#table-reserve').hide()

            $("#btn-returned").addClass("active-s");
            $("#btn-rented").removeClass("active-s");
            $("#btn-reserve").removeClass("active-s");
        }
    }

</script>
