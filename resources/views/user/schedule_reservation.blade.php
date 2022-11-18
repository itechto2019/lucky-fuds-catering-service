@extends('index')
@section('user_schedule_reservation')
<div class="for-schedule-reservation">
    <div class="for-page-title">
        <h1>Reservation</h1>
    </div>
    
    <div class="reserve">
        <div class="reserve form">
            <h3>Reservation Details</h3>
            <div class="error">
                @foreach ($errors->all() as $error)
                <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #1a1a1a1">{{$error}}</div>
                @endforeach
            </div>
            <form action="{{ route('user_create_event') }}" method="POST">
                @csrf
                <div class="form-separate">
                    <div class="form-page">
                        <div class="input-group">
                            <input type="text" name="client" id="" placeholder="Name" value="{{ Auth::user()->name }}" required disabled>
                        </div>
                        <div class="input-group">
                            <input type="text" name="contact" id="" placeholder="Contact Number" value="{{ old('contact') }}" required>
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" id="" placeholder="Email Address" value="{{ old('email') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="">Preferred Contact Method:&nbsp;</label>
                            <div style="display: flex;align-items:center; margin: 0px 5px">
                                <input type="radio" name="method" id="email" value="email" required>
                                <label for="email">Email Address</label>
                            </div>
                            <div style="display: flex;align-items:center;  margin: 0px 5px">
                                <input type="radio" name="method" id="contact" value="contact" required>
                                <label for="contact">Contact Number</label>
                            </div>
                        </div>
                        <div class="package-description">
                            <h3 style="padding: 0px 10px;">Package List</h3>
                            @foreach ($packages as $package)
                                <div class="package-info">
                                    <p><b>{{ $package->name }}</b></p>
                                    <p>{{ $package->details }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-page">
                        <div class="input-group">
                            <label for="">Date of Event:&nbsp;</label>
                            <input type="date" name="date" id="" placeholder="Date of event" value="{{ old('date') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="">Start time:&nbsp;</label>
                            <input type="time" name="time" id="" value="{{ old('time') }}" required>
                        </div>
                        <div class="input-group">
                            {{-- <label for="">Address of Event</label> --}}
                            <input type="text" name="address" placeholder="Address of event" value="{{ old('address') }}" required>
                        </div>
                        <div class="input-group">
                            {{-- <label for="">Number of Guest: </label> --}}
                            <input type="number" name="guest" placeholder="Number of guest" value="{{ old('guest') }}" required>
                        </div>
                        <div class="input-group">
                            {{-- <label for="">Event Type: </label> --}}
                            <input type="text" name="event" id="" placeholder="Event type" value="{{ old('event') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="">Package:&nbsp;</label>
                            <select name="package_id" id="package">
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group submit">
                            <div>
                                <button type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    setTimeout(() => {
       $('.error').css({
            display: 'none'
       })
    }, 3000);
</script>