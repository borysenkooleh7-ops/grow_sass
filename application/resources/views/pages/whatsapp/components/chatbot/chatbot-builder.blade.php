@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.chatbot_builder') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button class="btn btn-info btn-sm" onclick="createChatbotFlow()">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.create_flow')) }}
            </button>
        </div>
    </div>

    <!--chatbot section-->
    <div class="row">
        <div class="col-12">

        <!--chatbot status-->
        <div class="card mb-3 bg-light">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="ti-android"></i> {{ cleanLang(__('lang.chatbot_status')) }}:
                            @if(config('whatsapp.chatbot_enabled'))
                                <span class="badge badge-success">{{ cleanLang(__('lang.active')) }}</span>
                            @else
                                <span class="badge badge-secondary">{{ cleanLang(__('lang.inactive')) }}</span>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="custom-control custom-switch d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="chatbot-enabled"
                                {{ config('whatsapp.chatbot_enabled') ? 'checked' : '' }}
                                onchange="toggleChatbot(this.checked)">
                            <label class="custom-control-label" for="chatbot-enabled">{{ cleanLang(__('lang.enable_chatbot')) }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--chatbot settings-->
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="ti-settings"></i> {{ cleanLang(__('lang.chatbot_settings')) }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>{{ cleanLang(__('lang.welcome_message')) }}</label>
                    <textarea class="form-control" rows="3" id="chatbot-welcome-message">{{ config('whatsapp.chatbot_welcome_message') ?? __('lang.default_chatbot_welcome') }}</textarea>
                    <small class="form-text text-muted">{{ cleanLang(__('lang.chatbot_welcome_help')) }}</small>
                </div>

                <div class="form-group">
                    <label>{{ cleanLang(__('lang.fallback_message')) }}</label>
                    <textarea class="form-control" rows="2" id="chatbot-fallback-message">{{ config('whatsapp.chatbot_fallback_message') ?? __('lang.default_chatbot_fallback') }}</textarea>
                    <small class="form-text text-muted">{{ cleanLang(__('lang.chatbot_fallback_help')) }}</small>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="chatbot_handoff_human" name="chatbot_handoff_human"
                        {{ config('whatsapp.chatbot_handoff_human') ? 'checked' : '' }}>
                    <label class="form-check-label" for="chatbot_handoff_human">
                        {{ cleanLang(__('lang.allow_handoff_to_human')) }}
                    </label>
                    <small class="form-text text-muted">{{ cleanLang(__('lang.handoff_help')) }}</small>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="chatbot_business_hours_only" name="chatbot_business_hours_only"
                        {{ config('whatsapp.chatbot_business_hours_only') ? 'checked' : '' }}>
                    <label class="form-check-label" for="chatbot_business_hours_only">
                        {{ cleanLang(__('lang.active_outside_business_hours_only')) }}
                    </label>
                </div>

                <button class="btn btn-primary mt-3" onclick="saveChatbotSettings()">
                    <i class="ti-save"></i> {{ cleanLang(__('lang.save_settings')) }}
                </button>
            </div>
        </div>

        <!--chatbot flows-->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="ti-layout-list-thumb"></i> {{ cleanLang(__('lang.chatbot_flows')) }}</h5>
                <button class="btn btn-sm btn-primary" onclick="createChatbotFlow()">
                    <i class="ti-plus"></i> {{ cleanLang(__('lang.create_flow')) }}
                </button>
            </div>
            <div class="card-body">
                @if(isset($chatbot_flows) && count($chatbot_flows) > 0)
                    <div class="list-group">
                        @foreach($chatbot_flows as $flow)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $flow->name }}</h6>
                                    <small class="text-muted">{{ $flow->description }}</small>
                                    <div class="mt-2">
                                        <span class="badge badge-info">{{ $flow->trigger_type }}</span>
                                        <span class="badge badge-secondary">{{ $flow->steps_count }} steps</span>
                                        @if($flow->is_active)
                                            <span class="badge badge-success">{{ cleanLang(__('lang.active')) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editChatbotFlow({{ $flow->id }})">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testChatbotFlow({{ $flow->id }})">
                                        <i class="ti-control-play"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="duplicateChatbotFlow({{ $flow->id }})">
                                        <i class="ti-files"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteChatbotFlow({{ $flow->id }})">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!--flow preview-->
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6>{{ cleanLang(__('lang.flow_steps')) }}:</h6>
                                <ol class="pl-3">
                                    @foreach($flow->steps as $step)
                                        <li>
                                            <strong>{{ $step->type }}</strong>:
                                            {{ str_limit($step->content, 50) }}
                                            @if($step->options)
                                                <div class="ml-3 mt-1">
                                                    @foreach(json_decode($step->options, true) as $option)
                                                        <span class="badge badge-light">{{ $option['label'] }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted p-5">
                        <i class="ti-android" style="font-size: 64px; opacity: 0.3;"></i>
                        <h4 class="mt-3">{{ cleanLang(__('lang.no_chatbot_flows')) }}</h4>
                        <p>{{ cleanLang(__('lang.create_first_chatbot_flow')) }}</p>
                        <button class="btn btn-primary" onclick="createChatbotFlow()">
                            <i class="ti-plus"></i> {{ cleanLang(__('lang.create_flow')) }}
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!--chatbot analytics-->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="ti-bar-chart"></i> {{ cleanLang(__('lang.chatbot_analytics')) }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <h3>{{ $chatbot_stats['total_conversations'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">{{ cleanLang(__('lang.total_conversations')) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <h3>{{ $chatbot_stats['resolved_by_bot'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">{{ cleanLang(__('lang.resolved_by_bot')) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <h3>{{ $chatbot_stats['handoff_to_human'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">{{ cleanLang(__('lang.handed_to_human')) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <h3>{{ $chatbot_stats['satisfaction_rate'] ?? 0 }}%</h3>
                            <p class="text-muted mb-0">{{ cleanLang(__('lang.satisfaction_rate')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

</div>

<!--JavaScript-->
<script src="/public/js/whatsapp/chatbot.js?v={{ config('system.versioning') }}"></script>

@endsection
