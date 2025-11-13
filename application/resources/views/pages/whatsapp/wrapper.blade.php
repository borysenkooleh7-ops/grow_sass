@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--heading-->

        <!--action buttons-->
        @include('pages.whatsapp.components.misc.list-page-actions')
        <!--action buttons-->

    </div>

    <!--stats panel-->
    @if(auth()->user()->is_team)
    <div id="whatsapp-stats-wrapper" class="stats-widget-wrapper card-group">
        @include('pages.whatsapp.components.misc.list-pages-stats')
    </div>
    @endif

    <!--filter-->
    @if(auth()->user()->is_team)
    <div class="filter-strip">
        <div class="filter-items">
            @include('pages.whatsapp.components.misc.filter-whatsapp')
        </div>
    </div>
    @endif

    <!--main table view-->
    @include('pages.whatsapp.components.table.wrapper')

</div>
<!--main content -->

<!--WhatsApp Conversation Panel-->
@include('pages.whatsapp.panel')
@endsection
