<!--WhatsApp Connection Settings Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--connection name-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.connection_name')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" id="connection_name" name="connection_name"
                    value="{{ $connection->connection_name ?? '' }}">
            </div>
        </div>

        <!--connection type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.connection_type')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="connection_type" name="connection_type">
                    <option value="baileys" {{ runtimePreselected('baileys', $connection->connection_type ?? 'baileys') }}>
                        Baileys (QR Code)
                    </option>
                    <option value="twilio" {{ runtimePreselected('twilio', $connection->connection_type ?? '') }}>
                        Twilio
                    </option>
                    <option value="360dialog" {{ runtimePreselected('360dialog', $connection->connection_type ?? '') }}>
                        360Dialog
                    </option>
                    <option value="gupshup" {{ runtimePreselected('gupshup', $connection->connection_type ?? '') }}>
                        Gupshup
                    </option>
                </select>
            </div>
        </div>

        <!--phone number-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.phone_number')) }}</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number"
                    value="{{ $connection->phone_number ?? '' }}" placeholder="+1234567890">
                <small class="form-text text-muted">{{ cleanLang(__('lang.leave_blank_for_baileys')) }}</small>
            </div>
        </div>

        <!--api credentials (conditional)-->
        <div id="api-credentials-section" style="display: none;">
            <div class="form-group row">
                <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.api_key')) }}</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control form-control-sm" id="api_key" name="api_key"
                        value="{{ $connection->connection_data['api_key'] ?? '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.api_secret')) }}</label>
                <div class="col-sm-12">
                    <input type="password" class="form-control form-control-sm" id="api_secret" name="api_secret"
                        value="{{ $connection->connection_data['api_secret'] ?? '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.webhook_url')) }}</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control form-control-sm" id="webhook_url" name="webhook_url"
                        value="{{ $connection->connection_data['webhook_url'] ?? '' }}">
                </div>
            </div>
        </div>

        <!--QR code section (for Baileys)-->
        <div id="qr-code-section" style="display: none;">
            <div class="form-group row">
                <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.qr_code')) }}</label>
                <div class="col-sm-12 text-center">
                    @if(isset($connection->qr_code))
                        <img src="{{ $connection->qr_code }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                        <p class="text-muted mt-2">{{ cleanLang(__('lang.scan_qr_with_whatsapp')) }}</p>
                    @else
                        <div class="alert alert-info">
                            {{ cleanLang(__('lang.qr_code_will_appear_after_save')) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!--status-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.status')) }}</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="status" name="status">
                    <option value="disconnected" {{ runtimePreselected('disconnected', $connection->status ?? 'disconnected') }}>
                        {{ cleanLang(__('lang.disconnected')) }}
                    </option>
                    <option value="connecting" {{ runtimePreselected('connecting', $connection->status ?? '') }}>
                        {{ cleanLang(__('lang.connecting')) }}
                    </option>
                    <option value="connected" {{ runtimePreselected('connected', $connection->status ?? '') }}>
                        {{ cleanLang(__('lang.connected')) }}
                    </option>
                    <option value="error" {{ runtimePreselected('error', $connection->status ?? '') }}>
                        {{ cleanLang(__('lang.error')) }}
                    </option>
                </select>
            </div>
        </div>

        <!--is active-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="is_active" name="is_active" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($connection->is_active ?? true) }}>
                <label for="is_active">{{ cleanLang(__('lang.active')) }}</label>
            </div>
        </div>

        <!--hidden fields-->
        @if(isset($connection->id))
        <input type="hidden" name="connection_id" value="{{ $connection->id }}">
        @endif

    </div>
</div>

<script>
    // Show/hide sections based on connection type
    $(document).ready(function() {
        $('#connection_type').on('change', function() {
            var type = $(this).val();
            if (type === 'baileys') {
                $('#api-credentials-section').hide();
                $('#qr-code-section').show();
            } else {
                $('#api-credentials-section').show();
                $('#qr-code-section').hide();
            }
        });

        // Trigger on page load
        $('#connection_type').trigger('change');
    });
</script>
