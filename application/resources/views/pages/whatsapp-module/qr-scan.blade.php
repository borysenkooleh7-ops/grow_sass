@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-12 col-12 align-self-center">
            <h3 class="text-themecolor">
                <i class="mdi mdi-qrcode-scan"></i> {{ __('lang.whatsapp_qr_scan') ?? 'WhatsApp QR Code' }}
            </h3>
            <p class="text-muted">{{ __('lang.scan_qr_to_connect') ?? 'Scan this QR code with your WhatsApp mobile app to connect' }}</p>
        </div>
    </div>

    <!--QR code content-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if($isConnected && $connectionStatus === 'connected')
                    <!--Connected state-->
                    <div class="alert alert-success text-center p-40" role="alert">
                        <h2><i class="mdi mdi-check-circle text-success"></i></h2>
                        <h3>{{ __('lang.whatsapp_connected') ?? 'WhatsApp Connected!' }}</h3>
                        <p class="m-t-20">{{ __('lang.your_whatsapp_is_connected') ?? 'Your WhatsApp is successfully connected and ready to use.' }}</p>

                        @if($phoneNumber)
                        <p class="m-t-10">
                            <strong>{{ __('lang.connected_number') ?? 'Connected Number' }}:</strong>
                            <span class="badge badge-lg badge-success">{{ $phoneNumber }}</span>
                        </p>
                        @endif

                        <div class="m-t-30">
                            <a href="{{ url('/whatsapp/conversations') }}" class="btn btn-info btn-lg">
                                <i class="ti-comments"></i> {{ __('lang.go_to_conversations') ?? 'Go to Conversations' }}
                            </a>
                        </div>
                    </div>

                    @else
                    <!--QR code display-->
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <!--status indicator-->
                            <div class="text-center m-b-30">
                                <h4 class="qr-status-text">
                                    <span class="qr-status-indicator">
                                        <i class="mdi mdi-loading mdi-spin"></i>
                                        {{ __('lang.loading_qr_code') ?? 'Loading QR Code...' }}
                                    </span>
                                </h4>
                            </div>

                            <!--QR code container-->
                            <div class="qr-code-container text-center p-40" style="background: #f5f5f5; border-radius: 10px;">
                                <div class="qr-code-display" id="qrCodeDisplay">
                                    <!--QR code will be loaded here-->
                                    <div class="qr-loading">
                                        <i class="mdi mdi-loading mdi-spin" style="font-size: 48px; color: #999;"></i>
                                        <p class="m-t-20 text-muted">{{ __('lang.fetching_qr_code') ?? 'Fetching QR code from WATI...' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!--instructions-->
                            <div class="alert alert-info m-t-30" role="alert">
                                <h5><i class="ti-info-alt"></i> {{ __('lang.how_to_scan') ?? 'How to Scan' }}</h5>
                                <ol class="m-t-10">
                                    <li>{{ __('lang.qr_step_1') ?? 'Open WhatsApp on your phone' }}</li>
                                    <li>{{ __('lang.qr_step_2') ?? 'Tap Menu or Settings and select Linked Devices' }}</li>
                                    <li>{{ __('lang.qr_step_3') ?? 'Tap Link a Device' }}</li>
                                    <li>{{ __('lang.qr_step_4') ?? 'Point your phone at this screen to capture the QR code' }}</li>
                                </ol>
                            </div>

                            <!--refresh button-->
                            <div class="text-center m-t-20">
                                <button type="button" class="btn btn-outline-info btn-sm" id="refreshQRButton">
                                    <i class="mdi mdi-refresh"></i> {{ __('lang.refresh_qr_code') ?? 'Refresh QR Code' }}
                                </button>
                                <p class="text-muted m-t-10">
                                    <small>{{ __('lang.qr_auto_refresh') ?? 'QR code automatically refreshes every 30 seconds' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!--Connection info card-->
    @if($connection)
    <div class="row m-t-20">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('lang.connection_information') ?? 'Connection Information' }}</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="200">{{ __('lang.connection_name') ?? 'Connection Name' }}:</th>
                            <td>{{ $connection->whatsappconnection_name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('lang.status') ?? 'Status' }}:</th>
                            <td>
                                @if($connectionStatus === 'connected')
                                    <span class="badge badge-success">{{ __('lang.connected') ?? 'Connected' }}</span>
                                @elseif($connectionStatus === 'pending')
                                    <span class="badge badge-warning">{{ __('lang.pending') ?? 'Pending' }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('lang.disconnected') ?? 'Disconnected' }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($connection->whatsappconnection_last_connected)
                        <tr>
                            <th>{{ __('lang.last_connected') ?? 'Last Connected' }}:</th>
                            <td>{{ \Carbon\Carbon::parse($connection->whatsappconnection_last_connected)->diffForHumans() }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('lang.provider') ?? 'Provider' }}:</th>
                            <td>
                                <span class="badge badge-info">WATI</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

<!--JavaScript for QR scan module - INLINE VERSION (temporary workaround for 404 issue)-->
<script>
console.log('[QR Scan] JavaScript file loaded successfully');

// Wait for jQuery to be available
(function waitForJQuery() {
    if (typeof jQuery === 'undefined') {
        console.log('[QR Scan] Waiting for jQuery...');
        setTimeout(waitForJQuery, 50);
        return;
    }

    console.log('[QR Scan] jQuery detected, initializing module...');

    (function($) {
        'use strict';

        console.log('[QR Scan] Initializing QR scan module...');

        let statusCheckInterval = null;
        let qrRefreshInterval = null;
        let isConnected = false;

    function loadQRCode() {
        console.log('[QR Scan] Loading QR code from server...');
        $.ajax({
            url: '/whatsapp/qr-code',
            method: 'GET',
            success: function(response) {
                console.log('[QR Scan] QR code response received:', response);
                if (response.success && response.qr_code) {
                    displayQRCode(response.qr_code);
                    updateStatus('pending', 'Scan this QR code with WhatsApp mobile app');
                } else {
                    showError(response.message || 'Failed to load QR code');
                }
            },
            error: function(xhr) {
                console.error('[QR Scan] Failed to load QR code:', xhr);
                let errorMessage = 'Failed to load QR code';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                console.error('[QR Scan] Error message:', errorMessage);
                showError(errorMessage);
            }
        });
    }

    function displayQRCode(qrCodeData) {
        const qrDisplay = $('#qrCodeDisplay');
        qrDisplay.html('<img src="' + qrCodeData + '" alt="WhatsApp QR Code" style="max-width: 100%; height: auto; border-radius: 10px;">');
    }

    function updateStatus(status, message) {
        const statusIndicator = $('.qr-status-indicator');
        let icon = '<i class="mdi mdi-loading mdi-spin"></i>';
        let statusClass = 'text-warning';

        if (status === 'connected') {
            icon = '<i class="mdi mdi-check-circle"></i>';
            statusClass = 'text-success';
        } else if (status === 'error') {
            icon = '<i class="mdi mdi-alert-circle"></i>';
            statusClass = 'text-danger';
        } else if (status === 'pending') {
            icon = '<i class="mdi mdi-qrcode-scan"></i>';
            statusClass = 'text-info';
        }

        statusIndicator.html(icon + ' ' + message);
        statusIndicator.removeClass('text-success text-danger text-warning text-info').addClass(statusClass);
    }

    function showError(message) {
        const qrDisplay = $('#qrCodeDisplay');
        qrDisplay.html(
            '<div class="alert alert-danger">' +
                '<i class="ti-alert"></i> ' + message +
            '</div>'
        );
        updateStatus('error', 'Failed to load QR code');
    }

    function refreshQRCode() {
        updateStatus('pending', 'Refreshing QR code...');

        $.ajax({
            url: '/whatsapp/qr-refresh',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success && response.qr_code) {
                    displayQRCode(response.qr_code);
                    updateStatus('pending', 'QR code refreshed - Please scan');

                    $.notify({
                        message: 'QR code refreshed successfully'
                    }, {
                        type: 'success',
                        showProgressbar: false,
                        timer: 2000
                    });
                } else {
                    showError(response.message || 'Failed to refresh QR code');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to refresh QR code';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showError(errorMessage);
            }
        });
    }

    function checkQRStatus() {
        if (isConnected) {
            return;
        }

        $.ajax({
            url: '/whatsapp/qr-status',
            method: 'GET',
            success: function(response) {
                if (response.success && response.status === 'connected') {
                    isConnected = true;
                    onConnectionSuccess(response.phone);
                } else if (response.status === 'pending') {
                    updateStatus('pending', 'Waiting for scan...');
                }
            },
            error: function(xhr) {
                console.error('Status check failed:', xhr);
            }
        });
    }

    function onConnectionSuccess(phone) {
        stopPolling();
        updateStatus('connected', 'Connected successfully!');

        $.notify({
            message: 'WhatsApp connected successfully!' + (phone ? ' (' + phone + ')' : '')
        }, {
            type: 'success',
            showProgressbar: false,
            timer: 3000,
            placement: {
                from: 'top',
                align: 'center'
            }
        });

        setTimeout(function() {
            window.location.reload();
        }, 2000);
    }

    function startPolling() {
        statusCheckInterval = setInterval(function() {
            checkQRStatus();
        }, 5000);

        qrRefreshInterval = setInterval(function() {
            console.log('Auto-refreshing QR code...');
            refreshQRCode();
        }, 30000);
    }

    function stopPolling() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
            statusCheckInterval = null;
        }
        if (qrRefreshInterval) {
            clearInterval(qrRefreshInterval);
            qrRefreshInterval = null;
        }
    }

    function init() {
        console.log('[QR Scan] Init function called');

        if (typeof $ === 'undefined') {
            console.error('[QR Scan] jQuery is not available!');
            return;
        }
        console.log('[QR Scan] jQuery is available');

        const qrCodeDisplay = document.getElementById('qrCodeDisplay');
        if (!qrCodeDisplay) {
            console.log('[QR Scan] qrCodeDisplay element not found - not on QR scan page');
            return;
        }
        console.log('[QR Scan] qrCodeDisplay element found');

        if ($('.alert-success').length > 0) {
            isConnected = true;
            console.log('[QR Scan] Already connected - skipping QR load');
            return;
        }

        console.log('[QR Scan] Starting QR code load and polling...');
        loadQRCode();
        startPolling();

        $('#refreshQRButton').on('click', function(e) {
            e.preventDefault();
            refreshQRCode();
        });

        $(window).on('beforeunload', function() {
            stopPolling();
        });
    }

    $(document).ready(function() {
        console.log('[QR Scan] DOM ready, calling init...');
        init();
    });

    console.log('[QR Scan] Module definition complete');

    })(jQuery);

})();

console.log('[QR Scan] JavaScript file execution complete');
</script>
