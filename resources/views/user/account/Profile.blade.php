@extends('index')
@section('user_account_profile')
    <div class="for-page-title" style="user-select: none">
        <h1>Profile</h1>
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
                    <img src="{{Auth::user()->info ? asset('/asset/profile/'. Auth::user()->info->profile ) : asset('profile/user.png')}}" alt="failed to load image" style="width: 200px; height: 200px;object-fit: cover;background-repeat:no-repeat; border-radius: 50%" >
                </div>
                <div class="form-page">
                    <div class="input-group" style="border:2px solid #5FD068;">
                        <input type="file" accept="image/png,image/jpeg" name="profile" placeholder="Name" style="border-style:none" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" id="" placeholder="Name" value="{{ Auth::user()->info ? Auth::user()->info->name : old('name') }}" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="contact" id="" placeholder="Contact Number" value="{{ Auth::user()->info ? Auth::user()->info->contact : old('contact') }}" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" id="" placeholder="Email Address" value="{{ Auth::user()->info ? Auth::user()->info->email : old('email') }}" required>
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