/**
 * WhatsApp Broadcasts Component JavaScript
 * Handles broadcast creation, viewing, editing, and management
 */

// ========================================
// BROADCASTS MANAGEMENT FUNCTIONS
// ========================================

$(document).ready(function() {

    /**
     * View broadcast details in modal
     */
    window.viewBroadcast = function(broadcastId) {
        NProgress.start();
        $.ajax({
            url: '/whatsapp/broadcasts/' + broadcastId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    // Show broadcast details modal
                    showBroadcastDetails(response.broadcast);
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error loading broadcast details'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Show broadcast details modal
     */
    function showBroadcastDetails(broadcast) {
        var statusClass = broadcast.whatsappbroadcast_status === 'completed' ? 'success' :
                         broadcast.whatsappbroadcast_status === 'failed' ? 'danger' :
                         broadcast.whatsappbroadcast_status === 'processing' ? 'warning' : 'info';

        var modalHtml = `
            <div class="modal fade" id="broadcastDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Broadcast Details: ${broadcast.whatsappbroadcast_title}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> <span class="badge badge-${statusClass}">${broadcast.whatsappbroadcast_status}</span></p>
                                    <p><strong>Total Recipients:</strong> ${broadcast.whatsappbroadcast_total_recipients || 0}</p>
                                    <p><strong>Sent:</strong> ${broadcast.whatsappbroadcast_sent_count || 0}</p>
                                    <p><strong>Delivered:</strong> ${broadcast.whatsappbroadcast_delivered_count || 0}</p>
                                    <p><strong>Failed:</strong> ${broadcast.whatsappbroadcast_failed_count || 0}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Created:</strong> ${new Date(broadcast.created_at).toLocaleString()}</p>
                                    <p><strong>Scheduled For:</strong> ${broadcast.whatsappbroadcast_scheduled_at ? new Date(broadcast.whatsappbroadcast_scheduled_at).toLocaleString() : 'Sent immediately'}</p>
                                    <p><strong>Completed:</strong> ${broadcast.whatsappbroadcast_completed_at ? new Date(broadcast.whatsappbroadcast_completed_at).toLocaleString() : 'N/A'}</p>
                                </div>
                            </div>
                            <hr>
                            <h6>Message:</h6>
                            <div class="p-3 bg-light rounded">
                                <pre style="white-space: pre-wrap;">${broadcast.whatsappbroadcast_message}</pre>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#broadcastDetailsModal').remove();
        // Append and show new modal
        $('body').append(modalHtml);
        $('#broadcastDetailsModal').modal('show');
    }

    /**
     * Edit broadcast - open modal with edit form
     */
    window.editBroadcast = function(broadcastId) {
        NProgress.start();
        $.ajax({
            url: '/whatsapp/broadcasts/' + broadcastId + '/edit',
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
                    message: 'Error loading broadcast for editing'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Delete broadcast
     */
    window.deleteBroadcast = function(broadcastId) {
        if (!confirm('Are you sure you want to delete this broadcast? This action cannot be undone.')) {
            return;
        }

        NProgress.start();
        $.ajax({
            url: '/whatsapp/broadcasts/' + broadcastId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    $.notify({
                        message: response.message || 'Broadcast deleted successfully'
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
                    $('button[data-id="' + broadcastId + '"]').closest('tr').fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error deleting broadcast'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Event listeners for broadcast buttons
     */
    $(document).on('click', '.btn-view-broadcast', function() {
        var broadcastId = $(this).data('id');
        viewBroadcast(broadcastId);
    });

    $(document).on('click', '.btn-edit-broadcast', function() {
        var broadcastId = $(this).data('id');
        editBroadcast(broadcastId);
    });

    $(document).on('click', '.btn-delete-broadcast', function() {
        var broadcastId = $(this).data('id');
        deleteBroadcast(broadcastId);
    });

});
