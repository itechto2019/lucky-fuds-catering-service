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
                <div style="padding: 10px; margin:5px; border-radius: 5px; background-color: #FF6464; color: #1a1a1a1">{{$error}}</div>
            @endforeach
        </div>
        @if(session()->has('message'))
            <div style="padding: 15px; margin:5px; border-radius: 5px; background-color: #38E54D; color: #1a1a1a1">{{ session()->get('message') }}</div>
        @endif
        @if(Auth::user()->validate && Auth::user()->validate->status == false)
            <div style="padding: 15px; margin:5px; border-radius: 5px; background-color: #FF6464; color: #1a1a1a1">Verification is on process</div>
        @endif
        @if (!Auth::user()->validate && Auth::user()->info)
            <form action="{{ route('user_validate_update') }}" method="POST" enctype="multipart/form-data" style="display: flex;align-items:center; width: 300px">
                @csrf
                @method('PATCH')
                <div class="id-image">
                    <div class="provider" style="padding: 10px">
                        <h3>Please provide your valid id to verify your identity. <br>See the example below.</h3>
                    </div>
                    <div class="image-container" style="padding: 10px">
                        <img src="https://filipinotimes.net/wp-content/uploads/2018/08/Screen-Shot-2018-08-06-at-5.33.22-PM-1.png" alt="failed to load image" style="width: 350px; height: 200px;object-fit: cover;background-repeat:no-repeat;" >
                    </div>
                    <div class="input-group" style="border:2px solid #5FD068;position: relative;">
                        <input type="file" accept="image/png,image/jpeg" name="image" style="border-style:none" required>
                        <span style="position: absolute; bottom:0%;font-size: 14px;color: #1a1a1a;padding: 0px 10px;">Max: 5mb</span>
                    </div>
                    
                    <div class="input-group submit" style="display: block">
                        <div>
                            <button type="submit" style="width: 100%">Upload</button>
                        </div>
                    </div>
                </div>
                
            </form>
        @endif
        <hr>
        <form action="{{ route('user_profile_update') }}" method="POST" enctype="multipart/form-data">
            @if (!Auth::user()->info)
                <div class="">
                    <div style="padding: 15px; margin:5px; background-color: #F7A76C; color: #1a1a1a1">Please note, you can set your other information once, please double check your info.</div>
                </div>
            @endif
            @csrf
            @method('PATCH')
            <div class="form-separate" style="display: block">
                <div class="image-container" style="padding: 10px">
                    <img src="{{Auth::user()->info && Auth::user()->info->profile ? Auth::user()->info->profile : "https://storage.googleapis.com/lucky-fuds-catering-service.appspot.com/profile/user.png?GoogleAccessId=firebase-adminsdk-vjtb9%40lucky-fuds-catering-service.iam.gserviceaccount.com&Expires=4824937523&Signature=TDcSRfCsg89ELHHvb%2Bvna5LlfBt6AXFzu1vEl62CVFAapmVvYeK1DAr%2BK0rIuArNBs20gH1R3RL8Te2K0EYwoIN2AZmjXOqXLMsDfkxGLo87MyYcvWLKRhw6EeYw0HzL3AyhB%2Fo1ejpcfaUWZM%2BebZeRFiGsuyv0sCfSOoOLnue%2BOoX8qFmX8KJu4yC%2Bosi7FeHnc1WgiN5ZVKYkWEi3HydEfr%2B1C435cm7NJEG7qW77kJljDYSb6ZmfzsAQ0kKedGZG4SkP2oCedxyMBVcUcO24GQy0AEMuyAcnA9AYyF9Ygef%2B1k6H3uArqkWwK8mQQsgC61LcJXDdt8yga4v3nA%3D%3D" }}" alt="failed to load image" style="width: 200px; height: 200px;object-fit: cover;background-repeat:no-repeat; border-radius: 50%" >
                </div>
                @if (Auth::user()->validate && Auth::user()->validate->status == 1)
                    <div class="provider" style="padding: 10px; display: flex; align-items: center;">
                        <svg style="width: 40px; height: 40px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>                  
                        <h3>Verified</h3>
                    </div>
                @endif
                <div class="form-page">
                    <div class="input-group" style="border:2px solid #5FD068;position: relative;">
                        <input type="file" accept="image/png,image/jpeg" name="profile" placeholder="Name" style="border-style:none" required>
                        <span style="position: absolute; bottom:0%;font-size: 14px;color: #1a1a1a;padding: 0px 10px;">Max: 5mb</span>
                    </div>
                    @if (!Auth::user()->info && !Auth::user()->info?->name)
                        <div class="input-group">
                            <input type="text" name="name" id="" placeholder="Name" value="{{ Auth::user()->info ? substr(Auth::user()->info->name, 0, 8) . '***' : old('name') }}" required>
                        </div>
                    @else
                        <div class="input-group">
                            <b>Name: </b>
                            <p>{{Auth::user()->info ? substr(Auth::user()->info->name, 0, 8) . '***' : old('name')}}</p>
                        </div>
                    @endif
                    @if (!Auth::user()->info && !Auth::user()->info?->contact)
                        <div class="input-group">
                            <input type="text" name="contact" id="" placeholder="Contact Number" required>
                        </div>
                    @else
                        <div class="input-group">
                            <b>Contact: </b>
                            <p>{{ Auth::user()->info ? substr(Auth::user()->info->contact, 0, 0) . '***' : old('contact') }}</p>
                        </div>
                    @endif
                    
                    <div class="input-group">
                        <input type="date" name="birthday" id="" value="{{ Auth::user()->info ? Auth::user()->info->birthday : old('birthday') }}" max={{now()}} required>
                    </div>
                    <div class="input-group">
                        <b>Email: </b>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                    <div class="input-group" style="display: flex;justify-content:center">
                        <label for="">Preferred Contact Method:&nbsp;</label>
                        <div style="display: flex;align-items:center; margin: 0px 5px">
                            <input type="radio" name="method" id="email" value="email" required @checked(Auth::user()->info && Auth::user()->info->method == "email" ? true : false)>
                            <label for="email">Email Address</label>
                        </div>
                        <div style="display: flex;align-items:center;  margin: 0px 5px">
                            <input type="radio" name="method" id="contact" value="contact" required @checked(Auth::user()->info && Auth::user()->info->method == "contact" ? true : false)>
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