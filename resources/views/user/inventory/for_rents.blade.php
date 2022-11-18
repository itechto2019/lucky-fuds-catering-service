@extends('index')
@section('user_inventory_for_rents')
<div class="for-inventory-for-rents">
    <div class="for-page-title">
        <h1>For Rents</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            <div class="error-message">
                @foreach ($errors->all() as $error)
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #1a1a1a1">{{$error}}</div>
                @endforeach
            </div>
            @if (!$supplies->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th></th>
                </tr>
                @foreach ($supplies as $supply)
                @foreach ($supply->for_rents as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td><img src="{{ asset("stocks") }}/{{ $supply->image }}" width="50" alt=""></td>
                    <td>{{ $supply->item }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ $supply->price }}</td>
                    <td>
                        <div class="action-form">
                            @if ($item->quantity > 0)
                            <div class="action-button">
                                <button class="action-print" onclick="rent({{ $item->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                    Rent
                                </button>
                            </div>
                            @else
                            <div class="action-button">
                                Out of Stock
                            </div>
                            @endif
                            <div class="form" id="form-rent-{{ $item->id }}" class="form-rents" style="display:none">
                                <div class="form-data">
                                    <form action="{{ route('user_rent', [$item->id,  $item->stock_id]) }}"
                                        method="POST">
                                        <h3>Renting Information</h3>
                                        @csrf
                                        <input type="hidden" name="items" value="{{$supply->item}}">
                                        <div class="input-group">
                                            <input type="text" name="client" placeholder="Name"
                                                id="name-{{ $item->id }}" value="{{ Auth::user()->name }}" required disabled>
                                        </div>
                                        <div class="input-group" style="display: block">
                                            <input type="number" name="quantity" id="q-{{ $item->id }}"
                                                placeholder="Quantity"
                                                onchange="calculateQuantity({{ $item->id }}, {{ $item->quantity }}, {{ $supply->price }})"
                                                value="{{ $item->quantity }}" required>
                                            <div style="padding: 5px 0px">
                                                <span style="color:#FF1E1E;" id="q-limit-{{ $item->id }}"></span>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input type="number" name="amount" disabled id="amount-{{ $item->id }}"
                                                onchange="amountChangeError({{ $item->id }}, {{ $item->quantity }}, {{ $supply->price }})"
                                                placeholder="Amount" value="{{ $item->quantity  * $supply->price}}"
                                                required>
                                        </div>
                                        <div class="input-group">
                                            <input type="radio" name="method" value="pickup" style="cursor: pointer" required>
                                            <label for="method" style="user-select:none">Pickup</label>
                                            <input type="radio" style="cursor: pointer" value="deliver" name="method" required>
                                            <label for="method" style="user-select:none">Deliver</label>
                                        </div>
                                        <div class="input-group">
                                            <label for="">Date to use(?): </label>
                                            <input type="date" name="date" required>
                                        </div>
                                        <div class="input-group">
                                            <label for="">Date to return(?): </label>
                                            <input type="date" name="return" required>
                                        </div>
                                        <div class="input-group">
                                            <button type="submit">Submit</button>
                                            <div class="input-group">
                                                <button class="cancel" type="button"
                                                    onclick="cancelRent({{ $item->id }})">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforeach
            </table>
            @else
                <div style="padding: 10px">
                    <h3>No items are available</h3>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
<script>
    var modalState = false;
    function rent(id) {
        if(!modalState) {
            modalState = !modalState
            $(`#form-rent-${id}`).show()
        }else {
            modalState = !modalState
            $(`#form-rent-${id}`).hide()
        }
    }
    function cancelRent(id){
        modalState = !modalState
        $(`#form-rent-${id}`).hide()
    }
    function calculateQuantity(id, ql, p) {
        let q = $(`#q-${id}`).val()
        $(`#amount-${id}`).val(q * p)
        if(q > ql) {
            $(`#q-${id}`).val(ql)
            $(`#amount-${id}`).val(ql * p)
            $(`#q-limit-${id}`).text("You can't pickup above the quantity")
        }else if(q < 1){
            $(`#q-${id}`).val(1)
            $(`#amount-${id}`).val(p)
        }else{
            $(`#q-limit-${id}`).text("")
        }
    }
    function amountChangeError(id,q , p) {
        $(`#amount-${id}`).attr("disabled", "")
        $(`#amount-${id}`).val(q * p)
    }
    setTimeout(() => {
       $('.error-message').css({
            display: 'none'
       })
    }, 3000);
</script>