/**
 * Core Boot Script
 * Initializes libraries and sets up global configurations
 */

$(document).ready(function() {
    // Initialize NProgress
    if (typeof NProgress !== 'undefined') {
        NProgress.configure({
            showSpinner: false,
            minimum: 0.2,
            trickleSpeed: 200
        });
    }

    // Initialize tooltips
    if ($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Initialize popovers
    if ($.fn.popover) {
        $('[data-toggle="popover"]').popover();
    }

    // Initialize select2
    if ($.fn.select2) {
        $('.select2-basic').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }

    // Initialize datepicker
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    // Global AJAX error handler
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        if (jqxhr.status === 419) {
            // CSRF token mismatch
            console.error('CSRF token mismatch. Please refresh the page.');
        }
    });
});

/**
 * NX Notification Helper
 * Global notification function
 */
if (typeof NX === 'undefined') {
    window.NX = {};
}

NX.notification = function(options) {
    var type = options.type || 'info';
    var message = options.message || '';
    var duration = options.duration || 3000;

    // Use toastr if available
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: duration
        };

        toastr[type](message);
    } else {
        // Fallback to console
        console.log('[' + type.toUpperCase() + '] ' + message);

        // Simple alert for errors
        if (type === 'error') {
            alert(message);
        }
    }
};
