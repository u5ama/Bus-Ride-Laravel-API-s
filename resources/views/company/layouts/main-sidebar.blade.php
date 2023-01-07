!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('company.company') }}">
                        <img src="{{URL::asset('assets/img/brand/logo_black.png')}}" class="main-logo" alt="logo">
        </a>
        <a class="desktop-logo logo-dark active" href="{{ route('company.company') }}">
                        <img src="{{URL::asset('assets/img/brand/logo.png')}}" class="main-logo dark-theme" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('company.company') }}">
                        <img src="{{URL::asset('assets/img/brand/logo_black.png')}}" class="logo-icon" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('company.company') }}">
                        <img src="{{URL::asset('assets/img/brand/logo.png')}}" class="logo-icon dark-theme" alt="logo">
        </a>
    </div>

    <div class="main-sidemenu">
        <ul class="side-menu mt-3">
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.company') }}">
                    <i class="fa fa-tachometer-alt side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.dashboard') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.bus_stations.index') }}">
                    <i class="fa fa-hotel side-menu__icon"></i>
                    <span class="side-menu__label">Bus Station</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.buses.index') }}">
                    <i class="fa fa-bus-alt side-menu__icon"></i>
                    <span class="side-menu__label">Bus</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.assign_bus.index') }}">
                    <i class="fa fa-bus-alt side-menu__icon"></i>
                    <span class="side-menu__label">Assign Bus</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.routes.index') }}">
                    <i class="fa fa-route side-menu__icon"></i>
                    <span class="side-menu__label">Routes</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.driver.index') }}">
                    <i class="fa fa-user side-menu__icon"></i>
                    <span class="side-menu__label">Drivers</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.payment_account.index') }}">
                    <i class="fa fa-cash-register side-menu__icon"></i>
                    <span class="side-menu__label">Payment Account</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('company.withdraw.index') }}">
                    <i class="fa fa-wallet side-menu__icon"></i>
                    <span class="side-menu__label">Withdraw Request</span>
                </a>
            </li>
            {{--<li class="slide">
                <a class="side-menu__item" href="{{ route('company.contact-us') }}">
                    <i class="fas fa-address-book side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.contact_us') }}</span>
                </a>
            </li>
--}}
{{--            <li class="side-item side-item-category">Global Settings</li>--}}

           {{-- <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.country.index') }}">
                    <i class="fa fa-globe side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.country') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-mail-bulk side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.email') }}</span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                           href="{{ route('admin.smtp-credential.index') }}">{{ config('languageString.smtp_credential') }}</a>
                    </li>
                    <li><a class="slide-item"
                           href="{{ route('admin.fcm-credential.index') }}">{{ config('languageString.fcm_credential') }}</a>
                    </li>
                </ul>
            </li>
--}}

        </ul>
    </div>
</aside>
<!-- main-sidebar -->
