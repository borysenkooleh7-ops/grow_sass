/**
 * WhatsApp QR Scan Page JavaScript
 * WATI Single Connection Mode
 * Handles QR code loading, refresh, and connection status polling
 */

console.log('[QR Scan] JavaScript file loaded successfully');

(function() {
    'use strict';

    console.log('[QR Scan] Initializing QR scan module...');

    let statusCheckInterval = null;
    let qrRefreshInterval = null;
    let isConnected = false;

    /**
     * Load QR code from server
     */
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

    /**
     * Display QR code image
     * @param {string} qrCodeData - Base64 or URL of QR code
     */
    function displayQRCode(qrCodeData) {
        const qrDisplay = $('#qrCodeDisplay');
        qrDisplay.html('<img src="' + qrCodeData + '" alt="WhatsApp QR Code" style="max-width: 100%; height: auto; border-radius: 10px;">');
    }

    /**
     * Update connection status display
     * @param {string} status - Status code (pending, connected, error)
     * @param {string} message - Status message
     */
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

    /**
     * Show error message
     * @param {string} message - Error message
     */
    function showError(message) {
        const qrDisplay = $('#qrCodeDisplay');
        qrDisplay.html(
            '<div class="alert alert-danger">' +
                '<i class="ti-alert"></i> ' + message +
            '</div>'
        );
        updateStatus('error', 'Failed to load QR code');
    }

    /**
     * Refresh QR code
     */
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

    /**
     * Check QR scan status
     * Polls server to check if QR code has been scanned
     */
    function checkQRStatus() {
        if (isConnected) {
            return; // Don't check if already connected
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

    /**
     * Handle successful connection
     * @param {string} phone - Connected phone number
     */
    function onConnectionSuccess(phone) {
        // Stop polling
        stopPolling();

        // Update display
        updateStatus('connected', 'Connected successfully!');

        // Show success notification
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

        // Reload page after 2 seconds to show connected state
        setTimeout(function() {
            window.location.reload();
        }, 2000);
    }

    /**
     * Start polling for connection status
     */
    function startPolling() {
        // Check status every 5 seconds
        statusCheckInterval = setInterval(function() {
            checkQRStatus();
        }, 5000);

        // Auto-refresh QR code every 30 seconds (QR codes expire)
        qrRefreshInterval = setInterval(function() {
            console.log('Auto-refreshing QR code...');
            refreshQRCode();
        }, 30000);
    }

    /**
     * Stop all polling intervals
     */
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

    /**
     * Initialize QR scan page
     */
    function init() {
        console.log('[QR Scan] Init function called');

        // Check if jQuery is available
        if (typeof $ === 'undefined') {
            console.error('[QR Scan] jQuery is not available!');
            return;
        }
        console.log('[QR Scan] jQuery is available');

        // Only initialize if we're on the QR scan page and not connected
        const qrCodeDisplay = document.getElementById('qrCodeDisplay');
        if (!qrCodeDisplay) {
            console.log('[QR Scan] qrCodeDisplay element not found - not on QR scan page');
            return; // Not on QR scan page
        }
        console.log('[QR Scan] qrCodeDisplay element found');

        // Check if already connected by looking at page content
        if ($('.alert-success').length > 0) {
            isConnected = true;
            console.log('[QR Scan] Already connected - skipping QR load');
            return; // Already connected, no need to load QR
        }

        // Load QR code on page load
        console.log('[QR Scan] Starting QR code load and polling...');
        loadQRCode();

        // Start polling for status
        startPolling();

        // Bind refresh button
        $('#refreshQRButton').on('click', function(e) {
            e.preventDefault();
            refreshQRCode();
        });

        // Cleanup on page unload
        $(window).on('beforeunload', function() {
            stopPolling();
        });
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        console.log('[QR Scan] DOM ready, calling init...');
        init();
    });

    console.log('[QR Scan] Module definition complete');

})();

console.log('[QR Scan] JavaScript file execution complete');
