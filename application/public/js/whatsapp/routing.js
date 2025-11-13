/**
 * WhatsApp Routing & Assignment JavaScript
 * Handles routing strategy, assignment rules, and agent availability
 */

/**
 * Save routing strategy selection
 */
function saveRoutingStrategy() {
    const strategy = $('#routing_strategy').val();

    $.ajax({
        url: '/whatsapp/routing/save-strategy',
        method: 'POST',
        data: {
            strategy: strategy,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                NX.notification({
                    type: 'success',
                    message: NXLANG.settings_saved || 'Settings saved successfully'
                });
            }
        },
        error: function(xhr) {
            NX.notification({
                type: 'error',
                message: NXLANG.error_request_could_not_be_completed
            });
        }
    });
}

/**
 * Save all routing settings
 */
function saveRoutingSettings() {
    const formData = {
        routing_strategy: $('#routing_strategy').val(),
        max_concurrent_tickets: $('[name="max_concurrent_tickets"]').val(),
        auto_assign_new_tickets: $('#auto_assign_new_tickets').is(':checked'),
        reassign_on_offline: $('#reassign_on_offline').is(':checked'),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: '/whatsapp/routing/save-settings',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                NX.notification({
                    type: 'success',
                    message: NXLANG.settings_saved || 'Settings saved successfully'
                });
            }
        },
        error: function(xhr) {
            NX.notification({
                type: 'error',
                message: NXLANG.error_request_could_not_be_completed
            });
        }
    });
}

/**
 * Open modal to add new routing rule
 */
function addRoutingRule() {
    var url = '/whatsapp/routing/create';

    // Set modal title
    $(".modal-title").html(NXLANG.add_routing_rule || 'Add Routing Rule');

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
 * Open modal to edit routing rule
 * @param {number} ruleId - The rule ID to edit
 */
function editRoutingRule(ruleId) {
    var url = '/whatsapp/routing/' + ruleId + '/edit';

    // Set modal title
    $(".modal-title").html(NXLANG.edit_routing_rule || 'Edit Routing Rule');

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
 * Toggle routing rule active/inactive
 * @param {number} ruleId - The rule ID to toggle
 * @param {boolean} isActive - New active state
 */
function toggleRoutingRule(ruleId, isActive) {
    $.ajax({
        url: '/whatsapp/routing/' + ruleId + '/toggle',
        method: 'POST',
        data: {
            is_active: isActive,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                NX.notification({
                    type: 'success',
                    message: response.message || NXLANG.request_has_been_completed
                });
            }
        },
        error: function(xhr) {
            NX.notification({
                type: 'error',
                message: NXLANG.error_request_could_not_be_completed
            });

            // Revert toggle state
            $('#rule-' + ruleId).prop('checked', !isActive);
        }
    });
}

/**
 * Delete routing rule
 * @param {number} ruleId - The rule ID to delete
 */
function deleteRoutingRule(ruleId) {
    if (!confirm(NXLANG.are_you_sure || 'Are you sure?')) {
        return;
    }

    $.ajax({
        url: '/whatsapp/routing/' + ruleId,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                NX.notification({
                    type: 'success',
                    message: NXLANG.request_has_been_completed
                });

                // Reload page after short delay
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr) {
            NX.notification({
                type: 'error',
                message: NXLANG.error_request_could_not_be_completed
            });
        }
    });
}

/**
 * Toggle agent availability for ticket assignment
 * @param {number} agentId - The agent ID
 * @param {boolean} isAvailable - New availability state
 */
function toggleAgentAvailability(agentId, isAvailable) {
    $.ajax({
        url: '/whatsapp/routing/agent/' + agentId + '/availability',
        method: 'POST',
        data: {
            available: isAvailable,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                NX.notification({
                    type: 'success',
                    message: response.message || NXLANG.request_has_been_completed
                });
            }
        },
        error: function(xhr) {
            NX.notification({
                type: 'error',
                message: NXLANG.error_request_could_not_be_completed
            });

            // Revert toggle state
            $('#agent-available-' + agentId).prop('checked', !isAvailable);
        }
    });
}
