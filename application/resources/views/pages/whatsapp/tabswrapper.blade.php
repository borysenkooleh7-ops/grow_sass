@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--heading-->

    </div>

    <!--tabs navigation-->
    <div class="tabs-menu-wrapper">
        <ul class="nav nav-tabs" id="whatsappTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'tickets' || !request('tab') ? 'active' : '' }}"
                    id="tickets-tab" data-toggle="tab" href="#tickets" role="tab">
                    <i class="ti-ticket"></i> {{ cleanLang(__('lang.tickets')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'connections' ? 'active' : '' }}"
                    id="connections-tab" data-toggle="tab" href="#connections" role="tab">
                    <i class="ti-plug"></i> {{ cleanLang(__('lang.connections')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'templates' ? 'active' : '' }}"
                    id="templates-tab" data-toggle="tab" href="#templates" role="tab">
                    <i class="ti-file"></i> {{ cleanLang(__('lang.templates')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'quick-replies' ? 'active' : '' }}"
                    id="quick-replies-tab" data-toggle="tab" href="#quick-replies" role="tab">
                    <i class="ti-comment-alt"></i> {{ cleanLang(__('lang.quick_replies')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'analytics' ? 'active' : '' }}"
                    id="analytics-tab" data-toggle="tab" href="#analytics" role="tab">
                    <i class="ti-bar-chart"></i> {{ cleanLang(__('lang.analytics')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'settings' ? 'active' : '' }}"
                    id="settings-tab" data-toggle="tab" href="#settings" role="tab">
                    <i class="ti-settings"></i> {{ cleanLang(__('lang.settings')) }}
                </a>
            </li>
        </ul>
    </div>

    <!--tabs content-->
    <div class="tab-content" id="whatsappTabsContent">

        <!--tickets tab-->
        <div class="tab-pane fade {{ request('tab') == 'tickets' || !request('tab') ? 'show active' : '' }}"
            id="tickets" role="tabpanel">
            @include('pages.whatsapp.wrapper')
        </div>

        <!--connections tab-->
        <div class="tab-pane fade {{ request('tab') == 'connections' ? 'show active' : '' }}"
            id="connections" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @include('pages.whatsapp.components.connections-list')
                </div>
            </div>
        </div>

        <!--templates tab-->
        <div class="tab-pane fade {{ request('tab') == 'templates' ? 'show active' : '' }}"
            id="templates" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @include('pages.whatsapp.components.templates-manager')
                </div>
            </div>
        </div>

        <!--quick replies tab-->
        <div class="tab-pane fade {{ request('tab') == 'quick-replies' ? 'show active' : '' }}"
            id="quick-replies" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @include('pages.whatsapp.components.quick-replies-manager')
                </div>
            </div>
        </div>

        <!--analytics tab-->
        <div class="tab-pane fade {{ request('tab') == 'analytics' ? 'show active' : '' }}"
            id="analytics" role="tabpanel">
            @include('pages.whatsapp.components.analytics')
        </div>

        <!--settings tab-->
        <div class="tab-pane fade {{ request('tab') == 'settings' ? 'show active' : '' }}"
            id="settings" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @include('pages.whatsapp.components.settings')
                </div>
            </div>
        </div>

    </div>

</div>
<!--main content -->
@endsection
