@extends('auth.app')
<div class="for-login">
    <div class="for-form">
        <form action="{{ route('signin') }}" class="on-form" method="POST">
            @csrf
            <h2>LOGIN TO SYSTEM</h2>
            <div class="for-group-input"                        
                @error('email') 
                    style="border: 2px solid #EB1D36"
                @enderror>
                <input type="email" name="email" class="for-input" placeholder="Email">
                @error('email') 
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="for-group-input"
                @error('password') 
                    style="border: 2px solid #EB1D36"
                @enderror>            
                <input type="password" name="password" class="for-input" placeholder="Password">
                @error('password') 
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="for-group-input remember">
                <input type="checkbox" class="for-input" name="remember">
                <label for="checkbox">Remember</label>
            </div>
            <a style="padding: 10px;color:#1A1A1A" href="{{ url('register') }}">Register</a>
            <div class="for-group-input submit">
                <input type="submit" class="for-input" value="Sign in">
            </div>
        </form>
    </div>
</div>