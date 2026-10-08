@include(theme('partials._header'))

<style>
    /* =================================
   FORCE FULL-WIDTH DASHBOARD
================================= */

.dashboard_main_wrapper {
    display: flex;
    width: 100%;
}

.dashboard_main_wrapper .main_content {
    flex: 1;
    width: 100%;
    max-width: 100%;
}

/* kill bootstrap container width inside dashboard */
.dashboard_main_wrapper .main_content .container,
.dashboard_main_wrapper .main_content .container-lg,
.dashboard_main_wrapper .main_content .container-xl,
.dashboard_main_wrapper .main_content .container-fluid {
    max-width: 100% !important;
    width: 100% !important;
}

footer,
.aoraeditor-footer {
    display: none !important;
}


</style>

<div class="dashboard_main_wrapper">
    @include(theme('partials._sidebar'))

    <section
        class="main_content dashboard_part @if(\Illuminate\Support\Facades\Route::is('student.gamification.reward')) bg-none bg-body @endif">
        @include(theme('partials._dashboard_menu'))
        @yield('mainContent')
    </section>
</div>
@include('preloader')
<input type="hidden" name="app_debug" class="app_debug" value="{{env('APP_DEBUG') }}">
@include(theme('partials._footer'))
