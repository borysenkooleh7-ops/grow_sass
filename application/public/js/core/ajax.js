/**
 * Core AJAX Handler
 * Handles AJAX form submissions and requests
 */

/**
 * nxAjaxUxRequest - Handle AJAX form submission
 * @param {jQuery} $button - The submit button element
 */
function nxAjaxUxRequest($button) {
    var $form = $button.closest('form');
    var url = $form.attr('action') || $button.data('url');
    var method = $form.attr('method') || $button.data('ajax-type') || $button.data('method') || 'GET';
    var loadingTarget = $button.data('ajax-loading-target') || 'body';

    // Only create FormData if there's an actual form
    var formData = null;
    var ajaxSettings = {};

    if ($form.length > 0) {
        formData = new FormData($form[0]);
        ajaxSettings = {
            data: formData,
            processData: false,
            contentType: false
        };
    }

    if (!url) {
        console.error('No URL specified for AJAX request');
        return false;
    }

    // Show loading indicator if not hidden
    if ($button.data('progress-bar') !== 'hidden') {
        NProgress.start();
    }

    if ($button.prop('disabled') !== undefined) {
        $button.prop('disabled', true);
    }

    // Get CSRF token
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax($.extend({
        url: url,
        type: method,
        headers: {
            'X-CSRF-TOKEN': token
        },
        success: function(response) {
            if ($button.data('progress-bar') !== 'hidden') {
                NProgress.done();
            }

            if ($button.prop('disabled') !== undefined) {
                $button.prop('disabled', false);
            }

            // Handle DOM visibility changes
            if (response.dom_visibility) {
                response.dom_visibility.forEach(function(item) {
                    if (item.action === 'close-modal') {
                        $(item.selector).modal('hide');
                    } else if (item.action === 'show') {
                        $(item.selector).show();
                    } else if (item.action === 'hide') {
                        $(item.selector).hide();
                    }
                });
            }

            // Show notification (unless disabled)
            if (response.notification && $button.data('notifications') !== 'disabled') {
                if (typeof NX !== 'undefined' && NX.notification) {
                    NX.notification({
                        type: response.notification.type || 'success',
                        message: response.notification.value || response.notification.message || 'Request completed'
                    });
                }
            }

            // Reload target container if specified
            if (response.reload_target || loadingTarget) {
                var target = response.reload_target || loadingTarget;
                if ($(target).length && target !== 'hidden') {
                    // Reload the content
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
            }

            // Handle redirects
            if (response.redirect_url) {
                setTimeout(function() {
                    window.location.href = response.redirect_url;
                }, 500);
            }
        },
        error: function(xhr, status, error) {
            if ($button.data('progress-bar') !== 'hidden') {
                NProgress.done();
            }

            if ($button.prop('disabled') !== undefined) {
                $button.prop('disabled', false);
            }

            var errorMessage = 'An error occurred';

            // Parse error response
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.responseJSON.errors) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('<br>');

                    // Log validation errors for debugging
                    console.error('Validation errors:', xhr.responseJSON.errors);
                }
            }

            // Check for session timeout
            if (xhr.status === 401 || xhr.status === 419) {
                errorMessage = 'Your session has timed out. Please login again.';
                setTimeout(function() {
                    window.location.href = '/login';
                }, 2000);
            }

            // Show error notification (unless disabled)
            if ($button.data('notifications') !== 'disabled') {
                if (typeof NX !== 'undefined' && NX.notification) {
                    NX.notification({
                        type: 'error',
                        message: errorMessage
                    });
                } else {
                    alert(errorMessage);
                }
            }

            console.error('AJAX Error:', error, xhr);
            console.error('Full response:', xhr.responseJSON);
        }
    }, ajaxSettings));

    return false;
}
