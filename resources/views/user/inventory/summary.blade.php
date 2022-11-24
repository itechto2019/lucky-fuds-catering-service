@extends('index')
@section('user_inventory_summary')
<div class="for-inventory-summary">
    <div class="for-page-title">
        <h1>Summary page</h1>
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
                        <th>Image</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach ($rents as $rent)
                    <tr>
                        <td>{{ $rent->id }}</td>
                        <td><img src="{{ $rent->stock->image }}" alt=""></td>
                        <td>{{ $rent->created_at->format('Y-m-d') }}</td>
                        <td>
                            <b>Item: </b>
                            {{ $rent->stock->item }}
                            <br>
                            <b>Client: </b>{{ $rent->info->name }}
                            <br>
                            <b>Address: </b>{{ $rent->address }}
                            <br>
                            <b>Method: </b>{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}
                            <br>
                            <b>Amount: </b>
                            ₱{{ $rent->amount }}
                            <br>
                            <b>Date Used: </b>
                            {{ $rent->extends ? $rent->extends->date :  $rent->date  }}
                            <br>
                            <b>Date Returned: </b>
                            {{ $rent->extends ? $rent->extends->return :  $rent->return }}
                            <br>
                            <b>Date rented: </b>
                            {{ $rent->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            {{$rent->status}}
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
                        <th>Image</th>
                        <th>Date Rented</th>
                        <th>Details</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach ($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td><img src="{{ $return->stock->image }}" alt=""></td>
                        <td>{{ $return->created_at->format('Y-m-d') }}</td>
                        <td>
                            <b>Item: </b>
                            {{ $return->stock->item }}
                            <br>
                            <b>Client: </b>{{ $return->info->name }}
                            <br>
                            <b>Address: </b>{{ $rent->address }}
                            <br>
                            <b>Method: </b>{{ $return->delivers ? "Deliver" : ($return->pickups ? "Pickup" : "") }}
                            <br>
                            <b>Amount: </b>
                            ₱{{ $return->amount }}
                            <br>
                            <b>Date Used: </b>
                            {{ $return->extends ? $return->extends->date :  $return->date  }}
                            <br>
                            <b>Date Return: </b>
                            {{ $return->extends ? $return->extends->return :  $return->return }}
                            <br>
                            <b>Date Returned: </b>
                            {{ $return->updated_at->format('Y-m-d') }}
                        </td>
                        <td>
                            {{$rent->status}}
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
