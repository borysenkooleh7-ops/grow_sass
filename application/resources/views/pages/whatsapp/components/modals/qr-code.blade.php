<!--QR Code Modal for Baileys Connection-->
<div class="row" data-connection-id="{{ $connection_id ?? 0 }}" id="qr-code-modal-content">
    <div class="col-lg-12 text-center">

        @if(isset($qr_code) && $qr_code)
            <div class="qr-code-container mb-3">
                <img src="{{ $qr_code }}" alt="QR Code" class="img-fluid" style="max-width: 350px; border: 2px solid #eee; border-radius: 10px; padding: 15px;">
            </div>

            <div class="alert alert-info">
                <h5><i class="fab fa-whatsapp"></i> {{ cleanLang(__('lang.scan_instructions')) }}</h5>
                <ol class="text-left">
                    <li>{{ cleanLang(__('lang.qr_step_1')) }}</li>
                    <li>{{ cleanLang(__('lang.qr_step_2')) }}</li>
                    <li>{{ cleanLang(__('lang.qr_step_3')) }}</li>
                    <li>{{ cleanLang(__('lang.qr_step_4')) }}</li>
                </ol>
            </div>

            <div class="qr-status-indicator">
                <span class="badge badge-warning">
                    <i class="ti-reload"></i> {{ cleanLang(__('lang.waiting_for_scan')) }}
                </span>
            </div>

            <div class="mt-3">
                <button type="button" class="btn btn-secondary btn-sm" onclick="refreshQRCode({{ $connection_id }})">
                    <i class="ti-reload"></i> {{ cleanLang(__('lang.refresh_qr')) }}
                </button>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="ti-alert"></i> {{ cleanLang(__('lang.qr_code_not_available')) }}
                <p class="mt-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="generateQRCode({{ $connection_id }})">
                        <i class="ti-reload"></i> {{ cleanLang(__('lang.generate_qr')) }}
                    </button>
                </p>
            </div>
        @endif

        <!--connection status-->
        <div class="mt-3" id="connection-status-info">
            @if(isset($connection))
                <div class="connection-status-box p-3" style="background: #f9f9f9; border-radius: 8px;">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">{{ cleanLang(__('lang.status')) }}</small>
                            <div class="mt-1">
                                <span class="label {{ $connection->status == 'connected' ? 'label-success' : ($connection->status == 'connecting' ? 'label-warning' : 'label-default') }}">
                                    {{ runtimeLang($connection->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{ cleanLang(__('lang.phone_number')) }}</small>
                            <div class="mt-1">
                                @if($connection->phone_number)
                                    <strong>{{ $connection->phone_number }}</strong>
                                @else
                                    <span class="text-muted">{{ __('lang.not_connected') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">

<!--JavaScript-->
<script src="/public/js/whatsapp/qr-code.js?v={{ config('system.versioning') }}"></script>
