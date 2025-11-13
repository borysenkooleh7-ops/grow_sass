@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.sla_management') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button class="btn btn-info btn-sm" onclick="addSLAPolicy()">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_sla_policy')) }}
            </button>
        </div>
    </div>

    <!--SLA section-->
    <div class="row">
        <div class="col-12">

        <!--SLA overview-->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h3>{{ $sla_stats['met_percentage'] ?? 0 }}%</h3>
                        <p class="mb-0">{{ cleanLang(__('lang.sla_met')) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h3>{{ $sla_stats['breached'] ?? 0 }}</h3>
                        <p class="mb-0">{{ cleanLang(__('lang.sla_breached')) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h3>{{ $sla_stats['at_risk'] ?? 0 }}</h3>
                        <p class="mb-0">{{ cleanLang(__('lang.at_risk')) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h3>{{ $sla_stats['avg_response_time'] ?? '0m' }}</h3>
                        <p class="mb-0">{{ cleanLang(__('lang.avg_response_time')) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!--SLA policies-->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="ti-timer"></i> {{ cleanLang(__('lang.sla_policies')) }}</h5>
                <button class="btn btn-sm btn-primary" onclick="addSLAPolicy()">
                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_policy')) }}
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ cleanLang(__('lang.policy_name')) }}</th>
                                <th>{{ cleanLang(__('lang.priority')) }}</th>
                                <th>{{ cleanLang(__('lang.first_response_time')) }}</th>
                                <th>{{ cleanLang(__('lang.resolution_time')) }}</th>
                                <th>{{ cleanLang(__('lang.business_hours')) }}</th>
                                <th>{{ cleanLang(__('lang.active')) }}</th>
                                <th>{{ cleanLang(__('lang.actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($sla_policies) && count($sla_policies) > 0)
                                @foreach($sla_policies as $policy)
                                <tr>
                                    <td><strong>{{ $policy->name }}</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $policy->priority == 'urgent' ? 'danger' : ($policy->priority == 'high' ? 'warning' : 'info') }}">
                                            {{ ucfirst($policy->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $policy->first_response_time }} minutes</td>
                                    <td>{{ $policy->resolution_time }} hours</td>
                                    <td>
                                        @if($policy->business_hours_only)
                                            <i class="ti-check text-success"></i> {{ cleanLang(__('lang.yes')) }}
                                        @else
                                            <i class="ti-close text-muted"></i> {{ cleanLang(__('lang.no')) }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="sla-{{ $policy->id }}"
                                                {{ $policy->is_active ? 'checked' : '' }}
                                                onchange="toggleSLAPolicy({{ $policy->id }}, this.checked)">
                                            <label class="custom-control-label" for="sla-{{ $policy->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="editSLAPolicy({{ $policy->id }})">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-xs btn-outline-danger" onclick="deleteSLAPolicy({{ $policy->id }})">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted">{{ cleanLang(__('lang.no_sla_policies')) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--At-risk tickets-->
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="ti-alert"></i> {{ cleanLang(__('lang.tickets_at_risk')) }}</h5>
            </div>
            <div class="card-body">
                @if(isset($at_risk_tickets) && count($at_risk_tickets) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ cleanLang(__('lang.ticket')) }}</th>
                                    <th>{{ cleanLang(__('lang.priority')) }}</th>
                                    <th>{{ cleanLang(__('lang.assigned')) }}</th>
                                    <th>{{ cleanLang(__('lang.time_remaining')) }}</th>
                                    <th>{{ cleanLang(__('lang.sla_type')) }}</th>
                                    <th>{{ cleanLang(__('lang.action')) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($at_risk_tickets as $ticket)
                                <tr class="bg-warning-light">
                                    <td>{{ str_limit($ticket->ticket_subject, 40) }}</td>
                                    <td>
                                        <span class="badge badge-warning">{{ ucfirst($ticket->priority) }}</span>
                                    </td>
                                    <td>
                                        @if($ticket->assigned_to)
                                            <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename) }}"
                                                alt="user" class="img-circle avatar-xsmall">
                                        @else
                                            <span class="text-danger">{{ __('lang.unassigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="ti-timer"></i> {{ $ticket->sla_time_remaining }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->sla_type }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-primary" onclick="openWhatsappConversation({{ $ticket->id }})">
                                            <i class="ti-eye"></i> {{ cleanLang(__('lang.view')) }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted p-4">
                        <i class="ti-check" style="font-size: 48px; opacity: 0.3; color: green;"></i>
                        <p>{{ cleanLang(__('lang.no_tickets_at_risk')) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!--Breached tickets-->
        <div class="card">
            <div class="card-header">
                <h5><i class="ti-close"></i> {{ cleanLang(__('lang.sla_breached_tickets')) }}</h5>
            </div>
            <div class="card-body">
                @if(isset($breached_tickets) && count($breached_tickets) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ cleanLang(__('lang.ticket')) }}</th>
                                    <th>{{ cleanLang(__('lang.priority')) }}</th>
                                    <th>{{ cleanLang(__('lang.assigned')) }}</th>
                                    <th>{{ cleanLang(__('lang.breach_time')) }}</th>
                                    <th>{{ cleanLang(__('lang.sla_type')) }}</th>
                                    <th>{{ cleanLang(__('lang.action')) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($breached_tickets as $ticket)
                                <tr class="bg-danger-light">
                                    <td>{{ str_limit($ticket->ticket_subject, 40) }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ ucfirst($ticket->priority) }}</span>
                                    </td>
                                    <td>
                                        @if($ticket->assigned_to)
                                            <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename) }}"
                                                alt="user" class="img-circle avatar-xsmall">
                                        @else
                                            <span class="text-danger">{{ __('lang.unassigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="ti-alert"></i> {{ runtimeDateAgo($ticket->sla_breach_time) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->sla_type }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-danger" onclick="openWhatsappConversation({{ $ticket->id }})">
                                            <i class="ti-eye"></i> {{ cleanLang(__('lang.view')) }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted p-4">
                        <i class="ti-check" style="font-size: 48px; opacity: 0.3; color: green;"></i>
                        <p>{{ cleanLang(__('lang.no_breached_tickets')) }}</p>
                    </div>
                @endif
            </div>
        </div>

        </div>
    </div>

</div>

<!--JavaScript-->
<script src="/public/js/whatsapp/sla.js?v={{ config('system.versioning') }}"></script>

@endsection
