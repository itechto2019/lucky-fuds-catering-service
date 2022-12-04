@extends('index')
@section('about_page')
<div class="for-page-title">
    <h1>Update about information</h1>
</div>
<div style="padding: 10px">
    <h2>Create about</h2>
</div>
@if(session()->has('message'))
<div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">{{ session()->get('message')
    }}</div>
@endif
<form action="{{route('create_about')}}" method="POST">
    @csrf
    <div class="input-group">
        <textarea name="body" cols="20" rows="10" placeholder="Body"
            style="padding: 10px;width: 100%;margin: 10px;resize:none" required>{{Auth::user()->about->body ?? 'ss'}}</textarea>
    </div>
    <div class="input-group">
        <button type="submit">Save</button>
    </div>
</form>

@endsection