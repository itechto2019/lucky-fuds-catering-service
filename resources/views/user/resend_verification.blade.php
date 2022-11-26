<title>Email Confirmation</title>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #1A1A1A;
        color: #FFFFFF
    }
    .email-box {
        padding: 10px;
        display: grid;
        place-content: center;
        place-items: center
    }

</style>

<div class="email-box" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
    <div class="email-info">
        <img src="{{asset('assets/logo.jpg')}}" alt="Logo" border="0" width="400" style="display: block; width: 400px;">
        <h3>Confirmation link has been sent to your email.</h3>
        <p>{{$user->email}}</p>
        <p>If link doesn't work, click the resend button. <a href="{{route('verify_now', ['token' => $verifyToken])}}" style="color: #F7F7F7; font-weigh: bold;">Resend</a></p>
    </div>
</div>