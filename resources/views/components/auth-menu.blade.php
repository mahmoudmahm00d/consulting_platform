<div class="ms-auto">
    <!-- Authentication Links -->
    @guest
        <div class="menu">
            @if (Route::has('login'))
                <div class="menu-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </div>
            @endif
            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        </div>
    @else
        <!--begin::Trigger-->
        <a href="#" type="button" class="btn btn-link text-dark" data-kt-menu-trigger="hover"
            data-kt-menu-placement="bottom-start">
            {{ Auth::user()->name }}
        </a>
        <!--end::Trigger-->

        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-gray fw-bold fs-7 w-200px py-4"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('logout') }}" class="menu-link px-3"
                    onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu-->
    @endguest
