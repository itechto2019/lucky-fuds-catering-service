<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('index.css') }}">
    <title>Login</title>
</head>

<body>
    <div class="for-login">
        <div class="for-form">
            <form action="{{ route('signin') }}" class="on-form" method="POST">
                @csrf
                <h2>LOGIN TO SYSTEM</h2>
                @foreach ($errors->all() as $error)
                    <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
                @endforeach
                {{-- @error('message')
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$message}}</div>
                @enderror
                @error('terms')
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$terms}}</div>
                @enderror
                @error('policy')
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$policy}}</div>
                @enderror --}}
                <div class="for-group-input" @error('message') style="border: 2px solid #EB1D36" @enderror>
                    <input type="email" name="email" class="for-input" placeholder="Email" value="{{old('email')}}">
                </div>
                <div class="for-group-input" @error('message') style="border: 2px solid #EB1D36" @enderror>
                    <input type="password" name="password" class="for-input" placeholder="Password"
                        value="{{ old('password') }}">
                </div>
                <div class="for-group-input remember">
                    <input type="checkbox" class="for-input" name="remember">
                    <label for="checkbox">Remember</label>
                </div>
                <a style="padding: 10px;color:#1A1A1A" href="{{ route('register') }}">Register</a>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-terms">
                    <span><small>I aggree to the</small><b>&nbsp;Terms and Conditions</b></span>
                </div>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-policy">
                    <span><small>I aggree to the</small><b>&nbsp;Data Privacy Policy</b></span>
                </div>
                <div class="for-group-input submit">
                    <input type="submit" class="for-input" value="Sign in">
                </div>
            </form>
        </div>
    </div>
</body>

</html>