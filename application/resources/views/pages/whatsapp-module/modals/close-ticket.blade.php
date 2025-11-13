{{-- Close Ticket Modal with Type Selector --}}
<div class="modal fade" id="modal-close-ticket" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-close-circle"></i> {{ __('lang.close_ticket') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="form-close-ticket">
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" id="close-ticket-id">

                    {{-- Ticket Type Selector --}}
                    <div class="form-group">
                        <label for="close-ticket-type">{{ __('lang.ticket_type') }} <span class="text-danger">*</span></label>
                        <select class="form-control" name="ticket_type_id" id="close-ticket-type" required>
                            <option value="">{{ __('lang.select_ticket_type') }}</option>
                            @foreach($ticketTypes as $type)
                                <option value="{{ $type->whatsapptickettype_id }}">
                                    {{ $type->whatsapptickettype_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            {{ __('lang.ticket_type_help') }}
                        </small>
                    </div>

                    {{-- Closure Reason (Optional) --}}
                    <div class="form-group">
                        <label for="closure-reason">{{ __('lang.closure_reason') }} ({{ __('lang.optional') }})</label>
                        <textarea class="form-control" name="closure_reason" id="closure-reason" rows="3" placeholder="{{ __('lang.closure_reason_placeholder') }}"></textarea>
                    </div>

                    {{-- Send Closure Message --}}
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="send-closure-message" name="send_closure_message" value="1" checked>
                        <label class="custom-control-label" for="send-closure-message">
                            {{ __('lang.send_closure_message') }}
                        </label>
                        <small class="form-text text-muted">
                            {{ __('lang.send_closure_message_help') }}
                        </small>
                    </div>

                    {{-- Closure Message Preview --}}
                    <div id="closure-message-preview" class="alert alert-info mt-3" style="display: none;">
                        <strong>{{ __('lang.closure_message_preview') }}:</strong>
                        <div id="closure-message-content" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-close-circle"></i> {{ __('lang.close_ticket') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCloseTicketModal(ticketId) {
    $('#close-ticket-id').val(ticketId);
    $('#close-ticket-type').val('');
    $('#closure-reason').val('');
    $('#send-closure-message').prop('checked', true);
    $('#closure-message-preview').hide();
    $('#modal-close-ticket').modal('show');

    // Load closure message preview
    $.get(`/whatsapp/tickets/${ticketId}/closure-message`, function(response) {
        if (response.closure_message) {
            $('#closure-message-content').text(response.closure_message);
            $('#closure-message-preview').show();
        }
    });
}

$('#form-close-ticket').submit(function(e) {
    e.preventDefault();

    const $btn = $(this).find('button[type="submit"]');
    const originalText = $btn.html();

    $btn.html('<i class="mdi mdi-loading mdi-spin"></i> {{ __("lang.closing") }}...').prop('disabled', true);

    $.ajax({
        url: '/whatsapp/tickets/close',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#modal-close-ticket').modal('hide');
                NioApp.Toast('{{ __("lang.ticket_closed_successfully") }}', 'success');

                // Redirect or reload
                setTimeout(function() {
                    window.location.href = '/whatsapp/conversations';
                }, 1000);
            }
        },
        error: function(xhr) {
            NioApp.Toast(xhr.responseJSON?.message || '{{ __("lang.error_closing_ticket") }}', 'error');
        },
        complete: function() {
            $btn.html(originalText).prop('disabled', false);
        }
    });
});
</script>

<style>
#modal-close-ticket .modal-body {
    padding: 20px;
}

#modal-close-ticket .form-group label {
    font-weight: 600;
}

#closure-message-preview {
    border-left: 4px solid #17a2b8;
}

#closure-message-content {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
    font-style: italic;
}
</style>
