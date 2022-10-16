@extends('index')
@section('user_home')
<div class="for-page-title">
    <h1>Home</h1>
    <div style="padding: 5px"><small>Welcome {{ Auth::user()->name }}</small></div>
</div>
<div class="for-dashboard-data" style="position: relative">
    <div class="home-control-body">
        <div class="input-group">
            <button type="button" onclick="{{ route('user_home') }}">Reservation</button>
        </div>
    </div>
</div>
</div>
@endsection