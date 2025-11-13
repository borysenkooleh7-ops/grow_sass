/**
 * WhatsApp Connections JavaScript
 * Handles connection status refresh and monitoring
 */

/**
 * Initialize WhatsApp Connections Modal
 * Called after modal content is loaded
 */
function NXWhatsappConnections() {
    // Initialize select2 dropdowns
    if ($.fn.select2) {
        $('.select2-basic').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
    }

    // Handle connection type change - load dynamic fields
    $('#connection_type').off('change').on('change', function() {
        var type = $(this).val();
        var fieldsContainer = $('#connection-type-fields');

        if (type) {
            NProgress.start();
            $.ajax({
                url: '/whatsapp/connections/fields/' + type,
                method: 'GET',
                success: function(html) {
                    fieldsContainer.html(html);
                    NProgress.done();
                },
                error: function(xhr) {
                    console.error('Failed to load connection fields:', xhr);
                    fieldsContainer.html('<div class="alert alert-danger">Failed to load connection fields</div>');
                    NProgress.done();
                }
            });
        } else {
            fieldsContainer.html('');
        }
    });
}

$(document).ready(function() {

    /**
     * Handle connection type change - load dynamic fields (for non-modal contexts)
     */
    $(document).on('change', '#connection_type', function() {
        var type = $(this).val();
        var fieldsContainer = $('#connection-type-fields');

        if (type) {
            NProgress.start();
            $.ajax({
                url: '/whatsapp/connections/fields/' + type,
                method: 'GET',
                success: function(html) {
                    fieldsContainer.html(html);
                    NProgress.done();
                },
                error: function(xhr) {
                    console.error('Failed to load connection fields:', xhr);
                    fieldsContainer.html('<div class="alert alert-danger">Failed to load connection fields</div>');
                    NProgress.done();
                }
            });
        } else {
            fieldsContainer.html('');
        }
    });

});

/**
 * Refresh connection status
 */
function refreshConnectionStatus() {
    $.ajax({
        url: '/whatsapp/connections/status',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#connection-status-body').html(response.html);
            }
        },
        error: function(xhr) {
            console.error('Failed to refresh connection status:', xhr);
        }
    });
}

// Auto-refresh connection status every 30 seconds
setInterval(refreshConnectionStatus, 30000);
