/**
 * Core Application Script
 * Main application functions and utilities
 */

/**
 * Handle modal form submissions
 */
$(document).on('click', '.js-ajax-ux-request', function(e) {
    e.preventDefault();

    var $button = $(this);
    var url = $button.data('url');
    var method = $button.data('action-method') || 'POST';
    var loadingTarget = $button.data('loading-target') || $button.data('action-ajax-loading-target');

    // If this is a modal trigger button, load the modal content
    if ($button.hasClass('edit-add-modal-button') && url) {
        loadModalContent(url, $button);
    }
});

/**
 * Load modal content via AJAX
 */
function loadModalContent(url, $button) {
    var modalTarget = $button.data('target') || '#commonModal';
    var loadingTarget = $button.data('loading-target') || 'commonModalBody';
    var modalTitle = $button.data('modal-title') || '';

    NProgress.start();

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            NProgress.done();

            // Set modal title
            if (modalTitle) {
                $(modalTarget).find('.modal-title').text(modalTitle);
            }

            // Load content
            if (typeof response === 'string') {
                $('#' + loadingTarget).html(response);
            } else if (response.html) {
                $('#' + loadingTarget).html(response.html);
            }

            // Show modal
            $(modalTarget).modal('show');

            // Call postrun function if exists
            if ($button.data('ajax-class')) {
                var postrunFunction = $button.data('ajax-class').replace(/-/g, '');
                if (typeof window[postrunFunction] === 'function') {
                    window[postrunFunction]();
                }
            }

            // Setup form submission for the loaded form
            setupModalFormSubmission($button);
        },
        error: function(xhr, status, error) {
            NProgress.done();

            if (xhr.status === 419) {
                NX.notification({
                    type: 'error',
                    message: 'Your session has timed out. Please login again.'
                });
                setTimeout(function() {
                    window.location.href = '/login';
                }, 2000);
            } else {
                NX.notification({
                    type: 'error',
                    message: 'Failed to load content: ' + error
                });
            }
        }
    });
}

/**
 * Setup form submission for modal forms
 */
function setupModalFormSubmission($trigger) {
    var actionUrl = $trigger.data('action-url');
    var actionMethod = $trigger.data('action-method') || 'POST';
    var loadingTarget = $trigger.data('action-ajax-loading-target');

    // Set form action and method
    if (actionUrl) {
        $('#commonModalForm').attr('action', actionUrl);
        $('#commonModalForm').attr('method', actionMethod);
    }

    // Store loading target
    if (loadingTarget) {
        $('#commonModalForm').data('loading-target', loadingTarget);
    }
}

/**
 * Reset modal form
 */
$(document).on('hidden.bs.modal', '#commonModal', function() {
    $(this).find('form').trigger('reset');
    $(this).find('.modal-body').html('');
});
