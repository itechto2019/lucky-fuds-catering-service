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
                    <input type="email" name="email" class="for-input" placeholder="Email" value="{{old('email')}}"
                        id="email">
                </div>
                <div class="for-group-input" @error('message') style="border: 2px solid #EB1D36; display: flex; align-items:center" @enderror>
                    <input type="password" name="password" class="for-input" placeholder="Password"
                        value="{{ old('password') }}" id="password">
                    <div class="toggle-password" id="toggle-password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" onclick="togglePassword()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </div>
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
    var toggle = false
    function togglePassword() {
        if(!toggle) {
            $('#toggle-password').html('<svg onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>')
            toggle = !toggle
            $('#password').attr('type', 'text')
        }else {
            $('#toggle-password').html(`
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" onclick="togglePassword()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
            `)
            $('#password').attr('type', 'password')
            toggle = !toggle
        }
    }
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