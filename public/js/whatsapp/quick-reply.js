/**
 * WhatsApp Quick Reply Component JavaScript
 * Handles inserting quick replies into message composer
 */

/**
 * Insert quick reply message into composer
 * @param {number} replyId - The quick reply ID
 */
function insertQuickReply(replyId) {
    // Get the reply content
    $.ajax({
        url: '/whatsapp/quick-replies/' + replyId,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Insert into message input
                const messageInput = document.getElementById('message-input');
                if (messageInput) {
                    messageInput.value = response.data.message;
                    messageInput.focus();
                }
            }
        }
    });
}

/**
 * Open modal to add new quick reply
 */
function addQuickReply() {
    // Open modal to add new quick reply
    NX.loadModal({
        url: '/whatsapp/quick-replies/create',
        title: NXLANG.add_quick_reply || 'Add Quick Reply',
        size: 'medium'
    });
}

// ========================================
// QUICK REPLIES MANAGEMENT FUNCTIONS
// ========================================

$(document).ready(function() {

    /**
     * Edit quick reply - open modal with edit form
     */
    window.editQuickReply = function(replyId) {
        NProgress.start();
        $.ajax({
            url: '/whatsapp/quick-replies/' + replyId + '/edit',
            method: 'GET',
            success: function(response) {
                NProgress.done();
                // Load modal content
                $('#commonModal .modal-content').html(response);
                $('#commonModal').modal('show');
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error loading quick reply for editing'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Delete quick reply
     */
    window.deleteQuickReply = function(replyId) {
        if (!confirm('Are you sure you want to delete this quick reply? This action cannot be undone.')) {
            return;
        }

        NProgress.start();
        $.ajax({
            url: '/whatsapp/quick-replies/' + replyId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    $.notify({
                        message: response.message || 'Quick reply deleted successfully'
                    }, {
                        type: 'success',
                        showProgressbar: false,
                        timer: 2000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        }
                    });
                    // Remove the table row
                    $('button[data-id="' + replyId + '"]').closest('tr').fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error deleting quick reply'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Event listeners for quick reply buttons
     */
    $(document).on('click', '.btn-edit-quick-reply', function() {
        var replyId = $(this).data('id');
        editQuickReply(replyId);
    });

    $(document).on('click', '.btn-delete-quick-reply', function() {
        var replyId = $(this).data('id');
        deleteQuickReply(replyId);
    });

});
