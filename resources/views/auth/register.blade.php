<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('index.css') }}">
    <title>Register</title>
</head>
<body>
    <div class="for-register">
        <div class="for-form">
            <form action="{{ route('signup') }}" class="on-form" method="POST">
                @csrf                    
                <h2>REGISTER TO SYSTEM</h2>
                <div class="for-group-input" 
                    @error('email') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="email" name="email" class="for-input" placeholder="Email" value="{{ old('email') }}">
                    @error('email') 
                        <span>{{ $message }}</span>
                    @enderror
                </div>
                <div class="for-group-input"
                    @error('name') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="text" name="name" class="for-input" placeholder="Username" value="{{ old('name') }}">
                    @error('name') 
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
                <div class="for-group-input"
                    @error('password_confirmation') 
                        style="border: 2px solid #EB1D36"
                    @enderror>
                    <input type="password" name="password_confirmation" class="for-input" placeholder="Confirm Password">
                    @error('password_confirmation') 
                        <span>{{ $message }}</span>
                    @enderror
                </div>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-terms">
                    <span><small>I aggree to the</small><b>&nbsp;Terms and Conditions</b></span>
                </div>
                <div style="display: flex; align-items: center;padding: 5px 10px; gap: 5px;">
                    <input type="checkbox" name="agree-policy">
                    <span><small>I aggree to the</small><b>&nbsp;Data Privacy Policy</b></span>
                </div>
                <a style="padding: 10px;color:#1A1A1A" href="{{ url('login') }}">Already have an account?</a>
                <div class="for-group-input submit">
                    <input type="submit" class="for-input" value="Sign up">
                </div>
    
            </form>
        </div>
    </div>
</body>
</html>
