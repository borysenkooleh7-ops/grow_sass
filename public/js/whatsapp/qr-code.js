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
            ,
                type: 'error',
                
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
            ,
                type: 'error',
                
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
                    $.notify({
                        message: NXLANG.connection_successful || 'Connection successful!'
                    ,
                        type: 'success',
                        
                        
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

    // Clear interval when modal is closed (use .one() to prevent handler accumulation)
    $('#commonModal').one('hidden.bs.modal', function() {
        clearInterval(statusCheckInterval);
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
