<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lucky Fuds | Catering Services - Dashboard</title>
    <link rel="stylesheet" href="{{asset('index.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</head>
<body>
    <main>
        <div class="modal" style="position: fixed;z-index: 1"></div>
        <section class="for-nav">
        {{-- header --}}
            <nav class="for-nav">
                <div class="for-logo">
                    <div class="for-image">
                        <img src={{ asset('assets/logo.jpg') }} width="230" alt="">
                    </div>
                </div>
                <div class="for-link-container">
                    @if (Auth::user()->is_admin)
                        <div class="for-link {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}" id="dashboard">
                            <a href="{{ route('dashboard') }}">Dashboard</a>                                          
                        </div> 
                        <div class="for-link" id="reservation">
                            <a href="" onclick="openReservation(event)">
                                Reservation
                                <svg id="reservation_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="reservation-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'schedule_events' ? 'active' : '' }}">
                                <a href="{{ route('schedule_events') }}">Schedule Events</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'schedule_reservation' ? 'active' : '' }}">
                                <a href="{{ route('schedule_reservation') }}">Schedule Reservation</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'schedule_reports' ? 'active' : '' }}">
                                <a href="{{ route('schedule_reports') }}">Schedule Reports</a>                        
                            </div>
                            
                        </div>   
                        <div class="for-link {{ Route::currentRouteName() === 'inventory' ? 'active' : '' }}" id="inventory">
                            <a href="" onclick="openInventory(event)">
                                Inventory
                                <svg id="inventory_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        {{-- for inventory --}}
                        <div class="for-link-panel active" id="inventory-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_stocks' ? 'active' : '' }}">
                                <a href="{{ route('inventory_stocks') }}">Stocks</a>                        
                            </div>
                        </div>
                        <div class="for-link {{ Route::currentRouteName() === 'rentals' ? 'active' : '' }}" id="rentals">
                            <a href="" onclick="openInventory(event)">
                                Rentals
                                <svg id="rental_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        {{-- for rentals --}}
                        <div class="for-link-panel active" id="rentals-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_for_rents' ? 'active' : '' }}">
                                <a href="{{ route('inventory_for_rents') }}">For Rents</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_rents' ? 'active' : '' }}">
                                <a href="{{ route('inventory_rents') }}">Rent Request</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'extend_request' ? 'active' : '' }}">
                                <a href="{{ route('extend_request') }}">Extension Request</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_approves' ? 'active' : '' }}">
                                <a href="{{ route('inventory_approves') }}">Approves</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_extends' ? 'active' : '' }}">
                                <a href="{{ route('inventory_extends') }}">Extended</a>
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_return' ? 'active' : '' }}">
                                <a href="{{ route('inventory_return') }}">Return</a>
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_reports' ? 'active' : '' }}">
                                <a href="{{ route('inventory_reports') }}">Reports</a>
                            </div>
                        </div>   
                        <div class="for-link {{ Route::currentRouteName() === 'account' ? 'active' : '' }}" id="account">
                            <a href="" onclick="openAccount(event)">
                                Account Manager
                                <svg id="account_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="account-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'account_request' ? 'active' : '' }}">
                                <a href="{{ route('account_request') }}">Request Verification</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'account_verified' ? 'active' : '' }}">
                                <a href="{{ route('account_verified') }}">Verified</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'account_profile' ? 'active' : '' }}">
                                <a href="{{ route('account_profile') }}">Admin Profile</a>                        
                            </div>
                        </div>
                        <div class="for-link {{ Route::currentRouteName() === 'settings' ? 'active' : '' }}" id="settings">
                            <a href="" onclick="openSettings(event)">
                                Settings
                                <svg id="settings_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="settings-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'about_page' ? 'active' : '' }}">
                                <a href="{{ route('about_page') }}">Update about</a>                        
                            </div>
                        </div>
                    @else
                        <div class="for-link {{ Route::currentRouteName() === 'user_dashboard' ? 'active' : '' }}" id="dashboard">
                            <a href="{{ route('user_dashboard') }}">Dashboard</a>                                          
                        </div> 
                        <div class="for-link" id="reservation">
                            <a href="" onclick="openReservation(event)">
                                Reservation
                                <svg id="reservation_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="reservation-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_schedule_events' ? 'active' : '' }}">
                                <a href="{{ route('user_schedule_events') }}">Schedule Events</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_schedule_reservation' ? 'active' : '' }}">
                                <a href="{{ route('user_schedule_reservation') }}">Schedule Reservation</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_schedule_confirmation' ? 'active' : '' }}">
                                <a href="{{ route('user_schedule_confirmation') }}">Confirmation Requests</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_reseravation_summary' ? 'active' : '' }}">
                                <a href="{{ route('user_reseravation_summary') }}">Reservation Summary</a>                        
                            </div>
                            
                        </div>   
                        <div class="for-link {{ Route::currentRouteName() === 'inventory' ? 'active' : '' }}" id="inventory">
                            <a href="" onclick="openInventory(event)">
                                Rentals
                                <svg id="inventory_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="inventory-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_inventory_for_rents' ? 'active' : '' }}">
                                <a href="{{ route('user_inventory_for_rents') }}">For Rents</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_inventory_rents' ? 'active' : '' }}">
                                <a href="{{ route('user_inventory_rents') }}">Rented</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_inventory_extends' ? 'active' : '' }}">
                                <a href="{{ route('user_inventory_extends') }}">Extend Requests</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_inventory_summary' ? 'active' : '' }}">
                                <a href="{{ route('user_inventory_summary') }}">Summary</a>                        
                            </div>
                        </div>
                        {{-- Account --}}
                        <div class="for-link {{ Route::currentRouteName() === 'account' ? 'active' : '' }}" id="account">
                            <a href="" onclick="openAccount(event)">
                                Account Manager
                                <svg id="account_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="for-link-panel active" id="account-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_account_profile' ? 'active' : '' }}">
                                <a href="{{ route('user_account_profile') }}">Profile</a>                        
                            </div>
                        </div>
                    @endif
                    <div class="for-link">
                        <a href="#about" onclick="openAbout(event)">About</a>
                    </div>
                    <div class="for-link">
                        <a href="{{ route('signout') }}">Logout</a>
                    </div>
                </div>
            </nav>
            
        </section>
        @if (Auth::user()->is_admin)
            <section class="for-page">
                @if (Route::currentRouteName() === "dashboard")
                    @yield('dashboard')
                @elseif(Route::currentRouteName() === "schedule_events")
                    @yield('schedule_events')
                @elseif(Route::currentRouteName() === "schedule_reservation")
                    @yield('schedule_reservation')
                @elseif(Route::currentRouteName() === "schedule_reports")
                    @yield('schedule_reports')
                @elseif(Route::currentRouteName() === "inventory_stocks")
                    @yield('inventory_stocks')
                @elseif(Route::currentRouteName() === "inventory_for_rents")
                    @yield('inventory_for_rents')
                @elseif(Route::currentRouteName() === "inventory_rents")
                    @yield('inventory_rents')
                @elseif(Route::currentRouteName() === "inventory_approves")
                    @yield('inventory_approves')
                @elseif(Route::currentRouteName() === "extend_request")
                    @yield('extend_request')
                @elseif(Route::currentRouteName() === "inventory_extends")
                    @yield('inventory_extends')
                @elseif(Route::currentRouteName() === "inventory_return")
                    @yield('inventory_return')
                @elseif(Route::currentRouteName() === "inventory_reports")
                    @yield('inventory_reports')
                @elseif(Route::currentRouteName() === "account_profile")
                    @yield('account_profile')
                @elseif(Route::currentRouteName() === "account_request")
                    @yield('account_request')
                @elseif(Route::currentRouteName() === "account_verified")
                    @yield('account_verified')
                @elseif(Route::currentRouteName() === "about_page")
                    @yield('about_page')
                @endif
            </section>
        @else
            <section class="for-page">
                @if (Route::currentRouteName() === "user_dashboard")
                    @yield('user_dashboard')
                @elseif(Route::currentRouteName() === "user_schedule_events")
                    @yield('user_schedule_events')
                @elseif(Route::currentRouteName() === "user_schedule_reservation")
                    @yield('user_schedule_reservation')
                @elseif(Route::currentRouteName() === "user_schedule_confirmation")
                    @yield('user_schedule_confirmation')
                @elseif(Route::currentRouteName() === "user_reseravation_summary")
                    @yield('user_reseravation_summary')
                @elseif(Route::currentRouteName() === "user_inventory_for_rents")
                    @yield('user_inventory_for_rents')
                @elseif(Route::currentRouteName() === "user_inventory_rents")
                    @yield('inventory_rents')
                @elseif(Route::currentRouteName() === "user_inventory_extends")
                    @yield('user_inventory_extends')
                @elseif(Route::currentRouteName() === "user_inventory_summary")
                    @yield('user_inventory_summary')
                @elseif(Route::currentRouteName() === "user_account_profile")
                    @yield('user_account_profile')
                @elseif(Route::currentRouteName() === "user_online_payment")
                    @yield('user_online_payment')
                @endif
            </section>
        @endif
    </main>
