/**
 * WhatsApp QR Code Modal JavaScript
 * Handles QR code generation, refresh, and connection status polling
 */

/**
 * Refresh QR code for connection
 * @param {number} connectionId - The connection ID
 */
function refreshQRCode(connectionId) {
    $.ajax({
        url: '/whatsapp/connections/' + connectionId + '/refresh-qr',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            $.notify({
                message: NXLANG.error_request_could_not_be_completed
            }, {
                type: 'danger',
                showProgressbar: false,
                timer: 4000
            });
        }
    });
}

/**
 * Generate new QR code for connection
 * @param {number} connectionId - The connection ID
 */
function generateQRCode(connectionId) {
    $.ajax({
        url: '/whatsapp/connections/' + connectionId + '/generate-qr',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            $.notify({
                message: NXLANG.error_request_could_not_be_completed
            }, {
                type: 'danger',
                showProgressbar: false,
                timer: 4000
            });
        }
    });
}

/**
 * Initialize QR code connection status polling
 * Checks connection status every 5 seconds
 * @param {number} connectionId - The connection ID to monitor
 */
function initQRCodeStatusPolling(connectionId) {
    // Auto-refresh status every 5 seconds
    let statusCheckInterval = setInterval(function() {
        $.ajax({
            url: '/whatsapp/connections/' + connectionId + '/status',
            method: 'GET',
            success: function(response) {
                if (response.success && response.data.status === 'connected') {
                    clearInterval(statusCheckInterval);
                    clearInterval(qrRefreshInterval); // Also clear QR refresh interval
                    $.notify({
                        message: NXLANG.connection_successful || 'Connection successful!'
                    }, {
                        type: 'success',
                        showProgressbar: false,
                        timer: 2000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        }
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            }
        });
    }, 5000);

    // Auto-refresh QR code every 30 seconds (QR codes typically expire)
    let qrRefreshInterval = setInterval(function() {
        refreshQRCodeSilently(connectionId);
    }, 30000); // 30 seconds

    // Clear intervals when modal is closed
    $('#commonModal').on('hidden.bs.modal', function() {
        clearInterval(statusCheckInterval);
        clearInterval(qrRefreshInterval);
    });
}

/**
 * Refresh QR code silently without page reload
 * @param {number} connectionId - The connection ID
 */
function refreshQRCodeSilently(connectionId) {
    $.ajax({
        url: '/whatsapp/connections/' + connectionId + '/get-qr',
        method: 'GET',
        success: function(response) {
            if (response.success && response.qr_code) {
                // Update QR code image
                $('.qr-code-container img').attr('src', response.qr_code);

                // Show a subtle indicator that QR was refreshed
                $('.qr-status-indicator').html(
                    '<span class="badge badge-info"><i class="ti-reload"></i> QR Code refreshed at ' +
                    new Date().toLocaleTimeString() + '</span>'
                );

                console.log('QR code auto-refreshed at', new Date().toLocaleTimeString());
            }
        },
        error: function(xhr) {
            console.error('Failed to auto-refresh QR code:', xhr);
        }
    });
}

// Auto-initialize when QR code modal is loaded
$(document).ready(function() {
    const qrModalContent = document.getElementById('qr-code-modal-content');
    if (qrModalContent) {
        const connectionId = qrModalContent.getAttribute('data-connection-id');
        if (connectionId && connectionId > 0) {
            initQRCodeStatusPolling(connectionId);
        }
    }
});
