/**
 * WhatsApp SLA Management JavaScript
 * Handles SLA policy management and ticket monitoring
 */

/**
 * Open modal to add new SLA policy
 */
function addSLAPolicy() {
    var url = '/whatsapp/sla/create';

    // Set modal title
    $(".modal-title").html(NXLANG.add_sla_policy || 'Add SLA Policy');

    // Load modal content
    $.ajax({
        url: url,
        success: function(response) {
            $("#commonModal .modal-body").html(response);
            $("#commonModal").modal("show");
        }
    });
}

/**
 * Open modal to edit SLA policy
 * @param {number} policyId - The policy ID to edit
 */
function editSLAPolicy(policyId) {
    var url = '/whatsapp/sla/' + policyId + '/edit';

    // Set modal title
    $(".modal-title").html(NXLANG.edit_sla_policy || 'Edit SLA Policy');

    // Load modal content
    $.ajax({
        url: url,
        success: function(response) {
            $("#commonModal .modal-body").html(response);
            $("#commonModal").modal("show");
        }
    });
}

/**
 * Toggle SLA policy active/inactive
 * @param {number} policyId - The policy ID to toggle
 * @param {boolean} isActive - New active state
 */
function toggleSLAPolicy(policyId, isActive) {
    $.ajax({
        url: '/whatsapp/sla/' + policyId + '/toggle',
        method: 'POST',
        data: {
            is_active: isActive,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $.notify({
                    message: response.message || NXLANG.request_has_been_completed
                }, {
                    type: 'success',
                    showProgressbar: false,
                    timer: 2000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
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

            // Revert toggle state
            $('#sla-' + policyId).prop('checked', !isActive);
        }
    });
}

/**
 * Delete SLA policy
 * @param {number} policyId - The policy ID to delete
 */
function deleteSLAPolicy(policyId) {
    if (!confirm(NXLANG.are_you_sure || 'Are you sure?')) {
        return;
    }

    $.ajax({
        url: '/whatsapp/sla/' + policyId,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $.notify({
                    message: NXLANG.request_has_been_completed
                }, {
                    type: 'success',
                    showProgressbar: false,
                    timer: 2000
                });

                // Reload page after short delay
                setTimeout(function() {
                    location.reload();
                }, 1000);
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
 * Open WhatsApp conversation for a ticket
 * @param {number} ticketId - The ticket ID to view
 */
function openWhatsappConversation(ticketId) {
    // Redirect to WhatsApp conversation page with ticket parameter
    window.location.href = '/whatsapp/conversations?ticket=' + ticketId;
}

/**
 * Auto-refresh page for SLA monitoring
 * Refreshes every 60 seconds to show updated at-risk and breached tickets
 */
document.addEventListener('DOMContentLoaded', function() {
    // Only auto-refresh if we're on the SLA management page
    if (window.location.pathname.includes('/whatsapp/sla')) {
        setInterval(function() {
            location.reload();
        }, 60000); // Refresh every 60 seconds
    }
});
