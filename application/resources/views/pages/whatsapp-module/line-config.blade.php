@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ cleanLang(__('lang.line_configuration')) }}</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/whatsapp') }}">{{ cleanLang(__('lang.whatsapp')) }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/whatsapp/connections') }}">{{ cleanLang(__('lang.connections')) }}</a></li>
                <li class="breadcrumb-item active">{{ cleanLang(__('lang.configuration')) }}</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <a href="{{ url('/whatsapp/connections') }}" class="btn btn-secondary btn-sm">
                <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back')) }}
            </a>
        </div>
    </div>

    <!--connection info-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="avatar avatar-md rounded-circle">
                                <i class="mdi mdi-whatsapp mdi-36px text-success"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $connection->whatsappconnection_name }}</h4>
                            <p class="text-muted mb-0">{{ $connection->whatsappconnection_phone }}</p>
                        </div>
                        <div class="ml-auto">
                            @if($connection->whatsappconnection_status == 'connected')
                            <span class="badge badge-success badge-lg">
                                <i class="ti-check"></i> {{ cleanLang(__('lang.connected')) }}
                            </span>
                            @else
                            <span class="badge badge-warning badge-lg">
                                <i class="ti-alert"></i> {{ ucfirst($connection->whatsappconnection_status) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--configuration form-->
    <form id="lineConfigForm" action="{{ url('/whatsapp/line-config/'.$connection->whatsappconnection_id.'/update') }}" method="POST">
        @csrf
        @method('PUT')

        <!--automated messages-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="mdi mdi-message-text"></i> {{ cleanLang(__('lang.automated_messages')) }}
                        </h4>
                    </div>
                    <div class="card-body">

                        <!--welcome message-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.welcome_message')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="welcome_message" rows="3" placeholder="{{ cleanLang(__('lang.welcome_message_placeholder')) }}">{{ $config->whatsapplineconfig_welcome_message ?? '' }}</textarea>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.welcome_message_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--away message-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.away_message')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="away_message" rows="3" placeholder="{{ cleanLang(__('lang.away_message_placeholder')) }}">{{ $config->whatsapplineconfig_away_message ?? '' }}</textarea>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.away_message_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--closure message-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.closure_message')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="closure_message" rows="3" placeholder="{{ cleanLang(__('lang.closure_message_placeholder')) }}">{{ $config->whatsapplineconfig_closure_message ?? '' }}</textarea>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.closure_message_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--inactivity message-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.inactivity_message')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="inactivity_message" rows="3" placeholder="{{ cleanLang(__('lang.inactivity_message_placeholder')) }}">{{ $config->whatsapplineconfig_inactivity_message ?? '' }}</textarea>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.inactivity_message_help')) }}
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--auto assignment-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="mdi mdi-account-multiple"></i> {{ cleanLang(__('lang.auto_assignment')) }}
                        </h4>
                    </div>
                    <div class="card-body">

                        <!--enable auto assignment-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.enable_auto_assignment')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="auto_assign_enabled" name="auto_assign_enabled" value="1" {{ ($config->whatsapplineconfig_auto_assign_enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="auto_assign_enabled">
                                        {{ cleanLang(__('lang.automatically_assign_tickets')) }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.auto_assign_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--assignment logic-->
                        <div class="form-group row" id="assignment_logic_row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.assignment_logic')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <select class="form-control" name="auto_assign_logic">
                                    <option value="round_robin" {{ ($config->whatsapplineconfig_auto_assign_logic ?? 'round_robin') == 'round_robin' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.round_robin')) }}
                                    </option>
                                    <option value="least_active" {{ ($config->whatsapplineconfig_auto_assign_logic ?? '') == 'least_active' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.least_active')) }}
                                    </option>
                                    <option value="random" {{ ($config->whatsapplineconfig_auto_assign_logic ?? '') == 'random' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.random')) }}
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.assignment_logic_help')) }}
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--auto close-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="mdi mdi-timer-off"></i> {{ cleanLang(__('lang.auto_close_inactive')) }}
                        </h4>
                    </div>
                    <div class="card-body">

                        <!--enable auto close-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.enable_auto_close')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="auto_close_enabled" name="auto_close_enabled" value="1" {{ ($config->whatsapplineconfig_auto_close_enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="auto_close_enabled">
                                        {{ cleanLang(__('lang.automatically_close_inactive_tickets')) }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.auto_close_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--inactivity minutes-->
                        <div class="form-group row" id="inactivity_minutes_row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.inactivity_minutes')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <input type="number" class="form-control" name="inactivity_minutes" value="{{ $config->whatsapplineconfig_inactivity_minutes ?? 60 }}" min="1" max="10080">
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.inactivity_minutes_help')) }}
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--business hours-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="mdi mdi-clock-outline"></i> {{ cleanLang(__('lang.business_hours')) }}
                        </h4>
                    </div>
                    <div class="card-body">

                        <!--enable business hours-->
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.enable_business_hours')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="business_hours_enabled" name="business_hours_enabled" value="1" {{ ($config->whatsapplineconfig_business_hours_enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="business_hours_enabled">
                                        {{ cleanLang(__('lang.enable_business_hours_check')) }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    {{ cleanLang(__('lang.business_hours_help')) }}
                                </small>
                            </div>
                        </div>

                        <!--business hours start-->
                        <div class="form-group row" id="business_hours_start_row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.start_time')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <input type="time" class="form-control" name="business_hours_start" value="{{ $config->whatsapplineconfig_business_hours_start ?? '09:00' }}">
                            </div>
                        </div>

                        <!--business hours end-->
                        <div class="form-group row" id="business_hours_end_row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.end_time')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                <input type="time" class="form-control" name="business_hours_end" value="{{ $config->whatsapplineconfig_business_hours_end ?? '17:00' }}">
                            </div>
                        </div>

                        <!--business days-->
                        <div class="form-group row" id="business_days_row">
                            <label class="col-sm-12 col-md-3 col-form-label text-left text-md-right">
                                {{ cleanLang(__('lang.business_days')) }}
                            </label>
                            <div class="col-sm-12 col-md-9">
                                @php
                                $businessDays = explode(',', $config->whatsapplineconfig_business_days ?? 'monday,tuesday,wednesday,thursday,friday');
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                @endphp
                                <div class="row">
                                    @foreach($days as $day)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="day_{{ $day }}" name="business_days[]" value="{{ $day }}" {{ in_array($day, $businessDays) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="day_{{ $day }}">
                                                {{ cleanLang(__('lang.'.$day)) }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--form buttons-->
        <div class="row">
            <div class="col-12">
                <div class="form-group text-right">
                    <a href="{{ url('/whatsapp/connections') }}" class="btn btn-secondary waves-effect waves-light">
                        {{ cleanLang(__('lang.cancel')) }}
                    </a>
                    <button type="submit" class="btn btn-info waves-effect waves-light" id="saveButton">
                        <i class="ti-check"></i> {{ cleanLang(__('lang.save_changes')) }}
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

<!--javascript-->
<script>
$(document).ready(function() {

    // Toggle visibility based on switches
    function toggleBusinessHoursFields() {
        if ($('#business_hours_enabled').is(':checked')) {
            $('#business_hours_start_row, #business_hours_end_row, #business_days_row').show();
        } else {
            $('#business_hours_start_row, #business_hours_end_row, #business_days_row').hide();
        }
    }

    function toggleAutoAssignFields() {
        if ($('#auto_assign_enabled').is(':checked')) {
            $('#assignment_logic_row').show();
        } else {
            $('#assignment_logic_row').hide();
        }
    }

    function toggleAutoCloseFields() {
        if ($('#auto_close_enabled').is(':checked')) {
            $('#inactivity_minutes_row').show();
        } else {
            $('#inactivity_minutes_row').hide();
        }
    }

    // Initial state
    toggleBusinessHoursFields();
    toggleAutoAssignFields();
    toggleAutoCloseFields();

    // Event listeners
    $('#business_hours_enabled').change(toggleBusinessHoursFields);
    $('#auto_assign_enabled').change(toggleAutoAssignFields);
    $('#auto_close_enabled').change(toggleAutoCloseFields);

    // Form submission
    $('#lineConfigForm').on('submit', function(e) {
        e.preventDefault();

        var $btn = $('#saveButton');
        var originalText = $btn.html();

        $btn.html('<i class="ti-reload mdi-spin"></i> {{ cleanLang(__("lang.saving")) }}...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    NioApp.Toast('{{ cleanLang(__("lang.configuration_saved")) }}', 'success');
                }
            },
            error: function(xhr) {
                NioApp.Toast(xhr.responseJSON?.message || '{{ cleanLang(__("lang.error_saving_configuration")) }}', 'error');
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

@endsection
