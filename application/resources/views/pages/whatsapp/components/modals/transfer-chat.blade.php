<!--Transfer Chat Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--transfer to-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.transfer_to')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="transfer_to" name="transfer_to">
                    <option value="">{{ cleanLang(__('lang.select_agent')) }}</option>
                    @foreach($available_agents as $agent)
                        <option value="{{ $agent->id }}"
                            data-active-tickets="{{ $agent->active_tickets_count }}"
                            data-max-tickets="{{ $agent->max_tickets }}">
                            {{ $agent->first_name }} {{ $agent->last_name }}
                            @if($agent->id == auth()->id())
                                ({{ __('lang.me') }})
                            @endif
                            - {{ $agent->active_tickets_count }}/{{ $agent->max_tickets }} {{ __('lang.tickets') }}
                            @if(!$agent->is_online)
                                <span class="text-danger">({{ __('lang.offline') }})</span>
                            @endif
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">{{ cleanLang(__('lang.transfer_chat_help')) }}</small>
            </div>
        </div>

        <!--transfer reason-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.reason_for_transfer')) }}</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="transfer_reason" name="transfer_reason">
                    <option value="workload">{{ cleanLang(__('lang.workload_balancing')) }}</option>
                    <option value="expertise">{{ cleanLang(__('lang.requires_expertise')) }}</option>
                    <option value="language">{{ cleanLang(__('lang.language_barrier')) }}</option>
                    <option value="unavailable">{{ cleanLang(__('lang.agent_unavailable')) }}</option>
                    <option value="escalation">{{ cleanLang(__('lang.escalation')) }}</option>
                    <option value="other">{{ cleanLang(__('lang.other')) }}</option>
                </select>
            </div>
        </div>

        <!--transfer note-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.internal_note')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="3" name="transfer_note"
                    placeholder="{{ cleanLang(__('lang.add_note_for_receiving_agent')) }}"></textarea>
            </div>
        </div>

        <!--notify options-->
        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="notify_new_agent" name="notify_new_agent" checked>
                <label class="form-check-label" for="notify_new_agent">
                    {{ cleanLang(__('lang.notify_new_agent')) }}
                </label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="notify_customer" name="notify_customer">
                <label class="form-check-label" for="notify_customer">
                    {{ cleanLang(__('lang.notify_customer_about_transfer')) }}
                </label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="close_for_current_agent" name="close_for_current_agent">
                <label class="form-check-label" for="close_for_current_agent">
                    {{ cleanLang(__('lang.remove_from_my_active_tickets')) }}
                </label>
            </div>
        </div>

        <!--customer message-->
        <div class="form-group row" id="customer-message-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.message_to_customer')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="2" name="customer_message"
                    placeholder="{{ cleanLang(__('lang.optional_message_to_customer')) }}">{{ __('lang.default_transfer_message') }}</textarea>
            </div>
        </div>

        <!--current ticket info-->
        <div class="alert alert-info">
            <h6><strong>{{ cleanLang(__('lang.current_ticket_info')) }}</strong></h6>
            <div class="row">
                <div class="col-6">
                    <small>
                        <strong>{{ cleanLang(__('lang.subject')) }}:</strong> {{ $ticket->ticket_subject }}<br>
                        <strong>{{ cleanLang(__('lang.status')) }}:</strong> {{ runtimeLang($ticket->ticket_status) }}<br>
                        <strong>{{ cleanLang(__('lang.priority')) }}:</strong> {{ runtimeLang($ticket->priority) }}
                    </small>
                </div>
                <div class="col-6">
                    <small>
                        <strong>{{ cleanLang(__('lang.messages')) }}:</strong> {{ $ticket->messages_count }}<br>
                        <strong>{{ cleanLang(__('lang.created')) }}:</strong> {{ runtimeDateAgo($ticket->created_at) }}<br>
                        <strong>{{ cleanLang(__('lang.last_activity')) }}:</strong> {{ runtimeDateAgo($ticket->last_message_at) }}
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Show customer message section when notify customer is checked
        $('#notify_customer').on('change', function() {
            if ($(this).is(':checked')) {
                $('#customer-message-section').slideDown();
            } else {
                $('#customer-message-section').slideUp();
            }
        });

        // Highlight agents with low workload
        $('#transfer_to').on('change', function() {
            const selected = $(this).find(':selected');
            const activeTickets = selected.data('active-tickets');
            const maxTickets = selected.data('max-tickets');

            if (activeTickets >= maxTickets) {
                NX.notification({
                    type: 'warning',
                    message: '{{ cleanLang(__("lang.agent_at_capacity")) }}'
                });
            }
        });
    });
</script>
