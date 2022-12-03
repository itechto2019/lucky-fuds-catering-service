@extends('index')
@section('inventory_approves')
<div class="for-inventory-return">
    <div class="for-page-title">
        <h1>Approves</h1>
    </div>
    <div class="table-reservation">
        <div class="error-message">
            @foreach ($errors->all() as $error)
            <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
            @endforeach
        </div>
        @if(session()->has('message'))
        <div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">{{ session()->get('message')
            }}</div>
        @endif
        @if(session()->has('decline'))
        <div style="padding: 15px; margin:5px; background-color: #F7A76C; color: #1a1a1a1">{{ session()->get('decline')
            }}</div>
        @endif
        <div class="table-form">
            @if (!$rents->isEmpty())

            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->id }}</td>
                    <td><img src="{{ $rent->stock->image }}"></td>
                    <td>{{ $rent->stock->item}}</td>
                    <td style="width: 40%">
                        <p><b>Client:</b> {{ $rent->info->name }}</p>
                        <p><b>Method:</b> {{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</p>
                        <p><b>Mode of Payment:</b> {{ $rent->transaction->payment_method == 0 ? "Cash Payment" : "Online Payment"}} / {{ $rent->transaction->extend_online_transaction ? "Online Payment" : "Cash Payment"}}</p>
                        <p><b>Address:</b> {{ $rent->address }}</p>
                        <p><b>Date for use:</b> {{ $rent->extends ? $rent->extends->date : $rent->date }}</p>
                        <p><b>Date for return:</b> {{ $rent->extends ? $rent->extends->return : $rent->return }}</p>
                        <p><b>Amount: ₱</b>{{ $rent->amount }}</p>
                        <p><b>Quantity:</b> {{ $rent->quantity }}</p>
                    </td>
                    <td>{{$rent->status}}</td>
                    <td>
                        <div class="action-form">
                            <div class="action-button">
                                @if ($rent->status === "approved" && $rent?->rent_approve)
                                    @if($rent->transaction->online_transaction && $rent->transaction->online_transaction->payment_status)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <h3>Proof Of Payment</h3>
                                            <p><b>Reference number:</b><br>{{$rent->transaction->online_transaction->reference}}
                                            </p>
                                            <img src="{{$rent->transaction->online_transaction->image}}"
                                                style="width: 200px; cursor: pointer;"
                                                onclick="openImage({{$rent->id}},'{{$rent->transaction->online_transaction->image}}')">
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
                                        </div>
                                        
                                    @else
                                        @if ($rent->transaction->online_transaction && !$rent->transaction->online_transaction->payment_status)
                                            <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                                <h3>Proof Of Payment</h3>
                                                <p><b>Reference number:</b><br>{{$rent->transaction->online_transaction->reference}}
                                                </p>
                                                <img src="{{$rent->transaction->online_transaction->image}}"
                                                    style="width: 200px; cursor: pointer;"
                                                    onclick="openImage({{$rent->id}},'{{$rent->transaction->online_transaction->image}}')">
                                                    <form action="{{ route('accept_payment', $rent->id) }}" method="POST">
                                                        @csrf
                                                        @method('patch')
                                                        <button class="action-print">
                                                            Receive
                                                        </button>
                                                    </form>
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
                                        @endif
                                    @endif
                                @elseif($rent->status === "extended" && $rent?->extend_approve)
                                    @if($rent->extend_online_transaction && $rent->extend_online_transaction->payment_status)
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <h3>Proof Of Payment</h3>
                                            <p><b>Reference number:</b><br>{{$rent->extend_online_transaction->reference}}
                                            </p>
                                            <img src="{{$rent->extend_online_transaction->image}}"
                                                style="width: 200px; cursor: pointer;"
                                                onclick="openImage({{$rent->id}},'{{$rent->extend_online_transaction->image}}')">
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
                                        </div>
                                    @elseif ($rent->transaction->online_transaction && $rent->extend_online_transaction && !$rent->extend_online_transaction->payment_status)
                                        @if (!$rent->transaction->extend_online_transaction->payment_status && !$rent->transaction->extend_online_transaction->image)
                                            <p style="text-align: center">You've approve this extension request, wait for the proof of payment</p>
                                        @else
                                        <div style="display: grid;place-content: center;place-items:center; gap: 10px;">
                                            <h3>Proof Of Payment</h3>
                                            <p><b>Reference numberss:</b><br>{{$rent->extend_online_transaction->reference}}
                                            </p>
                                            <img src="{{$rent->extend_online_transaction->image}}"
                                                style="width: 200px; cursor: pointer;"
                                                onclick="openImage({{$rent->id}},'{{$rent->extend_online_transaction->image}}')">
                                                <form action="{{ route('accept_payment', $rent->id) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <button class="action-print">
                                                        Receive
                                                    </button>
                                                </form>
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
                                        @endif
                                    @else
                                        <p style="text-align: center">You've approve this extension.</p>
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
                                    @endif
                                @else
                                    @if($rent?->return)
                                        <p>You've returned the item</p>
                                    @else
                                        <p>You've approved this rent.</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                    {{-- @if ($rent->status == 'approved' && $rent->transaction->online_transaction)
                        @if (!$rent->transaction->online_transaction->payment_status && $rent->transaction->online_transaction->image)
                            <td>
                                <div style="display: grid; place-content: center; place-items:center;gap: 10px">
                                    <h3>Proof of payment</h3>
                                    <p><b>Reference number:</b><br>{{$rent->transaction->online_transaction->reference}}</p>
                                    <img src="{{$rent->transaction->online_transaction->image}}" alt="" style="width: 200px;cursor: pointer;" onclick="openImage({{$rent->id}},'{{$rent->transaction->online_transaction->image}}')">
                                    <div class="action-form">
                                        <div class="action-button">
                                            <form action="{{ route('accept_payment', $rent->id) }}" method="POST">
                                                @csrf
                                                @method('patch')
                                                <button class="action-print">
                                                    Receive
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="show-ref" id="ref-{{$rent->id}}" style="display: none">
                                        <svg  onclick="hide('{{$rent->id}}')" style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>                
                                        <img src="" id="proof-{{$rent->id}}">
                                    </div>
                                </div>
                            </td>
                        @elseif(!$rent->transaction->online_transaction->payment_status && !$rent->transaction->online_transaction->image)
                            <td>You've approved this rent request, wait for the proof of payment</td>
                        @else
                            <td>
                                <div style="display: grid; place-content: center; place-items:center;gap: 10px">
                                    <h3>Proof of payment</h3>
                                    <p><b>Reference number:</b><br>{{$rent->transaction->online_transaction->reference}}</p>
                                    <img src="{{$rent->transaction->online_transaction->image}}" alt="" style="width: 200px;cursor: pointer;" onclick="openImage({{$rent->id}},'{{$rent->transaction->online_transaction->image}}')">
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
                                    <div class="show-ref" id="ref-{{$rent->id}}" style="display: none">
                                        <svg  onclick="hide('{{$rent->id}}')" style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>                
                                        <img src="" id="proof-{{$rent->id}}">
                                    </div>
                                </div>
                            </td>
                        @endif
                    @else
                        <td>
                            @if ($rent->status == "returned")
                                item is returned
                            @elseif (!$rent->extends && !$rent->rent_approve)
                                item is declined
                            @else
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
                            @endif
                        </td>
                    @endif --}}
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
<script>
    function openImage(id,url) {
        $(`#ref-${id}`).show()
        $(`#proof-${id}`).attr('src', url)
    }
    function hide(id) {
        $(`#ref-${id}`).hide()
    }
</script>
@endsection