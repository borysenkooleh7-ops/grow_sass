/**
 * WhatsApp Automation Rules JavaScript
 * Handles automation rule management, toggles, and actions
 */

/**
 * Toggle automation rule active/inactive
 * @param {number} ruleId - The rule ID to toggle
 * @param {boolean} isActive - New active state
 */
function toggleAutomationRule(ruleId, isActive) {
    $.ajax({
        url: '/whatsapp/automation/' + ruleId + '/toggle',
        method: 'POST',
        data: {
            is_active: isActive,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Show success notification
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
            // Show error notification
            $.notify({
                message: NXLANG.error_request_could_not_be_completed
            }, {
                type: 'danger',
                showProgressbar: false,
                timer: 4000
            });

            // Revert toggle state
            $('#rule-toggle-' + ruleId).prop('checked', !isActive);
        }
    });
}

/**
 * Duplicate an existing automation rule
 * @param {number} ruleId - The rule ID to duplicate
 */
function duplicateAutomationRule(ruleId) {
    $.ajax({
        url: '/whatsapp/automation/' + ruleId + '/duplicate',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $.notify({
                    message: NXLANG.rule_duplicated || 'Rule duplicated successfully'
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
 * View automation rule execution logs
 * @param {number} ruleId - The rule ID to view logs for
 */
function viewRuleLog(ruleId) {
    var url = '/whatsapp/automation/' + ruleId + '/logs';

    // Set modal title
    $(".modal-title").html(NXLANG.automation_logs || 'Automation Logs');

    // Load modal content
    $.ajax({
        url: url,
        success: function(response) {
            $("#commonModal .modal-body").html(response);
            $("#commonModal").modal("show");
        },
        error: function(xhr) {
            alert('Failed to load logs');
        }
    });
}
