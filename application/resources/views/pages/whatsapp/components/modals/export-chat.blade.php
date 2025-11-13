<!--Export Chat History Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--export type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.what_to_export')) }}*</label>
            <div class="col-sm-12">
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="export_type" id="export_current" value="current_ticket" checked>
                    <label class="form-check-label" for="export_current">
                        {{ cleanLang(__('lang.current_conversation')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="export_type" id="export_contact" value="contact_all">
                    <label class="form-check-label" for="export_contact">
                        {{ cleanLang(__('lang.all_conversations_with_contact')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="export_type" id="export_custom" value="custom_range">
                    <label class="form-check-label" for="export_custom">
                        {{ cleanLang(__('lang.custom_date_range')) }}
                    </label>
                </div>
            </div>
        </div>

        <!--date range (conditional)-->
        <div class="form-group row" id="date-range-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.date_range')) }}</label>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm pickadate" name="export_start_date" placeholder="{{ cleanLang(__('lang.start_date')) }}">
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm pickadate" name="export_end_date" placeholder="{{ cleanLang(__('lang.end_date')) }}">
            </div>
        </div>

        <!--export format-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.export_format')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" name="export_format" id="export_format">
                    <option value="pdf">PDF {{ cleanLang(__('lang.document')) }}</option>
                    <option value="html">HTML {{ cleanLang(__('lang.page')) }}</option>
                    <option value="txt">{{ cleanLang(__('lang.plain_text')) }}</option>
                    <option value="json">JSON {{ cleanLang(__('lang.data')) }}</option>
                    <option value="csv">CSV {{ cleanLang(__('lang.spreadsheet')) }}</option>
                </select>
            </div>
        </div>

        <!--include options-->
        <div class="form-group">
            <label>{{ cleanLang(__('lang.include_in_export')) }}</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="include_media" name="include_media" checked>
                <label class="form-check-label" for="include_media">
                    {{ cleanLang(__('lang.media_attachments')) }}
                </label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="include_timestamps" name="include_timestamps" checked>
                <label class="form-check-label" for="include_timestamps">
                    {{ cleanLang(__('lang.timestamps')) }}
                </label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="include_internal_notes" name="include_internal_notes">
                <label class="form-check-label" for="include_internal_notes">
                    {{ cleanLang(__('lang.internal_notes')) }}
                </label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="include_system_messages" name="include_system_messages">
                <label class="form-check-label" for="include_system_messages">
                    {{ cleanLang(__('lang.system_messages')) }}
                </label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="include_ticket_info" name="include_ticket_info" checked>
                <label class="form-check-label" for="include_ticket_info">
                    {{ cleanLang(__('lang.ticket_information')) }}
                </label>
            </div>
        </div>

        <!--additional options-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.additional_options')) }}</label>
            <div class="col-sm-12">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="email_export" name="email_export">
                    <label class="form-check-label" for="email_export">
                        {{ cleanLang(__('lang.email_export_to_me')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="password_protect" name="password_protect">
                    <label class="form-check-label" for="password_protect">
                        {{ cleanLang(__('lang.password_protect_export')) }}
                    </label>
                </div>
            </div>
        </div>

        <!--password field (conditional)-->
        <div class="form-group row" id="password-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.export_password')) }}</label>
            <div class="col-sm-12">
                <input type="password" class="form-control form-control-sm" name="export_password" placeholder="{{ cleanLang(__('lang.enter_password')) }}">
                <small class="form-text text-muted">{{ cleanLang(__('lang.password_protect_help')) }}</small>
            </div>
        </div>

        <!--estimated size-->
        <div class="alert alert-info">
            <i class="ti-info-alt"></i> <strong>{{ cleanLang(__('lang.estimated_export_size')) }}:</strong>
            <span id="estimated-size">{{ cleanLang(__('lang.calculating')) }}...</span>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Show/hide date range section
        $('input[name="export_type"]').on('change', function() {
            if ($(this).val() === 'custom_range') {
                $('#date-range-section').slideDown();
            } else {
                $('#date-range-section').slideUp();
            }
            calculateExportSize();
        });

        // Show/hide password section
        $('#password_protect').on('change', function() {
            if ($(this).is(':checked')) {
                $('#password-section').slideDown();
            } else {
                $('#password-section').slideUp();
            }
        });

        // Calculate export size on any change
        $('input[type="checkbox"], select[name="export_format"]').on('change', calculateExportSize);

        // Initial calculation
        calculateExportSize();
    });

    function calculateExportSize() {
        const formData = {
            export_type: $('input[name="export_type"]:checked').val(),
            export_format: $('#export_format').val(),
            include_media: $('#include_media').is(':checked'),
            ticket_id: {{ $ticket_id ?? 0 }}
        };

        $.ajax({
            url: '/whatsapp/export/estimate-size',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#estimated-size').text(response.data.size + ' (' + response.data.message_count + ' messages)');
                }
            }
        });
    }
</script>
