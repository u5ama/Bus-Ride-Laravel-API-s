!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('admin.dashboard') }}">
                        <img src="{{URL::asset('assets/img/brand/logo_black.png')}}" class="main-logo" alt="logo">
        </a>
        <a class="desktop-logo logo-dark active" href="{{ route('admin.dashboard') }}">
                        <img src="{{URL::asset('assets/img/brand/logo.png')}}" class="main-logo dark-theme" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('admin.dashboard') }}">
                        <img src="{{URL::asset('assets/img/brand/logo_black.png')}}" class="logo-icon" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('admin.dashboard') }}">
                        <img src="{{URL::asset('assets/img/brand/logo.png')}}" class="logo-icon dark-theme" alt="logo">
        </a>
    </div>

    <div class="main-sidemenu">
        <ul class="side-menu mt-3">
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-tachometer-alt side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.dashboard') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.company.index') }}">
                    <i class="fa fa-user side-menu__icon"></i>
                    <span class="side-menu__label">Company</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.user.index') }}">
                    <i class="fa fa-user side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.user') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.bus_stations.index') }}">
                    <i class="fa fa-hotel side-menu__icon"></i>
                    <span class="side-menu__label">Bus Station</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.buses.index') }}">
                    <i class="fa fa-bus-alt side-menu__icon"></i>
                    <span class="side-menu__label">Bus</span>
                </a>
            </li>
            {{--<li class="slide">
                <a class="side-menu__item" href="{{ route('admin.assign_bus.index') }}">
                    <i class="fa fa-bus-alt side-menu__icon"></i>
                    <span class="side-menu__label">Assign Bus</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.routes.index') }}">
                    <i class="fa fa-route side-menu__icon"></i>
                    <span class="side-menu__label">Routes</span>
                </a>
            </li>--}}

            {{--<li class="slide">
                <a class="side-menu__item" href="{{ route('admin.contact-us') }}">
                    <i class="fas fa-address-book side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.contact_us') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.report-problem') }}">
                    <i class="fa fa-file side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.report_problem') }}</span>
                </a>
            </li>--}}

           {{-- <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.notification.index') }}">
                    <i class="fa fa-bell side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.send_notification') }}</span>
                </a>
            </li>--}}

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.withdraw_requests.index') }}">
                    <i class="fa fa-wallet side-menu__icon"></i>
                    <span class="side-menu__label">Withdraw Requests</span>
                </a>
            </li>

{{--            <li class="side-item side-item-category">Global Settings</li>--}}

            {{--<li class="slide">
                <a class="side-menu__item" href="{{ route('admin.country.index') }}">
                    <i class="fa fa-globe side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.country') }}</span>
                </a>
            </li>--}}

           {{-- <li class="slide">
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
            </li>--}}


           {{-- <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-language side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.language_admin') }}</span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                           href="{{ route('admin.language.index') }}">{{ config('languageString.languages') }}</a>
                    </li>
                    <li><a class="slide-item"
                           href="{{ route('admin.language-screen.index') }}">{{ config('languageString.language_screen') }}</a>
                    </li>
                    <li><a class="slide-item"
                           href="{{ route('admin.language-string.index') }}">{{ config('languageString.language_string') }}</a>
                    </li>
                </ul>
            </li>--}}

            {{--                <li class="slide">--}}
            {{--                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">--}}
            {{--                        <i class="fas fa-user-shield side-menu__icon"></i>--}}
            {{--                        <span class="side-menu__label">{{ config('languageString.admin') }}</span><i--}}
            {{--                            class="angle fe fe-chevron-down"></i></a>--}}
            {{--                    <ul class="slide-menu">--}}
            {{--                        <li><a class="slide-item"--}}
            {{--                               href="{{ route('admin.admin.index') }}">{{ config('languageString.admin') }}</a>--}}
            {{--                        </li>--}}
            {{--                        <li><a class="slide-item"--}}
            {{--                               href="{{ route('admin.role.index') }}">{{ config('languageString.role') }}</a>--}}
            {{--                        </li>--}}
            {{--                    </ul>--}}
            {{--                </li>--}}
            {{--            <li class="slide">--}}
            {{--                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">--}}
            {{--                    <i class="fa fa-users-cog side-menu__icon"></i>--}}
            {{--                    <span class="side-menu__label">{{config('languageString.panel_theme')}}</span><i--}}
            {{--                        class="angle fe fe-chevron-down"></i></a>--}}
            {{--                <ul class="slide-menu">--}}
            {{--                    <li><a class="slide-item"--}}
            {{--                           href="{{ route('admin.panel-color.index') }}">{{config('languageString.panel_color')}}</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}
            <li class="side-item side-item-category">{{config('languageString.app_setting')}}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.page.index') }}">
                    <i class="fa fa-file side-menu__icon"></i>
                    <span class="side-menu__label">{{config('languageString.text_page')}}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.social-link.index') }}">
                    <i class="fa fa-globe-europe side-menu__icon"></i>
                    <span class="side-menu__label">{{config('languageString.social_link')}}</span>
                </a>
            </li>

            {{--<li class="slide">
                <a class="side-menu__item" href="{{ route('admin.app-control.index') }}">
                    <i class="fa fa-file-word side-menu__icon"></i>
                    <span class="side-menu__label">{{config('languageString.app_control')}}</span>
                </a>
            </li>--}}

{{--            <li class="slide">--}}
{{--                <a class="side-menu__item" href="{{ route('admin.app-menu.index') }}">--}}
{{--                    <i class="fa fa-bars side-menu__icon"></i>--}}
{{--                    <span class="side-menu__label">App Menu</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.setting') }}">
                    <i class="fa fa-cog side-menu__icon"></i>
                    <span class="side-menu__label">{{config('languageString.setting')}}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
