<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('index.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <title>Login</title>
</head>

<body>
    <div class="modal"></div>
    <div class="error-message">
        @foreach ($errors->all() as $error)
            <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
        @endforeach
    </div>
    <div class="for-login">
        <div class="for-form">
            <form action="{{ route('signin') }}" class="on-form" method="POST" onsubmit="submitForm(event)">
                @csrf
                <h2>LOGIN TO SYSTEM</h2>

                <div class="for-group-input" @error('message') style="border: 2px solid #EB1D36" @enderror>
                    <input type="email" name="email" class="for-input" placeholder="Email" value="{{old('email')}}" id="email">
                </div>
                <div class="for-group-input" @error('message') style="border: 2px solid #EB1D36" @enderror>
                    <input type="password" name="password" class="for-input" placeholder="Password"
                        value="{{ old('password') }}" id="password">
                </div>
                <div class="for-group-input remember">
                    <input type="checkbox" class="for-input" name="remember" id="remember">
                    <label for="checkbox">Remember</label>
                </div>
                <a style="padding: 10px;color:#1A1A1A" href="{{ route('register') }}">Register</a>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-terms" id="agree-term">
                    <span><small>I agree to the</small><b>&nbsp;<a href="#"
                                style="text-decoration: none; color: #000000" onclick="openTerms(event)">Terms and
                                Conditions</a></b></span>
                </div>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-policy" id="agree-policy">
                    <span><small>I agree to the</small><b>&nbsp;<a href="#"
                                style="text-decoration: none; color: #000000" onclick="openPolicy(event)">Data Privacy
                                Policy</a></b></span>
                </div>
                <div class="for-group-input submit">
                    <input type="submit" class="for-input" value="Sign in">
                </div>
            </form>
        </div>
    </div>

</body>
<script>
    function openPolicy(e) {
        e.preventDefault()
        $.ajax({
            type: "get",
            url: "/policy",
            success: function (response) {
                $('.modal').css({
                    display: 'block'
                })
                $('.modal').html(response)
            }
        });
    }
    function closePolicy() {
        $('.modal').css({
            display: 'none'
        })
    }

    function openTerms(e) {
        e.preventDefault()
        $.ajax({
            type: "get",
            url: "/terms",
            success: function (response) {
                $('.modal').css({
                    display: 'block'
                })
                $('.modal').html(response)
            }
        });
    }
    function closeTerm() {
        $('.modal').css({
            display: 'none'
        })
    }
    setTimeout(() => {
       $('.error-message').fadeOut() 
    }, 3000);

</script>

</html>