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
    <title>Register</title>
</head>
<body>
    <div class="for-register">
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
            @endforeach
        </div>
        <div class="for-form">
            <form action="{{ route('signup') }}" class="on-form" method="POST">
                @csrf                    
                <h2>REGISTER TO SYSTEM</h2>
                <div class="for-group-input" 
                    @error('username') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="email" name="email" class="for-input" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="for-group-input"
                    @error('password') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="password" name="password" class="for-input" placeholder="Password">
                </div>
                <div class="for-group-input"
                    @error('password_confirmation') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="password" name="password_confirmation" class="for-input" placeholder="Confirm Password">
                </div>
                <a style="padding: 10px;color:#1A1A1A" href="{{ url('login') }}">Already have an account?</a>
                <div class="for-group-input submit">
                    <input type="submit" class="for-input" value="Sign up">
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    setTimeout(() => {
       $('.error-message').fadeOut() 
    }, 5000);
</script>
</html>