</body>
</html>
<script defer>
    $('#reservation').click(()=> {
        $('#rentals-active').hide()
        $('#inventory-active').hide()
        $('#reservation-active').toggle()
        
        $('#rental_icon').show()
        $('#inventory_icon').show()
        $('#reservation_icon').toggle()
    })
    $('#inventory').click(()=> {
        $('#reservation-active').hide()
        $('#rentals-active').hide()
        $('#inventory-active').toggle()

        $('#rental_icon').show()
        $('#reservation_icon').show()
        $('#inventory_icon').toggle()
    })
    $('#rentals').click(()=> {
        $('#reservation-active').hide()
        $('#inventory-active').hide()
        $('#rentals-active').toggle()

        $('#reservation_icon').show()
        $('#inventory_icon').show()
        $('#rental_icon').toggle()
    })
    $('#account').click(()=> {
        $('#reservation-active').hide()
        $('#inventory-active').hide()
        $('#rentals-active').hide()
        $('#account-active').toggle()

        $('#reservation_icon').show()
        $('#inventory_icon').show()
        $('#rental_icon').show()
        $('#account_icon').toggle()
    })
    $('#settings').click(()=> {
        $('#reservation-active').hide()
        $('#inventory-active').hide()
        $('#rentals-active').hide()
        $('#account-active').hide()
        $('#settings-active').toggle()

        $('#reservation_icon').show()
        $('#inventory_icon').show()
        $('#rental_icon').show()
        $('#account_icon').show()
        $('#settings_icon').toggle()
    })
    function openInventory(e) {
        e.preventDefault();
    }
    function openReservation(e) {
        e.preventDefault();
    }
    function openAccount(e) {
        e.preventDefault();
    }
    function openSettings(e) {
        e.preventDefault();
    }
    setTimeout(() => {
       $('.error-message').fadeOut() 
    }, 3000);
    function openAbout(e) {
        e.preventDefault()
        $.ajax({
            type: "get",
            url: "/about",
            success: function (response) {
                $('.modal').css({
                    display: 'block'
                })
                $('.modal').html(response)
            }
        });
    }
    function closeAbout() {
        $('.modal').css({
            display: 'none'
        })
    }

</script>