/**
 * Core Events Handler
 * Handles common UI interactions and AJAX events
 */

$(document).ready(function() {

    /**
     * Handle confirm-action-danger clicks
     * Shows confirmation dialog before performing DELETE/PUT/POST actions
     */
    $(document).on('click', '.confirm-action-danger', function(e) {
        e.preventDefault();

        var $button = $(this);
        var confirmTitle = $button.data('confirm-title') || 'Are you sure?';
        var confirmText = $button.data('confirm-text') || 'This action cannot be undone.';
        var ajaxType = $button.data('ajax-type') || 'DELETE';
        var url = $button.data('url');
        var removeRow = $button.data('ajax-success-remove-row') || false;
        var reloadPage = $button.data('ajax-success-reload-page') || false;
        var redirectUrl = $button.data('ajax-success-redirect') || null;

        if (!url) {
            console.error('No URL specified for confirm-action-danger');
            return;
        }

        // Show confirmation dialog
        if (confirm(confirmTitle + '\n\n' + confirmText)) {
            // Show loading state
            NProgress.start();
            $button.prop('disabled', true);

            // Get CSRF token
            var token = $('meta[name="csrf-token"]').attr('content');

            // Perform AJAX request
            $.ajax({
                url: url,
                type: ajaxType,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function(response) {
                    NProgress.done();
                    $button.prop('disabled', false);

                    // Show success notification
                    if (typeof NX !== 'undefined' && NX.notification) {
                        NX.notification({
                            type: 'success',
                            message: response.message || 'Action completed successfully'
                        });
                    }

                    // Handle post-action behaviors
                    if (removeRow) {
                        // Remove the table row
                        $button.closest('tr').fadeOut(400, function() {
                            $(this).remove();
                        });
                    }

                    if (reloadPage) {
                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }

                    if (redirectUrl) {
                        // Redirect to specified URL
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }

                    // If redirect_url is in response, use that
                    if (response.redirect_url) {
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    NProgress.done();
                    $button.prop('disabled', false);

                    var errorMessage = 'An error occurred';

                    // Try to parse error response
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }

                    // Check for session timeout
                    if (xhr.status === 401 || xhr.status === 419) {
                        errorMessage = 'Your session has timed out. Please login again.';
                        // Redirect to login after showing error
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 2000);
                    }

                    // Show error notification
                    if (typeof NX !== 'undefined' && NX.notification) {
                        NX.notification({
                            type: 'error',
                            message: errorMessage
                        });
                    } else {
                        alert(errorMessage);
                    }

                    console.error('AJAX Error:', error, xhr);
                }
            });
        }
    });

    /**
     * Ensure CSRF token is set in AJAX headers globally
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});
