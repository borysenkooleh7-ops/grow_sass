<!--Compose Section-->

<!--subject-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.subject')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="ticket_subject" name="ticket_subject"
            placeholder="{{ cleanLang(__('lang.ticket_subject')) }}">
    </div>
</div>

<!--phone number-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.phone_number')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number"
            placeholder="+1234567890">
        <small class="form-text text-muted">{{ cleanLang(__('lang.phone_number_format_help')) }}</small>
    </div>
</div>

<!--contact name-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_name')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="contact_name" name="contact_name"
            placeholder="{{ cleanLang(__('lang.contact_name')) }}">
    </div>
</div>

<!--message-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.initial_message')) }}</label>
    <div class="col-sm-12">
        <textarea class="form-control form-control-sm" rows="5" name="initial_message" id="initial_message"
            placeholder="{{ cleanLang(__('lang.type_your_message_here')) }}"></textarea>
    </div>
</div>

<!--channel-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.channel')) }}</label>
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="channel" id="channel-whatsapp" value="whatsapp" checked>
            <label class="form-check-label" for="channel-whatsapp">
                <i class="fab fa-whatsapp text-success"></i> WhatsApp
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="channel" id="channel-email" value="email">
            <label class="form-check-label" for="channel-email">
                <i class="ti-email"></i> Email
            </label>
        </div>
    </div>
</div>

<!--attachments-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.attachments')) }}</label>
    <div class="col-sm-12">
        <input type="file" class="form-control-file" name="attachments[]" id="attachments" multiple>
        <small class="form-text text-muted">{{ cleanLang(__('lang.max_file_size_10mb')) }}</small>
    </div>
</div>
