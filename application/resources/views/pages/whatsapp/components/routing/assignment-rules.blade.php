@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.routing_assignment') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button class="btn btn-info btn-sm" onclick="addRoutingRule()">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_routing_rule')) }}
            </button>
        </div>
    </div>

    <!--routing section-->
    <div class="row">
        <div class="col-12">

        <!--routing strategy-->
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="ti-direction-alt"></i> {{ cleanLang(__('lang.routing_strategy')) }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>{{ cleanLang(__('lang.default_assignment_method')) }}</label>
                    <select class="form-control" id="routing_strategy" name="routing_strategy" onchange="saveRoutingStrategy()">
                        <option value="manual" {{ config('whatsapp.routing_strategy') == 'manual' ? 'selected' : '' }}>
                            {{ cleanLang(__('lang.manual_assignment')) }}
                        </option>
                        <option value="round_robin" {{ config('whatsapp.routing_strategy') == 'round_robin' ? 'selected' : '' }}>
                            {{ cleanLang(__('lang.round_robin')) }}
                        </option>
                        <option value="least_active" {{ config('whatsapp.routing_strategy') == 'least_active' ? 'selected' : '' }}>
                            {{ cleanLang(__('lang.least_active_agent')) }}
                        </option>
                        <option value="skill_based" {{ config('whatsapp.routing_strategy') == 'skill_based' ? 'selected' : '' }}>
                            {{ cleanLang(__('lang.skill_based_routing')) }}
                        </option>
                        <option value="priority_based" {{ config('whatsapp.routing_strategy') == 'priority_based' ? 'selected' : '' }}>
                            {{ cleanLang(__('lang.priority_based_routing')) }}
                        </option>
                    </select>
                    <small class="form-text text-muted">{{ cleanLang(__('lang.routing_strategy_help')) }}</small>
                </div>

                <div class="form-group">
                    <label>{{ cleanLang(__('lang.max_concurrent_tickets_per_agent')) }}</label>
                    <input type="number" class="form-control" name="max_concurrent_tickets" value="{{ config('whatsapp.max_concurrent_tickets') ?? 10 }}" min="1">
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="auto_assign_new_tickets" name="auto_assign_new_tickets"
                        {{ config('whatsapp.auto_assign_new_tickets') ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_assign_new_tickets">
                        {{ cleanLang(__('lang.automatically_assign_new_tickets')) }}
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="reassign_on_offline" name="reassign_on_offline"
                        {{ config('whatsapp.reassign_on_offline') ? 'checked' : '' }}>
                    <label class="form-check-label" for="reassign_on_offline">
                        {{ cleanLang(__('lang.reassign_when_agent_offline')) }}
                    </label>
                </div>

                <button class="btn btn-primary mt-3" onclick="saveRoutingSettings()">
                    <i class="ti-save"></i> {{ cleanLang(__('lang.save_settings')) }}
                </button>
            </div>
        </div>

        <!--custom routing rules-->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="ti-settings"></i> {{ cleanLang(__('lang.custom_routing_rules')) }}</h5>
                <button class="btn btn-sm btn-primary" onclick="addRoutingRule()">
                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_rule')) }}
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ cleanLang(__('lang.priority')) }}</th>
                                <th>{{ cleanLang(__('lang.rule_name')) }}</th>
                                <th>{{ cleanLang(__('lang.conditions')) }}</th>
                                <th>{{ cleanLang(__('lang.assign_to')) }}</th>
                                <th>{{ cleanLang(__('lang.active')) }}</th>
                                <th>{{ cleanLang(__('lang.actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody id="routing-rules-list">
                            @if(isset($routing_rules) && count($routing_rules) > 0)
                                @foreach($routing_rules as $rule)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $rule->priority }}</span>
                                    </td>
                                    <td><strong>{{ $rule->name }}</strong></td>
                                    <td>
                                        <small class="text-muted">
                                            @foreach(json_decode($rule->conditions, true) as $condition)
                                                <div>{{ $condition['field'] }} {{ $condition['operator'] }} {{ $condition['value'] }}</div>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>
                                        @if($rule->assign_to_type == 'user')
                                            <span class="badge badge-info">{{ $rule->assigned_user_name }}</span>
                                        @elseif($rule->assign_to_type == 'team')
                                            <span class="badge badge-success">{{ __('lang.team') }}: {{ $rule->assigned_team_name }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('lang.auto') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="rule-{{ $rule->id }}"
                                                {{ $rule->is_active ? 'checked' : '' }}
                                                onchange="toggleRoutingRule({{ $rule->id }}, this.checked)">
                                            <label class="custom-control-label" for="rule-{{ $rule->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="editRoutingRule({{ $rule->id }})">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-xs btn-outline-danger" onclick="deleteRoutingRule({{ $rule->id }})">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        {{ cleanLang(__('lang.no_routing_rules')) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--agent availability-->
        <div class="card">
            <div class="card-header">
                <h5><i class="ti-user"></i> {{ cleanLang(__('lang.agent_availability')) }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ cleanLang(__('lang.agent')) }}</th>
                                <th>{{ cleanLang(__('lang.status')) }}</th>
                                <th>{{ cleanLang(__('lang.active_tickets')) }}</th>
                                <th>{{ cleanLang(__('lang.max_capacity')) }}</th>
                                <th>{{ cleanLang(__('lang.available_for_assignment')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($agents) && count($agents) > 0)
                                @foreach($agents as $agent)
                                <tr>
                                    <td>
                                        <img src="{{ getUsersAvatar($agent->avatar_directory, $agent->avatar_filename) }}"
                                            alt="user" class="img-circle avatar-xsmall mr-2">
                                        {{ $agent->first_name }} {{ $agent->last_name }}
                                    </td>
                                    <td>
                                        @if($agent->is_online)
                                            <span class="badge badge-success">{{ cleanLang(__('lang.online')) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ cleanLang(__('lang.offline')) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $agent->active_tickets_count ?? 0 }}</strong>
                                    </td>
                                    <td>
                                        {{ $agent->max_tickets ?? 10 }}
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="agent-available-{{ $agent->id }}"
                                                {{ $agent->available_for_assignment ? 'checked' : '' }}
                                                onchange="toggleAgentAvailability({{ $agent->id }}, this.checked)">
                                            <label class="custom-control-label" for="agent-available-{{ $agent->id }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        </div>
    </div>

</div>

<!--JavaScript-->
<script src="/public/js/whatsapp/routing.js?v={{ config('system.versioning') }}"></script>

@endsection
