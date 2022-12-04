@extends('index')
@section('inventory_rents')
<div class="for-inventory-rents">
    <div class="for-page-title">
        <h1>Rented</h1>
        @if (!Auth::user()->info || !Auth::user()->validate)
        <div style="color:#FF1E1E;display: flex; align-items:center">
            <div>
                <svg style="width: 40px; height: 40px" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <p>Please complete your profile to access other features</p>
            </div>
        </div>
        @endif
    </div>
    <div class="table-reservation">
        <div class="table-form" style="max-height: 500px">
            <div class="error-message">
                @foreach ($errors->all() as $error)
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #1a1a1a1">{{$error}}</div>
                @endforeach
            </div>
            @if(session()->has('message'))
            <div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">{{
                session()->get('message') }}</div>
            @endif
            @if (!$rents->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td><img src="{{ $rent->stock->image }}" alt=""></td>
                    <td>{{ $rent->stock->item }}</td>
                    <td style="width: 40%">
                        <p><b>Client:</b> {{ $rent->info->name }}</p>
                        <p><b>Method:</b> {{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</p>
                        <p><b>Mode of Payment:</b> {{ $rent->transaction->payment_method == 0 ? "Cash Payment" : "Online
                            Payment"}} / {{ $rent->transaction->extend_online_transaction ? "Online Payment" : "Cash Payment"}}</p>
                        <p><b>Address:</b> {{ $rent->address }}</p>
                        <p><b>Date for use:</b> {{ $rent->extends ? $rent->extends->date : $rent->date }}</p>
                        <p><b>Date for return:</b> {{ $rent->extends ? $rent->extends->return : $rent->return }}</p>
                        <p><b>Amount: ₱</b>{{ $rent->amount }}</p>
                        <p><b>Quantity:</b> {{ $rent->quantity }}</p>
                    </td>
                    <td>{{ $rent->status }}</td>
                    <td>
                        <div class="action-form">
                            <div class="action-button" style="text-align: center">
                                @if($rent->status === "approved" && $rent?->rent_approve && !$rent->return)
                                    <p>Admin approved your request</p>
                                @elseif($rent?->rent_decline)
                                    <p>Admin declined your request</p>

                                @elseif($rent->status === "extended" && $rent?->extend_approve)
                                    @if ($rent->transaction->offline_transaction && $rent->online_extend_transaction)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <p style="margin: 5px">Your rent is approved,<br>pay via GCASH and submit <br>proof
                                                of payment here</p>
                                            <button onclick="payment({{ $rent->id }})"
                                                style="padding: 10px; display:flex;justify-content: center;">
                                                Submit
                                            </button>
                                        </div>
                                        <div class="form" id="form-payment-{{ $rent->id }}" class="form-rents"
                                            style="display:none">
                                            <div class="form-data">
                                                <form action="{{ route('user_online_payment', $rent->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    <h3>Proof of Payment</h3>
                                                    <div style="padding: 10px">Admin GCash Number: <b>{{$admin->admin_info && $admin->admin_info->contact ? : "Not set" }}</b></div>
                                                    <div style="padding: 10px">Admin GCASH Name: {{$admin->admin_info && $admin->admin_info->name ? : "Not set" }}<b></b></div>
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="text" name="ref" placeholder="Reference number">
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="file" name="image">
                                                    </div>
                                                    <div class="input-group" style="gap: 10px">
                                                        <button type="submit" style="width: auto">Submit</button>
                                                        <button class="cancel" type="button"
                                                            onclick="cancelPayment({{ $rent->id }})"
                                                            style="background-color: gray; width: auto">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($rent->transaction->offline_transaction && $rent->extend_online_transaction && !$rent->extend_online_transaction->image)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <p style="margin: 5px">Your extension is approved,<br>pay via GCASH and submit <br>proof
                                                of payment here</p>
                                            <button onclick="payment({{ $rent->id }})"
                                                style="padding: 10px; display:flex;justify-content: center;">
                                                Submit
                                            </button>
                                        </div>
                                        <div class="form" id="form-payment-{{ $rent->id }}" class="form-rents"
                                            style="display:none">
                                            <div class="form-data">
                                                <form action="{{ route('user_online_payment', $rent->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    <h3>Proof of Payment</h3>
                                                    <div style="padding: 10px">Admin GCash Number: <b>{{$admin->admin_info && $admin->admin_info->contact ? $admin->admin_info->contact : "Not set" }}</b></div>
                                                    <div style="padding: 10px">Admin GCASH Name: {{$admin->admin_info && $admin->admin_info->name ?  $admin->admin_info->name : "Not set" }}<b></b></div>
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="text" name="ref" placeholder="Reference number">
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="file" name="image">
                                                    </div>
                                                    <div class="input-group" style="gap: 10px">
                                                        <button type="submit" style="width: auto">Submit</button>
                                                        <button class="cancel" type="button"
                                                            onclick="cancelPayment({{ $rent->id }})"
                                                            style="background-color: gray; width: auto">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($rent->extend_online_transaction && $rent->extend_online_transaction->image)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <p><b>Reference number:</b><br>{{$rent->extend_online_transaction->reference}}
                                            </p>
                                            <img src="{{$rent->extend_online_transaction->image}}"
                                                style="width: 200px; cursor: pointer;"
                                                onclick="openImage({{$rent->id}},'{{$rent->extend_online_transaction->image}}')">
                                            @if (!$rent->extend_online_transaction->payment_status)
                                                <p>Proof of Payment submitted, waiting for admin to receive</p>
                                            @else
                                                <p>Proof of Payment received</p>
                                            @endif
                                            <div class="show-ref" id="ref-{{$rent->id}}" style="display: none">
                                                <svg onclick="hide('{{$rent->id}}')"
                                                    style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <img src="" id="proof-{{$rent->id}}">
                                            </div>
                                        </div>
                                    @else
                                        @if ($rent->transaction->payment_method)
                                            <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                                <p style="margin: 5px">Your extension is approved,<br>pay via GCASH and submit <br>proof
                                                    of payment here</p>
                                                <button onclick="payment({{ $rent->id }})"
                                                    style="padding: 10px; display:flex;justify-content: center;" @disabled(!$admin->admin_info)>
                                                    Submit
                                                </button>
                                            </div>
                                            <div class="form" id="form-payment-{{ $rent->id }}" class="form-rents"
                                                style="display:none">
                                                <div class="form-data">
                                                    <form action="{{ route('user_online_payment', $rent->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        <h3>Proof of Payment</h3>
                                                        <div style="padding: 10px">Admin GCash Number: <b>{{$admin->admin_info && $admin->admin_info->contact ? $admin->admin_info->contact : "Not set" }}</b></div>
                                                        <div style="padding: 10px">Admin GCASH Name: {{$admin->admin_info && $admin->admin_info->name ?  $admin->admin_info->name : "Not set" }}<b></b></div>
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="text" name="ref" placeholder="Reference number" @disabled(!$admin->admin_info)>
                                                        </div>
                                                        <div class="input-group">
                                                            <input type="file" name="image" @disabled(!$admin->admin_info)>
                                                        </div>
                                                        <div class="input-group" style="gap: 10px" >
                                                            <button type="submit" style="width: auto" @disabled(!$admin->admin_info)>Submit</button>
                                                            <button class="cancel" type="button"
                                                                onclick="cancelPayment({{ $rent->id }})"
                                                                style="background-color: gray; width: auto" @disabled(!$admin->admin_info)>Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <p>Admin approved your extension.</p>
                                        @endif
                                    @endif

                                @elseif($rent?->extend_decline)
                                    <p>Admin declined your extension request</p>

                                @elseif($rent?->status === "returned" && $rent?->return)
                                    <p>Item is returned</p>

                                @elseif($rent->status == "approved" && $rent->rent_approve)
                                    @if ($rent->transaction->online_transaction && !$rent->transaction->online_transaction->image)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <p style="margin: 5px">Your rent is approved,<br>pay via GCASH and submit <br>proof of payment here</p>
                                            <button onclick="payment({{ $rent->id }})"
                                                style="padding: 10px; display:flex;justify-content: center;" @disabled(!$admin->admin_info)>
                                                Submit
                                            </button>
                                        </div>
                                        <div class="form" id="form-payment-{{ $rent->id }}" class="form-rents"
                                            style="display:none">
                                            <div class="form-data">
                                                <form action="{{ route('user_online_payment', $rent->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    <h3>Proof of Payment</h3>
                                                    <div style="padding: 10px">Admin GCash Number: <b>{{$admin->admin_info && $admin->admin_info->contact ? $admin->admin_info->contact : "Not set" }}</b></div>
                                                    <div style="padding: 10px">Admin GCASH Name: <b>{{$admin->admin_info && $admin->admin_info->name ? $admin->admin_info->name: "Not set" }}</b></div>
                                                    @csrf 
                                                    <div class="input-group">
                                                        <input type="text" name="ref" placeholder="Reference number" @disabled(!$admin->admin_info)>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="file" name="image" @disabled(!$admin->admin_info)>
                                                    </div>
                                                    <div class="input-group" style="gap: 10px">
                                                        <button type="submit" style="width: auto" @disabled(!$admin->admin_info)>Submit</button>
                                                        <button class="cancel" type="button"
                                                            onclick="cancelPayment({{ $rent->id }})"
                                                            style="background-color: gray; width: auto" @disabled(!$admin->admin_info)>Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        @if ($rent->transaction->online_transaction && $rent->transaction->online_transaction->image)
                                            <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                                <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                                    <p><b>Reference number:</b><br>{{$rent->transaction->online_transaction->reference}}
                                                    </p>
                                                    <img src="{{$rent->transaction->online_transaction->image}}"
                                                        style="width: 200px; cursor: pointer;"
                                                        onclick="openImage({{$rent->id}},'{{$rent->transaction->online_transaction->image}}')">
                                                    @if (!$rent->transaction->online_transaction->payment_status)
                                                        <p>Proof of Payment submitted, waiting for admin to receive</p>
                                                    @else
                                                        <p>Proof of Payment received</p>
                                                        @if (!$rent->extends)
                                                            <div class="input-group" style="display:grid;place-content:center">
                                                                <button type="button" style="background-color: #67A93A;text-align:center" onclick="extend({{$rent->id}})">Extend</button>
                                                            </div>
                                                            <div class="form" id="form-extends-{{ $rent->id }}" class="form-rents"
                                                                style="display:none">
                                                                <div class="form-data">
                                                                    <form action="{{ route('user_extends', $rent->id) }}" method="POST">
                                                                        <h3>Extend Rental</h3>
                                                                        <div style="padding: 10px">Contact: <b>{{ $rent->info && $rent->info->method
                                                                                == "email" ? Auth::user()->email : ($rent->info &&
                                                                                $rent->info->method == "contact" ? $rent->info->contact : "Not
                                                                                set")}}</b></div>
                                                                        <div style="padding: 10px">Address: <b>{{ $rent->info->address }}</b></div>
                                                                        @csrf
                                                                        <div class="input-group" style="position: relative">
                                                                            <span style="position: absolute; top: 0%">Date to use(?): </span>
                                                                            <input type="date" name="date" value="{{ $rent->date }}" min={{now()}}>
                                                                        </div>
                                                                        <div class="input-group" style="position: relative">
                                                                            <span style="position: absolute; top: 0%">Date to return(?):</span>
                                                                            <input type="date" name="return" value="{{ $rent->return }}"
                                                                                min={{now()}} max={{Carbon\Carbon::today()->addDays(6)}}>
                                                                        </div>
                                                                        <div class="input-group">
                                                                            <label for="payment"
                                                                                style="font-size: 14px;user-select:none">Cash</label>
                                                                            <input type="radio" name="payment" value="cash" style="cursor: pointer"
                                                                                required checked>
                                                                            <label for="payment" style="font-size: 14px;user-select:none">Online
                                                                                Payment</label>
                                                                            <input type="radio" style="cursor: pointer" value="online"
                                                                                name="payment" required>
                                                                        </div>
                                                                        <div class="input-group">
                                                                            <button type="submit"
                                                                                style="background-color: #67A93A; color: #1A1A1A; width: auto;">Submit</button>
                                                                            <div class="input-group">
                                                                                <button class="cancel" type="button"
                                                                                    onclick="cancelExtend({{ $rent->id }})"
                                                                                    style="background-color: #9D9D9D;">Cancel</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="show-ref" id="ref-{{$rent->id}}" style="display: none">
                                                        <svg onclick="hide('{{$rent->id}}')"
                                                            style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <img src="" id="proof-{{$rent->id}}">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p>Your rent is approved</p>
                                            <div class="input-group" style="display:grid;place-content:center">
                                                <button type="button" style="background-color: #67A93A;text-align:center" onclick="extend({{$rent->id}})">Extend</button>
                                            </div>
                                            <div class="form" id="form-extends-{{ $rent->id }}" class="form-rents"
                                                style="display:none">
                                                <div class="form-data">
                                                    <form action="{{ route('user_extends', $rent->id) }}" method="POST">
                                                        <h3>Extend Rental</h3>
                                                        <div style="padding: 10px">Contact: <b>{{ $rent->info && $rent->info->method
                                                                == "email" ? Auth::user()->email : ($rent->info &&
                                                                $rent->info->method == "contact" ? $rent->info->contact : "Not
                                                                set")}}</b></div>
                                                        <div style="padding: 10px">Address: <b>{{ $rent->info->address }}</b></div>
                                                        @csrf
                                                        <div class="input-group" style="position: relative">
                                                            <span style="position: absolute; top: 0%">Date to use(?): </span>
                                                            <input type="date" name="date" value="{{ $rent->date }}" min={{now()}}>
                                                        </div>
                                                        <div class="input-group" style="position: relative">
                                                            <span style="position: absolute; top: 0%">Date to return(?):</span>
                                                            <input type="date" name="return" value="{{ $rent->return }}"
                                                                min={{now()}} max={{Carbon\Carbon::today()->addDays(6)}}>
                                                        </div>
                                                        <div class="input-group">
                                                            <label for="payment"
                                                                style="font-size: 14px;user-select:none">Cash</label>
                                                            <input type="radio" name="payment" value="cash" style="cursor: pointer"
                                                                required checked>
                                                            <label for="payment" style="font-size: 14px;user-select:none">Online
                                                                Payment</label>
                                                            <input type="radio" style="cursor: pointer" value="online"
                                                                name="payment" required>
                                                        </div>
                                                        <div class="input-group">
                                                            <button type="submit"
                                                                style="background-color: #67A93A; color: #1A1A1A; width: auto;">Submit</button>
                                                            <div class="input-group">
                                                                <button class="cancel" type="button"
                                                                    onclick="cancelExtend({{ $rent->id }})"
                                                                    style="background-color: #9D9D9D;">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <p>Waiting for admin approval</p>
                                @endif
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
            <a href="{{ route('user_inventory_for_rents') }}" style="text-decoration: none; color:#06283D">
                <- Rent a service?</a>
                    @endif
        </div>
    </div>
</div>
<script>
    function openImage(id,url) {
        $(`#ref-${id}`).show()
        $(`#proof-${id}`).attr('src', url)
    }
    function hide(id) {
        $(`#ref-${id}`).hide()
    }

    
    function extend(id) {
        $(`#form-extends-${id}`).fadeIn(300)
    }
    function cancelExtend(id) {
        $(`#form-extends-${id}`).fadeOut(300)
    }

    function payment(id) {
        $(`#form-payment-${id}`).fadeIn(300)
    }
    function cancelPayment(id) {
        $(`#form-payment-${id}`).fadeOut(300)
    }
</script>
@endsection