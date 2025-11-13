/**
 * WhatsApp Templates Component JavaScript
 * Handles template selection and usage
 */

/**
 * Toggle template visibility
 * @param {number} templateId - The template ID to toggle
 */
function toggleTemplate(templateId) {
    const templateBody = document.getElementById('template-body-' + templateId);
    if (templateBody.style.display === 'none') {
        // Hide all other templates
        document.querySelectorAll('.template-body').forEach(function(el) {
            el.style.display = 'none';
        });
        // Show this template
        templateBody.style.display = 'block';
    } else {
        templateBody.style.display = 'none';
    }
}

/**
 * Use selected template in message composer
 * @param {number} templateId - The template ID to use
 */
function useTemplate(templateId) {
    // Get the template content
    $.ajax({
        url: '/whatsapp/templates/' + templateId,
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
 * Search templates by title
 */
function searchTemplates() {
    const searchValue = document.getElementById('template-search').value.toLowerCase();
    const templateItems = document.querySelectorAll('.template-item');

    templateItems.forEach(function(item) {
        const title = item.getAttribute('data-title');
        if (title.includes(searchValue)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

/**
 * Open modal to manage templates
 */
function manageTemplates() {
    // Open modal to manage templates
    NX.loadModal({
        url: '/whatsapp/templates',
        title: NXLANG.manage_templates || 'Manage Templates',
        size: 'large'
    });
}

// ========================================
// TEMPLATES MANAGEMENT FUNCTIONS
// ========================================

$(document).ready(function() {

    /**
     * Preview template in modal
     */
    window.previewTemplate = function(templateId) {
        NProgress.start();
        $.ajax({
            url: '/whatsapp/templates/' + templateId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    // Show preview modal
                    showTemplatePreview(response.template);
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error loading template preview'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Show template preview modal
     */
    function showTemplatePreview(template) {
        var modalHtml = `
            <div class="modal fade" id="templatePreviewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Template Preview: ${template.whatsapptemplatemain_title}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Category:</strong> <span class="badge badge-primary">${template.whatsapptemplatemain_category}</span></p>
                                    <p><strong>Language:</strong> ${template.whatsapptemplatemain_language}</p>
                                    <p><strong>Status:</strong> ${template.whatsapptemplatemain_is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Created:</strong> ${new Date(template.created_at).toLocaleDateString()}</p>
                                    <p><strong>Created By:</strong> ${template.creator ? template.creator.full_name : 'N/A'}</p>
                                </div>
                            </div>
                            <hr>
                            <h6>Message:</h6>
                            <div class="p-3 bg-light rounded">
                                <pre style="white-space: pre-wrap;">${template.whatsapptemplatemain_message}</pre>
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
        $('#templatePreviewModal').remove();
        // Append and show new modal
        $('body').append(modalHtml);
        $('#templatePreviewModal').modal('show');
    }

    /**
     * Edit template - open modal with edit form
     */
    window.editTemplate = function(templateId) {
        NProgress.start();
        $.ajax({
            url: '/whatsapp/templates/' + templateId + '/edit',
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
                    message: 'Error loading template for editing'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Duplicate template
     */
    window.duplicateTemplate = function(templateId) {
        if (!confirm('Are you sure you want to duplicate this template?')) {
            return;
        }

        NProgress.start();
        $.ajax({
            url: '/whatsapp/templates/' + templateId + '/duplicate',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    $.notify({
                        message: response.message || 'Template duplicated successfully'
                    }, {
                        type: 'success',
                        showProgressbar: false,
                        timer: 2000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        }
                    });
                    // Reload page after short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error duplicating template'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Delete template
     */
    window.deleteTemplate = function(templateId) {
        if (!confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
            return;
        }

        NProgress.start();
        $.ajax({
            url: '/whatsapp/templates/' + templateId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    NProgress.done();
                    $.notify({
                        message: response.message || 'Template deleted successfully'
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
                    $('button[data-id="' + templateId + '"]').closest('tr').fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            },
            error: function(xhr) {
                NProgress.done();
                $.notify({
                    message: 'Error deleting template'
                }, {
                    type: 'danger',
                    showProgressbar: false,
                    timer: 4000
                });
            }
        });
    };

    /**
     * Event listeners for template buttons
     */
    $(document).on('click', '.btn-preview-template', function() {
        var templateId = $(this).data('id');
        previewTemplate(templateId);
    });

    $(document).on('click', '.btn-edit-template', function() {
        var templateId = $(this).data('id');
        editTemplate(templateId);
    });

    $(document).on('click', '.btn-duplicate-template', function() {
        var templateId = $(this).data('id');
        duplicateTemplate(templateId);
    });

    $(document).on('click', '.btn-delete-template', function() {
        var templateId = $(this).data('id');
        deleteTemplate(templateId);
    });

});
