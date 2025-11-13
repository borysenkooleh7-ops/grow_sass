/**
 * WhatsApp Chatbot Builder JavaScript
 * Handles chatbot configuration, flow management, and testing
 */

/**
 * Toggle chatbot enabled/disabled
 * @param {boolean} enabled - New enabled state
 */
function toggleChatbot(enabled) {
    $.ajax({
        url: '/whatsapp/chatbot/toggle',
        method: 'POST',
        data: {
            enabled: enabled,
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
            $('#chatbot-enabled').prop('checked', !enabled);
        }
    });
}

/**
 * Save chatbot settings
 */
function saveChatbotSettings() {
    const settings = {
        welcome_message: $('#chatbot-welcome-message').val(),
        fallback_message: $('#chatbot-fallback-message').val(),
        handoff_human: $('#chatbot_handoff_human').is(':checked'),
        business_hours_only: $('#chatbot_business_hours_only').is(':checked'),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: '/whatsapp/chatbot/settings',
        method: 'POST',
        data: settings,
        success: function(response) {
            if (response.success) {
                $.notify({
                    message: NXLANG.settings_saved || 'Settings saved successfully'
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
        }
    });
}

/**
 * Open modal to create new chatbot flow
 */
function createChatbotFlow() {
    var url = '/whatsapp/chatbot/flows/create';

    // Set modal title
    $(".modal-title").html(NXLANG.create_chatbot_flow || 'Create Chatbot Flow');

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
 * Open modal to edit chatbot flow
 * @param {number} flowId - The flow ID to edit
 */
function editChatbotFlow(flowId) {
    var url = '/whatsapp/chatbot/flows/' + flowId + '/edit';

    // Set modal title
    $(".modal-title").html(NXLANG.edit_chatbot_flow || 'Edit Chatbot Flow');

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
 * Open modal to test chatbot flow
 * @param {number} flowId - The flow ID to test
 */
function testChatbotFlow(flowId) {
    var url = '/whatsapp/chatbot/flows/' + flowId + '/test';

    // Set modal title
    $(".modal-title").html(NXLANG.test_chatbot_flow || 'Test Chatbot Flow');

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
 * Duplicate an existing chatbot flow
 * @param {number} flowId - The flow ID to duplicate
 */
function duplicateChatbotFlow(flowId) {
    $.ajax({
        url: '/whatsapp/chatbot/flows/' + flowId + '/duplicate',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $.notify({
                    message: NXLANG.flow_duplicated || 'Flow duplicated successfully'
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
 * Delete chatbot flow
 * @param {number} flowId - The flow ID to delete
 */
function deleteChatbotFlow(flowId) {
    if (!confirm(NXLANG.are_you_sure || 'Are you sure?')) {
        return;
    }

    $.ajax({
        url: '/whatsapp/chatbot/flows/' + flowId,
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
