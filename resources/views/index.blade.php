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
        <section class="for-nav">
            {{-- header --}}
            @if (Auth::user()->is_admin)
                <nav class="for-navbar">
                    <div class="for-logo">
                        <div class="for-image">
                            <img src={{ asset('assets/logo.jpg') }} width="250" alt="">
                        </div>
                    </div>
                    <div class="for-link-container">
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
                        <div class="for-link-panel active" id="inventory-active">
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_stocks' ? 'active' : '' }}">
                                <a href="{{ route('inventory_stocks') }}">Stocks</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_for_rents' ? 'active' : '' }}">
                                <a href="{{ route('inventory_for_rents') }}">For Rents</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_rents' ? 'active' : '' }}">
                                <a href="{{ route('inventory_rents') }}">Rented</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_approves' ? 'active' : '' }}">
                                <a href="{{ route('inventory_approves') }}">Approves</a>                        
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_extends' ? 'active' : '' }}">
                                <a href="{{ route('inventory_extends') }}">Extends</a>
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_return' ? 'active' : '' }}">
                                <a href="{{ route('inventory_return') }}">Return</a>
                            </div>
                            <div class="for-sub-link {{ Route::currentRouteName() === 'inventory_reports' ? 'active' : '' }}">
                                <a href="{{ route('inventory_reports') }}">Reports</a>
                            </div>
                        </div>   
                        <div class="for-link">
                            <a href="{{ route('signout') }}">Logout</a>
                        </div>
                    </div>
                </nav>
            @else
                <nav class="for-navbar">
                    <div class="for-logo">
                        <div class="for-image">
                            <img src={{ asset('assets/logo.jpg') }} width="250" alt="">
                        </div>
                    </div>
                    <div class="for-link-container">
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
                        </div>   
                        <div class="for-link {{ Route::currentRouteName() === 'inventory' ? 'active' : '' }}" id="inventory">
                            <a href="" onclick="openInventory(event)">
                                Inventory
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
                            <div class="for-sub-link {{ Route::currentRouteName() === 'user_inventory_summary' ? 'active' : '' }}">
                                <a href="{{ route('user_inventory_summary') }}">Summary</a>                        
                            </div>
                        </div>   
                        <div class="for-link">
                            <a href="{{ route('signout') }}">Logout</a>
                        </div>
                    </div>
                </nav>
            @endif
            
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
                @elseif(Route::currentRouteName() === "inventory_extends")
                    @yield('inventory_extends')
                @elseif(Route::currentRouteName() === "inventory_return")
                    @yield('inventory_return')
                @elseif(Route::currentRouteName() === "inventory_reports")
                    @yield('inventory_reports')
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
                @elseif(Route::currentRouteName() === "user_inventory_for_rents")
                    @yield('user_inventory_for_rents')
                @elseif(Route::currentRouteName() === "user_inventory_rents")
                    @yield('inventory_rents')
                @elseif(Route::currentRouteName() === "user_inventory_summary")
                    @yield('user_inventory_summary')
                @endif
            </section>
        @endif
    </main>
</body>
</html>
<script defer>
    $('#reservation').click(()=> {
        $('#reservation-active').toggle()
        $('#inventory-active').hide()
        $('#reservation_icon').toggle()
    })
    $('#inventory').click(()=> {
        $('#reservation-active').hide()
        $('#inventory-active').toggle()
        $('#inventory_icon').toggle()
    })

    function openInventory(e) {
        e.preventDefault();
    }
    function openReservation(e) {
        e.preventDefault();
    }
</script>