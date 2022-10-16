@extends('index')
@section('user_home')
<div class="for-page-title">
    <h1>Home</h1>
    <div style="padding: 5px"><small>Welcome {{ Auth::user()->name }}</small></div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <div class="home-control-body">
        <div class="input-group a">
            <a type="button" href="{{ route('user_schedule_reservation') }}">Reservation</a>
        </div>
        <div class="input-group a">
            <a type="button" href="{{ route('user_inventory_for_rents') }}">Rent</a>
        </div>
    </div>
</div>
</div>
@endsection