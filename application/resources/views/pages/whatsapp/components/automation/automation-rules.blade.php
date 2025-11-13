@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.automation_rules') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button" class="btn btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal"
                data-target="#commonModal"
                data-url="{{ url('/whatsapp/automation/create') }}"
                data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.add_automation_rule')) }}"
                data-modal-size="modal-lg"
                data-action-url="{{ url('/whatsapp/automation') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-action-ajax-loading-target="automation-rules-container">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_rule')) }}
            </button>
        </div>
    </div>

    <!--automation rules section-->
    <div class="row">
        <div class="col-12">

        <!--automation rules list-->
        <div id="automation-rules-container">
            @if(isset($rules) && count($rules) > 0)
                @foreach($rules as $rule)
                <div class="card mb-3 automation-rule-card" data-id="{{ $rule->id }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="ti-bolt text-warning"></i> {{ $rule->name }}
                                @if($rule->is_active)
                                    <span class="badge badge-success ml-2">{{ cleanLang(__('lang.active')) }}</span>
                                @else
                                    <span class="badge badge-secondary ml-2">{{ cleanLang(__('lang.inactive')) }}</span>
                                @endif
                            </h5>
                            <small class="text-muted">{{ $rule->description }}</small>
                        </div>
                        <div>
                            <!--toggle active-->
                            <div class="custom-control custom-switch d-inline-block mr-3">
                                <input type="checkbox" class="custom-control-input" id="rule-toggle-{{ $rule->id }}"
                                    {{ $rule->is_active ? 'checked' : '' }}
                                    onchange="toggleAutomationRule({{ $rule->id }}, this.checked)">
                                <label class="custom-control-label" for="rule-toggle-{{ $rule->id }}"></label>
                            </div>
                            <!--actions dropdown-->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                    {{ cleanLang(__('lang.actions')) }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                        href="javascript:void(0)"
                                        data-toggle="modal"
                                        data-target="#commonModal"
                                        data-url="{{ url('/whatsapp/automation/'.$rule->id.'/edit') }}"
                                        data-loading-target="commonModalBody"
                                        data-modal-title="{{ cleanLang(__('lang.edit_rule')) }}"
                                        data-modal-size="modal-lg"
                                        data-action-url="{{ url('/whatsapp/automation/'.$rule->id) }}"
                                        data-action-method="PUT">
                                        <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="duplicateAutomationRule({{ $rule->id }})">
                                        <i class="ti-files"></i> {{ cleanLang(__('lang.duplicate')) }}
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="viewRuleLog({{ $rule->id }})">
                                        <i class="ti-eye"></i> {{ cleanLang(__('lang.view_logs')) }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                                        data-confirm-title="{{ cleanLang(__('lang.delete_rule')) }}"
                                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                        data-ajax-type="DELETE"
                                        data-url="{{ url('/whatsapp/automation/'.$rule->id) }}">
                                        <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>{{ cleanLang(__('lang.trigger')) }}:</strong>
                                <div class="trigger-info mt-2">
                                    <span class="badge badge-primary">{{ runtimeLang($rule->trigger_type) }}</span>
                                    @if($rule->trigger_conditions)
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                @foreach(json_decode($rule->trigger_conditions, true) as $condition)
                                                    <div>• {{ $condition['field'] }} {{ $condition['operator'] }} {{ $condition['value'] }}</div>
                                                @endforeach
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ cleanLang(__('lang.actions')) }}:</strong>
                                <div class="actions-info mt-2">
                                    @if($rule->actions)
                                        @foreach(json_decode($rule->actions, true) as $action)
                                            <div class="mb-1">
                                                <span class="badge badge-info">{{ runtimeLang($action['type']) }}</span>
                                                <small class="text-muted">{{ $action['value'] ?? '' }}</small>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ cleanLang(__('lang.statistics')) }}:</strong>
                                <div class="stats-info mt-2">
                                    <div><small class="text-muted">{{ cleanLang(__('lang.triggered')) }}: <strong>{{ $rule->triggered_count ?? 0 }}</strong> times</small></div>
                                    <div><small class="text-muted">{{ cleanLang(__('lang.last_triggered')) }}: {{ $rule->last_triggered_at ? runtimeDateAgo($rule->last_triggered_at) : __('lang.never') }}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-body text-center text-muted p-5">
                        <i class="ti-bolt" style="font-size: 64px; opacity: 0.3;"></i>
                        <h4 class="mt-3">{{ cleanLang(__('lang.no_automation_rules')) }}</h4>
                        <p>{{ cleanLang(__('lang.create_first_automation_rule')) }}</p>
                        <button type="button" class="btn btn-primary edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal"
                            data-target="#commonModal"
                            data-url="{{ url('/whatsapp/automation/create') }}"
                            data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_automation_rule')) }}"
                            data-modal-size="modal-lg"
                            data-action-url="{{ url('/whatsapp/automation') }}"
                            data-action-method="POST">
                            <i class="ti-plus"></i> {{ cleanLang(__('lang.create_rule')) }}
                        </button>
                    </div>
                </div>
            @endif
        </div>

        </div>
    </div>

</div>

<!--Styles-->
<link href="/public/css/whatsapp/automation.css?v={{ config('system.versioning') }}" rel="stylesheet">

<!--JavaScript-->
<script src="/public/js/whatsapp/automation.js?v={{ config('system.versioning') }}"></script>

@endsection
