@extends('index')
@section('user_account_profile')
    <div class="for-page-title" style="user-select: none">
        <h1>Profile account</h1>
    </div>
    <div class="reserve form">
        <div class="title-container" style="padding: 10px">
            <h3>Update Profile</h3>
        </div>
        <div class="error">
            @foreach ($errors->all() as $error)
            <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #1a1a1a1">{{$error}}</div>
            @endforeach
        </div>
        <form action="{{ route('user_profile_update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-separate" style="display: block">
                <div class="image-container" style="padding: 10px">
                    <img src="{{Auth::user()->info && Auth::user()->info->profile ? Auth::user()->info->profile : "https://storage.googleapis.com/lucky-fuds-catering-service.appspot.com/profile/user.png?GoogleAccessId=firebase-adminsdk-vjtb9%40lucky-fuds-catering-service.iam.gserviceaccount.com&Expires=4824937523&Signature=TDcSRfCsg89ELHHvb%2Bvna5LlfBt6AXFzu1vEl62CVFAapmVvYeK1DAr%2BK0rIuArNBs20gH1R3RL8Te2K0EYwoIN2AZmjXOqXLMsDfkxGLo87MyYcvWLKRhw6EeYw0HzL3AyhB%2Fo1ejpcfaUWZM%2BebZeRFiGsuyv0sCfSOoOLnue%2BOoX8qFmX8KJu4yC%2Bosi7FeHnc1WgiN5ZVKYkWEi3HydEfr%2B1C435cm7NJEG7qW77kJljDYSb6ZmfzsAQ0kKedGZG4SkP2oCedxyMBVcUcO24GQy0AEMuyAcnA9AYyF9Ygef%2B1k6H3uArqkWwK8mQQsgC61LcJXDdt8yga4v3nA%3D%3D" }}" alt="failed to load image" style="width: 200px; height: 200px;object-fit: cover;background-repeat:no-repeat; border-radius: 50%" >
                </div>
                <div class="form-page">
                    <div class="input-group" style="border:2px solid #5FD068;">
                        <input type="file" accept="image/png,image/jpeg" name="profile" placeholder="Name" style="border-style:none" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" id="" placeholder="Name" value="{{ Auth::user()->info ? substr(Auth::user()->info->name, 0, 8) . '***' : old('name') }}" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="contact" id="" placeholder="Contact Number" value="{{ Auth::user()->info ? substr(Auth::user()->info->contact, 0, 0) . '***' : old('contact') }}" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" id="" placeholder="Email Address" value="{{ Auth::user()->info ? substr(Auth::user()->info->email, 0, 5) . '***' : old('email') }}" required>
                    </div>
                    <div class="input-group" style="display: flex;justify-content:center">
                        <label for="">Preferred Contact Method:&nbsp;</label>
                        <div style="display: flex;align-items:center; margin: 0px 5px">
                            <input type="radio" name="method" id="email" value="email" required checked={{Auth::user()->info && Auth::user()->info->method == "email" ? true : false}}>
                            <label for="email">Email Address</label>
                        </div>
                        <div style="display: flex;align-items:center;  margin: 0px 5px">
                            <input type="radio" name="method" id="contact" value="contact" required checked={{Auth::user()->info && Auth::user()->info->method == "contact" ? true : false}}>
                            <label for="contact">Contact Number</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="text" name="address" placeholder="Address" value="{{ Auth::user()->info ? Auth::user()->info->address : old('address') }}" required>
                    </div>
                </div>
                <div class="form-page">
                    <div class="input-group submit" style="display: block">
                        <div>
                            <button type="submit" style="width: 100%">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
@endsection
<script>
    setTimeout(() => {
       $('.error').css({
            display: 'none'
       })
    }, 3000);
</script>