<!--Add/Edit Automation Rule-->
<div class="row">
    <div class="col-lg-12">

        <!--rule name-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.rule_name')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" name="rule_name"
                    value="{{ $rule->name ?? '' }}" placeholder="{{ cleanLang(__('lang.enter_rule_name')) }}">
            </div>
        </div>

        <!--description-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.description')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="2" name="rule_description">{{ $rule->description ?? '' }}</textarea>
            </div>
        </div>

        <hr>

        <!--trigger section-->
        <h5 class="mb-3"><i class="ti-bolt"></i> {{ cleanLang(__('lang.when_should_this_rule_trigger')) }}</h5>

        <!--trigger type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.trigger_event')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" name="trigger_type" id="trigger_type" onchange="updateTriggerConditions()">
                    <option value="new_message" {{ runtimePreselected('new_message', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.new_message_received')) }}
                    </option>
                    <option value="new_ticket" {{ runtimePreselected('new_ticket', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.new_ticket_created')) }}
                    </option>
                    <option value="ticket_status_change" {{ runtimePreselected('ticket_status_change', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.ticket_status_changed')) }}
                    </option>
                    <option value="keyword_match" {{ runtimePreselected('keyword_match', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.message_contains_keyword')) }}
                    </option>
                    <option value="business_hours" {{ runtimePreselected('business_hours', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.outside_business_hours')) }}
                    </option>
                    <option value="no_response" {{ runtimePreselected('no_response', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.no_response_after_x_minutes')) }}
                    </option>
                    <option value="ticket_assigned" {{ runtimePreselected('ticket_assigned', $rule->trigger_type ?? '') }}>
                        {{ cleanLang(__('lang.ticket_assigned')) }}
                    </option>
                </select>
            </div>
        </div>

        <!--trigger conditions-->
        <div class="form-group row" id="trigger-conditions-section">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.additional_conditions')) }}</label>
            <div class="col-sm-12">
                <div id="conditions-container">
                    @if(isset($rule->trigger_conditions))
                        @foreach(json_decode($rule->trigger_conditions, true) as $index => $condition)
                            <div class="condition-row mb-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" name="condition_field[]">
                                            <option value="message_text" {{ $condition['field'] == 'message_text' ? 'selected' : '' }}>{{ cleanLang(__('lang.message_text')) }}</option>
                                            <option value="client_type" {{ $condition['field'] == 'client_type' ? 'selected' : '' }}>{{ cleanLang(__('lang.client_type')) }}</option>
                                            <option value="priority" {{ $condition['field'] == 'priority' ? 'selected' : '' }}>{{ cleanLang(__('lang.priority')) }}</option>
                                            <option value="tag" {{ $condition['field'] == 'tag' ? 'selected' : '' }}>{{ cleanLang(__('lang.tag')) }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" name="condition_operator[]">
                                            <option value="contains" {{ $condition['operator'] == 'contains' ? 'selected' : '' }}>{{ cleanLang(__('lang.contains')) }}</option>
                                            <option value="equals" {{ $condition['operator'] == 'equals' ? 'selected' : '' }}>{{ cleanLang(__('lang.equals')) }}</option>
                                            <option value="not_equals" {{ $condition['operator'] == 'not_equals' ? 'selected' : '' }}>{{ cleanLang(__('lang.not_equals')) }}</option>
                                            <option value="starts_with" {{ $condition['operator'] == 'starts_with' ? 'selected' : '' }}>{{ cleanLang(__('lang.starts_with')) }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="condition_value[]" value="{{ $condition['value'] }}" placeholder="{{ cleanLang(__('lang.value')) }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeCondition(this)">
                                            <i class="ti-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addCondition()">
                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_condition')) }}
                </button>
            </div>
        </div>

        <hr>

        <!--actions section-->
        <h5 class="mb-3"><i class="ti-settings"></i> {{ cleanLang(__('lang.what_should_happen')) }}</h5>

        <div id="actions-container">
            @if(isset($rule->actions))
                @foreach(json_decode($rule->actions, true) as $index => $action)
                    <div class="action-row card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>{{ cleanLang(__('lang.action_type')) }}</label>
                                    <select class="form-control form-control-sm" name="action_type[]" onchange="updateActionFields(this)">
                                        <option value="send_message" {{ $action['type'] == 'send_message' ? 'selected' : '' }}>{{ cleanLang(__('lang.send_auto_reply')) }}</option>
                                        <option value="assign_to" {{ $action['type'] == 'assign_to' ? 'selected' : '' }}>{{ cleanLang(__('lang.assign_to_user')) }}</option>
                                        <option value="change_status" {{ $action['type'] == 'change_status' ? 'selected' : '' }}>{{ cleanLang(__('lang.change_ticket_status')) }}</option>
                                        <option value="add_tag" {{ $action['type'] == 'add_tag' ? 'selected' : '' }}>{{ cleanLang(__('lang.add_tag')) }}</option>
                                        <option value="set_priority" {{ $action['type'] == 'set_priority' ? 'selected' : '' }}>{{ cleanLang(__('lang.set_priority')) }}</option>
                                        <option value="send_email" {{ $action['type'] == 'send_email' ? 'selected' : '' }}>{{ cleanLang(__('lang.send_email_notification')) }}</option>
                                        <option value="webhook" {{ $action['type'] == 'webhook' ? 'selected' : '' }}>{{ cleanLang(__('lang.trigger_webhook')) }}</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ cleanLang(__('lang.action_value')) }}</label>
                                    <textarea class="form-control form-control-sm" rows="2" name="action_value[]" placeholder="{{ cleanLang(__('lang.enter_action_details')) }}">{{ $action['value'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-1 text-right">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-danger btn-block" onclick="removeAction(this)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-sm btn-primary mb-3" onclick="addAction()">
            <i class="ti-plus"></i> {{ cleanLang(__('lang.add_action')) }}
        </button>

        <hr>

        <!--rule options-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12">
                <input type="checkbox" id="is_active" name="is_active" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($rule->is_active ?? true) }}>
                <label for="is_active">{{ cleanLang(__('lang.activate_rule')) }}</label>
            </div>
        </div>

        <div class="form-group form-group-checkbox row">
            <div class="col-12">
                <input type="checkbox" id="stop_processing" name="stop_processing" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($rule->stop_processing ?? false) }}>
                <label for="stop_processing">{{ cleanLang(__('lang.stop_processing_other_rules')) }}</label>
                <small class="form-text text-muted">{{ cleanLang(__('lang.stop_processing_help')) }}</small>
            </div>
        </div>

        @if(isset($rule->id))
        <input type="hidden" name="rule_id" value="{{ $rule->id }}">
        @endif

    </div>
</div>

<!--buttons-->
<div class="text-right">
    <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
        data-url="{{ $url ?? '' }}" data-loading-target="" data-ajax-type="POST" data-form-id="commonModalForm"
        data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_rule')) }}</button>
</div>

<script>
    function addCondition() {
        const html = `
            <div class="condition-row mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control form-control-sm" name="condition_field[]">
                            <option value="message_text">{{ cleanLang(__('lang.message_text')) }}</option>
                            <option value="client_type">{{ cleanLang(__('lang.client_type')) }}</option>
                            <option value="priority">{{ cleanLang(__('lang.priority')) }}</option>
                            <option value="tag">{{ cleanLang(__('lang.tag')) }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm" name="condition_operator[]">
                            <option value="contains">{{ cleanLang(__('lang.contains')) }}</option>
                            <option value="equals">{{ cleanLang(__('lang.equals')) }}</option>
                            <option value="not_equals">{{ cleanLang(__('lang.not_equals')) }}</option>
                            <option value="starts_with">{{ cleanLang(__('lang.starts_with')) }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm" name="condition_value[]" placeholder="{{ cleanLang(__('lang.value')) }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeCondition(this)">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#conditions-container').append(html);
    }

    function removeCondition(btn) {
        $(btn).closest('.condition-row').remove();
    }

    function addAction() {
        const html = `
            <div class="action-row card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>{{ cleanLang(__('lang.action_type')) }}</label>
                            <select class="form-control form-control-sm" name="action_type[]">
                                <option value="send_message">{{ cleanLang(__('lang.send_auto_reply')) }}</option>
                                <option value="assign_to">{{ cleanLang(__('lang.assign_to_user')) }}</option>
                                <option value="change_status">{{ cleanLang(__('lang.change_ticket_status')) }}</option>
                                <option value="add_tag">{{ cleanLang(__('lang.add_tag')) }}</option>
                                <option value="set_priority">{{ cleanLang(__('lang.set_priority')) }}</option>
                                <option value="send_email">{{ cleanLang(__('lang.send_email_notification')) }}</option>
                                <option value="webhook">{{ cleanLang(__('lang.trigger_webhook')) }}</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label>{{ cleanLang(__('lang.action_value')) }}</label>
                            <textarea class="form-control form-control-sm" rows="2" name="action_value[]" placeholder="{{ cleanLang(__('lang.enter_action_details')) }}"></textarea>
                        </div>
                        <div class="col-md-1 text-right">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-danger btn-block" onclick="removeAction(this)">
                                <i class="ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#actions-container').append(html);
    }

    function removeAction(btn) {
        $(btn).closest('.action-row').remove();
    }

    function updateTriggerConditions() {
        // Show/hide conditions based on trigger type
    }
</script>
