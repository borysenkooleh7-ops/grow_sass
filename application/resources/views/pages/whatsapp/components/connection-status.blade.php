<!--Connection Status Widget-->
<div class="whatsapp-connection-status-widget">
    <div class="connection-status-header">
        <h6>{{ cleanLang(__('lang.connection_status')) }}</h6>
        <button class="btn btn-xs btn-link" onclick="refreshConnectionStatus()">
            <i class="ti-reload"></i>
        </button>
    </div>

    <div class="connection-status-body" id="connection-status-body">
        @if(isset($connections) && count($connections) > 0)
            @foreach($connections as $connection)
            <div class="connection-status-item" data-id="{{ $connection->id }}">
                <div class="connection-info">
                    <div class="connection-name">
                        {{ $connection->connection_name }}
                        @if($connection->is_active)
                            <i class="ti-check-box text-success" title="{{ __('lang.active') }}"></i>
                        @endif
                    </div>
                    <div class="connection-phone">
                        <small class="text-muted">{{ $connection->phone_number ?? __('lang.not_set') }}</small>
                    </div>
                </div>
                <div class="connection-status-indicator">
                    @if($connection->status == 'connected')
                        <span class="status-dot status-dot-success" title="{{ __('lang.connected') }}"></span>
                    @elseif($connection->status == 'connecting')
                        <span class="status-dot status-dot-warning status-dot-pulse" title="{{ __('lang.connecting') }}"></span>
                    @elseif($connection->status == 'error')
                        <span class="status-dot status-dot-danger" title="{{ __('lang.error') }}"></span>
                    @else
                        <span class="status-dot status-dot-default" title="{{ __('lang.disconnected') }}"></span>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center text-muted p-3">
                <small>{{ cleanLang(__('lang.no_connections')) }}</small>
            </div>
        @endif
    </div>
</div>

<!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">

<!--JavaScript-->
<script src="/public/js/whatsapp/connections.js?v={{ config('system.versioning') }}"></script>
