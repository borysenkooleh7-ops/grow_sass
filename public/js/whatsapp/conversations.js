/**
 * WhatsApp Conversations Module
 * Handles initialization after form is loaded into modal
 */

/**
 * Initialize select2 dropdowns after conversation form is loaded
 */
function NXWhatsappConversationForm() {
    console.log('Initializing WhatsApp conversation form...');

    // Initialize basic select2 dropdowns
    if (typeof $('.select2-basic').select2 === 'function') {
        $('.select2-basic').select2({
            dropdownParent: $('#commonModal'),
            placeholder: NXLANG.select_option || 'Select...',
            width: '100%'
        });
    }

    // Initialize multiple select for tags
    if (typeof $('.select2-multiple-tags').select2 === 'function') {
        $('.select2-multiple-tags').select2({
            dropdownParent: $('#commonModal'),
            tags: true,
            tokenSeparators: [','],
            placeholder: NXLANG.select_tags || 'Select tags...',
            width: '100%'
        });
    }

    // Handle form submission via AJAX
    $('#commonModalForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submit intercepted, calling AJAX handler...');

        var $button = $('#commonModalSubmitButton');
        if (typeof nxAjaxUxRequest === 'function') {
            nxAjaxUxRequest($button);
        } else {
            console.error('nxAjaxUxRequest function not found');
        }

        return false;
    });

    // Also handle direct button click
    $('#commonModalSubmitButton').off('click').on('click', function(e) {
        // If button type is submit, form submit handler will handle it
        // This is just a fallback
        if ($(this).attr('type') !== 'submit') {
            e.preventDefault();
            console.log('Button click intercepted, calling AJAX handler...');

            if (typeof nxAjaxUxRequest === 'function') {
                nxAjaxUxRequest($(this));
            } else {
                console.error('nxAjaxUxRequest function not found');
            }

            return false;
        }
    });

    console.log('WhatsApp conversation form initialized successfully');
}
