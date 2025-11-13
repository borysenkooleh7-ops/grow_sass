<!--Broadcast Message Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--recipients type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.send_to')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="recipient_type" name="recipient_type" onchange="toggleRecipientOptions()">
                    <option value="all_contacts">{{ cleanLang(__('lang.all_contacts')) }}</option>
                    <option value="clients">{{ cleanLang(__('lang.all_clients')) }}</option>
                    <option value="specific_clients">{{ cleanLang(__('lang.specific_clients')) }}</option>
                    <option value="tags">{{ cleanLang(__('lang.contacts_with_tags')) }}</option>
                    <option value="custom">{{ cleanLang(__('lang.custom_phone_numbers')) }}</option>
                </select>
            </div>
        </div>

        <!--specific clients-->
        <div class="form-group row" id="specific-clients-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.select_clients')) }}</label>
            <div class="col-sm-12">
                <select class="select2-multiple form-control form-control-sm" multiple="multiple" id="client_ids" name="client_ids[]">
                    @foreach($clients as $client)
                    <option value="{{ $client->client_id }}">{{ $client->client_company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--tags-->
        <div class="form-group row" id="tags-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.select_tags')) }}</label>
            <div class="col-sm-12">
                <select class="select2-multiple-tags form-control form-control-sm" multiple="multiple" id="broadcast_tags" name="broadcast_tags[]">
                    @foreach($tags as $tag)
                    <option value="{{ $tag->tag_title }}">{{ $tag->tag_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--custom phone numbers-->
        <div class="form-group row" id="custom-phones-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.phone_numbers')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="4" name="phone_numbers" id="phone_numbers"
                    placeholder="{{ cleanLang(__('lang.enter_phone_numbers_one_per_line')) }}"></textarea>
                <small class="form-text text-muted">{{ cleanLang(__('lang.phone_numbers_format_help')) }}</small>
            </div>
        </div>

        <!--broadcast name-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.broadcast_name')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" name="broadcast_name" id="broadcast_name" placeholder="{{ cleanLang(__('lang.enter_broadcast_name')) }}">
            </div>
        </div>

        <!--connection-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.whatsapp_connection')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="broadcast_connection_id" name="broadcast_connection_id">
                    <option value="">{{ cleanLang(__('lang.select')) }}</option>
                    @foreach($connections as $connection)
                    <option value="{{ $connection->whatsappconnection_id }}">
                        {{ $connection->whatsappconnection_name }} ({{ $connection->whatsappconnection_phone }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--message template-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.use_template')) }}</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="template_id" name="template_id" onchange="loadTemplate()">
                    <option value="">{{ cleanLang(__('lang.none')) }}</option>
                    @foreach($templates as $template)
                    <option value="{{ $template->whatsapptemplatemain_id }}">{{ $template->whatsapptemplatemain_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--message-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.message')) }}*</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="6" name="broadcast_message" id="broadcast_message"
                    placeholder="{{ cleanLang(__('lang.type_your_message_here')) }}"></textarea>
                <small class="form-text text-muted">
                    {{ cleanLang(__('lang.available_variables')) }}:
                    <code>@{{contact_name}}</code>,
                    <code>@{{company_name}}</code>,
                    <code>@{{phone_number}}</code>
                </small>
            </div>
        </div>

        <!--attachments-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.attachments')) }}</label>
            <div class="col-sm-12">
                <input type="file" class="form-control-file" name="broadcast_attachments[]" id="broadcast_attachments" multiple>
                <small class="form-text text-muted">{{ cleanLang(__('lang.max_file_size_10mb')) }}</small>
            </div>
        </div>

        <!--schedule-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="schedule_broadcast" name="schedule_broadcast" class="filled-in chk-col-light-blue" onchange="toggleScheduleOptions()">
                <label for="schedule_broadcast">{{ cleanLang(__('lang.schedule_for_later')) }}</label>
            </div>
        </div>

        <!--schedule date time-->
        <div class="form-group row" id="schedule-section" style="display: none;">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.schedule_datetime')) }}</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm pickdatetime" autocomplete="off" name="schedule_datetime" id="schedule_datetime">
            </div>
        </div>

        <!--estimated recipients-->
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="ti-info-alt"></i> <strong>{{ cleanLang(__('lang.estimated_recipients')) }}:</strong>
                    <span id="estimated-recipients">0</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleRecipientOptions() {
        const recipientType = $('#recipient_type').val();

        // Hide all sections
        $('#specific-clients-section').hide();
        $('#tags-section').hide();
        $('#custom-phones-section').hide();

        // Show relevant section
        if (recipientType === 'specific_clients') {
            $('#specific-clients-section').show();
        } else if (recipientType === 'tags') {
            $('#tags-section').show();
        } else if (recipientType === 'custom') {
            $('#custom-phones-section').show();
        }

        // Update estimated recipients
        updateEstimatedRecipients();
    }

    function toggleScheduleOptions() {
        if ($('#schedule_broadcast').is(':checked')) {
            $('#schedule-section').show();
        } else {
            $('#schedule-section').hide();
        }
    }

    function loadTemplate() {
        const templateId = $('#template_id').val();
        if (templateId) {
            $.ajax({
                url: '/whatsapp/templates/' + templateId,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#broadcast_message').val(response.data.message);
                    }
                }
            });
        }
    }

    function updateEstimatedRecipients() {
        const recipientType = $('#recipient_type').val();
        const formData = new FormData();
        formData.append('recipient_type', recipientType);

        if (recipientType === 'specific_clients') {
            formData.append('client_ids', $('#client_ids').val());
        } else if (recipientType === 'tags') {
            formData.append('tags', $('#broadcast_tags').val());
        } else if (recipientType === 'custom') {
            formData.append('phone_numbers', $('#phone_numbers').val());
        }

        $.ajax({
            url: '/whatsapp/broadcast/estimate',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#estimated-recipients').text(response.data.count);
                }
            }
        });
    }

    // Update estimates on change
    $(document).ready(function() {
        $('#recipient_type, #client_ids, #broadcast_tags, #phone_numbers').on('change', updateEstimatedRecipients);
    });
</script>
