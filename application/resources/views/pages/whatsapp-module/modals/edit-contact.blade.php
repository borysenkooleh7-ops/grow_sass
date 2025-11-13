{{-- Edit Contact Modal --}}
<div class="modal fade" id="modal-edit-contact" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-account-edit"></i> {{ __('lang.edit_contact') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="form-edit-contact">
                <div class="modal-body">
                    <input type="hidden" name="contact_id" id="edit-contact-id">

                    {{-- Contact Name --}}
                    <div class="form-group">
                        <label for="edit-contact-name">{{ __('lang.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit-contact-name" required>
                    </div>

                    {{-- Company --}}
                    <div class="form-group">
                        <label for="edit-contact-company">{{ __('lang.company') }}</label>
                        <input type="text" class="form-control" name="company" id="edit-contact-company" placeholder="{{ __('lang.company_name') }}">
                    </div>

                    {{-- Phone Number (Read-only) --}}
                    <div class="form-group">
                        <label for="edit-contact-phone">{{ __('lang.phone_number') }}</label>
                        <input type="text" class="form-control" id="edit-contact-phone" readonly>
                    </div>

                    {{-- Tags --}}
                    <div class="form-group">
                        <label for="edit-contact-tags">{{ __('lang.tags') }}</label>
                        <select class="form-control select2" name="tags[]" id="edit-contact-tags" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->whatsapptag_id }}">
                                    {{ $tag->whatsapptag_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            {{ __('lang.select_multiple_tags') }}
                        </small>
                    </div>

                    {{-- Link to Client --}}
                    <div class="form-group">
                        <label for="edit-contact-client">{{ __('lang.link_to_client') }}</label>
                        <select class="form-control select2" name="client_id" id="edit-contact-client">
                            <option value="">{{ __('lang.no_client_linked') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->client_id }}">
                                    {{ $client->client_company_name ?: $client->client_firstname . ' ' . $client->client_lastname }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            {{ __('lang.link_to_client_help') }}
                        </small>
                    </div>

                    {{-- Notes --}}
                    <div class="form-group">
                        <label for="edit-contact-notes">{{ __('lang.notes') }}</label>
                        <textarea class="form-control" name="notes" id="edit-contact-notes" rows="3" placeholder="{{ __('lang.contact_notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="mdi mdi-content-save"></i> {{ __('lang.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showEditContactModal(contactId) {
    // Load contact data
    $.get(`/whatsapp/contacts/${contactId}/edit`, function(response) {
        const contact = response.contact;

        $('#edit-contact-id').val(contact.whatsappcontact_id);
        $('#edit-contact-name').val(contact.whatsappcontact_name);
        $('#edit-contact-company').val(contact.whatsappcontact_company);
        $('#edit-contact-phone').val(contact.whatsappcontact_phone);
        $('#edit-contact-notes').val(contact.whatsappcontact_notes);

        // Set client
        $('#edit-contact-client').val(contact.whatsappcontact_clientid).trigger('change');

        // Set tags
        const tags = contact.tags_array || [];
        $('#edit-contact-tags').val(tags).trigger('change');

        $('#modal-edit-contact').modal('show');
    });
}

$('#form-edit-contact').submit(function(e) {
    e.preventDefault();

    const contactId = $('#edit-contact-id').val();
    const $btn = $(this).find('button[type="submit"]');
    const originalText = $btn.html();

    $btn.html('<i class="mdi mdi-loading mdi-spin"></i> {{ __("lang.saving") }}...').prop('disabled', true);

    $.ajax({
        url: `/whatsapp/contacts/${contactId}/update`,
        method: 'PUT',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#modal-edit-contact').modal('hide');
                NioApp.Toast('{{ __("lang.contact_updated") }}', 'success');

                // Reload page to show updates
                setTimeout(function() {
                    location.reload();
                }, 500);
            }
        },
        error: function(xhr) {
            NioApp.Toast(xhr.responseJSON?.message || '{{ __("lang.error_updating_contact") }}', 'error');
        },
        complete: function() {
            $btn.html(originalText).prop('disabled', false);
        }
    });
});

// Initialize Select2 when modal is shown
$('#modal-edit-contact').on('shown.bs.modal', function() {
    $('#edit-contact-tags, #edit-contact-client').select2({
        dropdownParent: $('#modal-edit-contact'),
        width: '100%'
    });
});
</script>

<style>
#modal-edit-contact .modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

#modal-edit-contact .form-group label {
    font-weight: 600;
}

#modal-edit-contact .select2-container {
    width: 100% !important;
}
</style>
