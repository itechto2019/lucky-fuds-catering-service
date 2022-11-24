@extends('index')
@section('dashboard')
<div class="for-page-title">
    <h1>Dashboard</h1>
    <div style="padding: 5px"><small>Welcome {{ Auth::user()->info  ? Auth::user()->info->name : "User" }}</small></div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <h1>Reservation Requests</h1>
    <div class="card-body">
        <div class="cardbox">
            <h3>Confirmed</h3>
            <span>
                <div class="notif">
                    {{ $approved }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Declined</h3>
            <span>
                <div class="notif">
                    {{ $declined }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Pending</h3>
            <span>
                <div class="notif">
                    {{ $pending }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Total Request</h3>
            <span>
                <div class="notif">
                    {{ $request }}
                </div>
            </span>
        </div>
    </div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <h1>Rent Requests</h1>
    <div class="card-body">
        <div class="cardbox">
            <h3>Confirmed</h3>
            <span>
                <div class="notif">
                    {{ $confirmedRent }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Declined</h3>
            <span>
                <div class="notif">
                    {{ $declinedRent }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Pending</h3>
            <span>
                <div class="notif">
                    {{ $pendingRent }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Total Request</h3>
            <span>
                <div class="notif">
                    {{ $totalRequest }}
                </div>
            </span>
        </div>
    </div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <h1>Extend Requests</h1>
    <div class="card-body">
        <div class="cardbox">
            <h3>Confirmed</h3>
            <span>
                <div class="notif">
                    {{ $confirmedExtend }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Declined</h3>
            <span>
                <div class="notif">
                    {{ $declinedExtend }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Pending</h3>
            <span>
                <div class="notif">
                    {{ $pendingExtend }}
                </div>
            </span>
        </div>
        <div class="cardbox">
            <h3>Total Request</h3>
            <span>
                <div class="notif">
                    {{ $totalRequestExtend }}
                </div>
            </span>
        </div>
    </div>
</div>
<div class="for-event-dates">
    <div class="event-dates">
        <h1>Event Dates</h1>
    </div>
    <div style="padding: 15px; display: flex; gap: 10px;">
        @foreach ($months as $month)
            <div class="month-selection"  onclick="getDateEvent('{{$month}}')" style="{{ !$selectedMonth && date('M') == date('M', strtotime($month)) ? "background-color:#FA8281;" : (date('M', strtotime($selectedMonth)) == $month ? "background-color:#FA8281;" : "") }}">
                <h3>{{ $month }}</h3>
            </div>
        @endforeach
    </div>
    <div class="events">
        <div class="dates">
            @if(!$reserves->isEmpty())
                @for($i = 0; $i <= $noOfDays; $i++)
                    <div class="@foreach ($reserves as $reserve)
                        {{ date('j', strtotime($reserve->date)) - 1 == $i? 'date active' : 'date'}}
                    @endforeach">
                        <div>{{ Carbon\Carbon::now()->days($i + 1)->format('D') }}</div>
                        <div>{{ Carbon\Carbon::now()->days($i + 1)->format('j')}}</div>
                    </div>
                @endfor
            @endif
        </div>
        <div class="event-items"  style="height: 100px">
            <div class="event-card-body">
                @if (!$reserves->isEmpty())
                    @foreach ($reserves as $event)
                        <div class="event-item" style="background-color: #FFFFFF;border: 1px solid #D0D0D0D;border-radius: 5px">
                            <h3>{{ date('D', strtotime($event->date)) }}</h3>
                            <div>
                                {{ $event->event }}
                            </div>
                            <div>
                                <small>{{ $event->date }}</small>
                            </div>
                            <hr>
                        </div>
                        <br>
                    @endforeach
                @else
                    <div class="event-item">
                        <b>No upcoming events</b>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="products-container">
    <div class="product-title" style="padding: 10px">
        <h1>Products</h1>
    </div>
    <div class="products-list">
        @foreach ($products as $product)
            <div class="product-box">
                <img src="{{$product->image}}" alt="">
                <div class="product-info">
                    <span>₱{{$product->price}}</span>
                    <p>{{strlen($product->item) > 50 ? substr($product->item, 0, 50) . '...': $product->item}}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    function getDateEvent(eventMonth) {
        window.location.href = `?month=${eventMonth}`
    }
</script>
@endsection