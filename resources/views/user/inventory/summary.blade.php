@extends('index')
@section('user_inventory_summary')
<div class="for-inventory-summary">
    <div class="for-page-title">
        <h1>Summary page</h1>
    </div>
    <div class="table-summary">
        <div style="padding: 10px">
            <div style="width:fit-content;">
                <button class="b-option" id="btn-rented" type="button" style="padding: 10px; border: none;font-size: 18px;cursor: pointer" onclick='activePage("rented")'>Rented</button>
                <span>/</span>
                <button class="b-option " id="btn-returned" type="button" style="padding: 10px; border: none;font-size: 18px; cursor: pointer" onclick='activePage("returned")'>Returned</button>
            </div>
        </div>
        <div class="table-form" id="table-rented">
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
                        <td>
                            <b>Item: </b>
                            {{ $rent->items }}
                            <br>
                            <b>Client: </b>{{ $rent->client }}
                            <br>
                            <b>Amount: </b>
                            ₱{{ $rent->amount }}
                            <br>
                            <b>Date rented: </b>
                            {{ $rent->created_at->format('Y-m-d') }}
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
        <div class="table-form" id="table-returned" style="display: none">
            <h3>Returned Summary</h3>
            @if (!$returns->isEmpty())
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
                        <td>
                            <b>Item: </b>
                            {{ $return->items }}
                            <br>
                            <b>Client: </b>{{ $return->client }}
                            <br>
                            <b>Amount: </b>
                            ₱{{ $return->amount }}
                            <br>
                            <b>Date Returned: </b>
                            {{ $return->updated_at->format('Y-m-d') }}
                        </td>
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
        if(activeState === "rented"){
            $('#table-rented').show();
            $('#table-returned').hide();

            $("#btn-rented").addClass("active-s");
            $("#btn-returned").removeClass("active-s");
        }else if(activeState === "returned"){
            $('#table-returned').show();
            $('#table-rented').hide();

            $("#btn-returned").addClass("active-s");
            $("#btn-rented").removeClass("active-s");
        }
    }

</script>
