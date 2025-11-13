<!--WhatsApp Settings Component-->
<div class="row">
    <div class="col-lg-12">

        <!--section: general settings-->
        <div class="card">
            <div class="card-header">
                <h5>{{ cleanLang(__('lang.general_settings')) }}</h5>
            </div>
            <div class="card-body">

                <!--auto assign-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.auto_assign_tickets')) }}</label>
                    <div class="col-sm-8">
                        <select class="select2-basic form-control form-control-sm" name="auto_assign_tickets">
                            <option value="no" {{ config('whatsapp.auto_assign_tickets') == 'no' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.no')) }}
                            </option>
                            <option value="round_robin" {{ config('whatsapp.auto_assign_tickets') == 'round_robin' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.round_robin')) }}
                            </option>
                            <option value="least_active" {{ config('whatsapp.auto_assign_tickets') == 'least_active' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.least_active')) }}
                            </option>
                        </select>
                        <small class="form-text text-muted">{{ cleanLang(__('lang.auto_assign_tickets_help')) }}</small>
                    </div>
                </div>

                <!--auto close tickets-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.auto_close_tickets_after_days')) }}</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control form-control-sm" name="auto_close_days"
                            value="{{ config('whatsapp.auto_close_days') ?? 7 }}" min="0">
                        <small class="form-text text-muted">{{ cleanLang(__('lang.auto_close_tickets_help')) }}</small>
                    </div>
                </div>

                <!--notification settings-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.email_notifications')) }}</label>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="notify_new_ticket" id="notify_new_ticket"
                                {{ config('whatsapp.notify_new_ticket') ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_ticket">
                                {{ cleanLang(__('lang.notify_on_new_ticket')) }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="notify_new_message" id="notify_new_message"
                                {{ config('whatsapp.notify_new_message') ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_message">
                                {{ cleanLang(__('lang.notify_on_new_message')) }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="notify_assigned" id="notify_assigned"
                                {{ config('whatsapp.notify_assigned') ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_assigned">
                                {{ cleanLang(__('lang.notify_when_assigned')) }}
                            </label>
                        </div>
                    </div>
                </div>

                <!--default priority-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.default_priority')) }}</label>
                    <div class="col-sm-8">
                        <select class="select2-basic form-control form-control-sm" name="default_priority">
                            <option value="low" {{ config('whatsapp.default_priority') == 'low' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.low')) }}
                            </option>
                            <option value="normal" {{ config('whatsapp.default_priority') == 'normal' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.normal')) }}
                            </option>
                            <option value="high" {{ config('whatsapp.default_priority') == 'high' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.high')) }}
                            </option>
                            <option value="urgent" {{ config('whatsapp.default_priority') == 'urgent' ? 'selected' : '' }}>
                                {{ cleanLang(__('lang.urgent')) }}
                            </option>
                        </select>
                    </div>
                </div>

                <!--message retention-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.message_retention_days')) }}</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control form-control-sm" name="message_retention_days"
                            value="{{ config('whatsapp.message_retention_days') ?? 365 }}" min="0">
                        <small class="form-text text-muted">{{ cleanLang(__('lang.message_retention_help')) }}</small>
                    </div>
                </div>

            </div>
        </div>

        <!--section: webhook settings-->
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ cleanLang(__('lang.webhook_settings')) }}</h5>
            </div>
            <div class="card-body">

                <!--webhook url-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.webhook_url')) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" readonly
                            value="{{ url('/webhooks/whatsapp') }}">
                        <small class="form-text text-muted">{{ cleanLang(__('lang.webhook_url_help')) }}</small>
                    </div>
                </div>

                <!--webhook secret-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.webhook_secret')) }}</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="webhook-secret" readonly
                                value="{{ config('whatsapp.webhook_secret') ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-secondary" type="button" onclick="generateWebhookSecret()">
                                    <i class="ti-reload"></i> {{ cleanLang(__('lang.regenerate')) }}
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">{{ cleanLang(__('lang.webhook_secret_help')) }}</small>
                    </div>
                </div>

            </div>
        </div>

        <!--section: appearance-->
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ cleanLang(__('lang.appearance')) }}</h5>
            </div>
            <div class="card-body">

                <!--brand color-->
                <div class="form-group row">
                    <label class="col-sm-4 control-label col-form-label">{{ cleanLang(__('lang.brand_color')) }}</label>
                    <div class="col-sm-8">
                        <input type="color" class="form-control form-control-sm" name="brand_color"
                            value="{{ config('whatsapp.brand_color') ?? '#25D366' }}">
                        <small class="form-text text-muted">{{ cleanLang(__('lang.brand_color_help')) }}</small>
                    </div>
                </div>

            </div>
        </div>

        <!--save button-->
        <div class="text-right mt-3">
            <button type="submit" class="btn btn-danger" onclick="saveWhatsappSettings()">
                <i class="ti-check"></i> {{ cleanLang(__('lang.save_settings')) }}
            </button>
        </div>

    </div>
</div>

<script>
    function generateWebhookSecret() {
        // Generate a new webhook secret
        $.ajax({
            url: '/whatsapp/settings/generate-webhook-secret',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    document.getElementById('webhook-secret').value = response.data.secret;
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.webhook_secret_regenerated")) }}'
                    });
                }
            }
        });
    }

    function saveWhatsappSettings() {
        // Save WhatsApp settings
        const formData = new FormData();

        // Collect all form inputs
        $('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                if ($(this).attr('type') === 'checkbox') {
                    formData.append(name, $(this).is(':checked') ? '1' : '0');
                } else {
                    formData.append(name, $(this).val());
                }
            }
        });

        $.ajax({
            url: '/whatsapp/settings',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.settings_saved_successfully")) }}'
                    });
                }
            }
        });
    }
</script>
