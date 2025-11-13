<!--Add/Edit Message Template Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--title-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.title')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" id="template_title" name="template_title"
                    value="{{ $template->whatsapptemplatemain_title ?? '' }}" placeholder="{{ cleanLang(__('lang.template_title')) }}">
            </div>
        </div>

        <!--category-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.category')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="template_category" name="template_category">
                    <option value="general" {{ runtimePreselected('general', $template->whatsapptemplatemain_category ?? '') }}>
                        {{ cleanLang(__('lang.general')) }}
                    </option>
                    <option value="marketing" {{ runtimePreselected('marketing', $template->whatsapptemplatemain_category ?? '') }}>
                        {{ cleanLang(__('lang.marketing')) }}
                    </option>
                    <option value="notification" {{ runtimePreselected('notification', $template->whatsapptemplatemain_category ?? '') }}>
                        {{ cleanLang(__('lang.notification')) }}
                    </option>
                    <option value="support" {{ runtimePreselected('support', $template->whatsapptemplatemain_category ?? '') }}>
                        {{ cleanLang(__('lang.support')) }}
                    </option>
                    <option value="transactional" {{ runtimePreselected('transactional', $template->whatsapptemplatemain_category ?? '') }}>
                        {{ cleanLang(__('lang.transactional')) }}
                    </option>
                </select>
            </div>
        </div>

        <!--message-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.message')) }}*</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="8" name="template_message" id="template_message">{{ $template->whatsapptemplatemain_message ?? '' }}</textarea>
                <small class="form-text text-muted">
                    {{ cleanLang(__('lang.available_variables')) }}:
                    <code>@{{contact_name}}</code>,
                    <code>@{{company_name}}</code>,
                    <code>@{{phone_number}}</code>,
                    <code>@{{agent_name}}</code>,
                    <code>@{{date}}</code>,
                    <code>@{{time}}</code>
                </small>
            </div>
        </div>

        <!--language-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.language')) }}</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="template_language" name="template_language">
                    <option value="en" {{ runtimePreselected('en', $template->whatsapptemplatemain_language ?? 'en') }}>English</option>
                    <option value="es" {{ runtimePreselected('es', $template->whatsapptemplatemain_language ?? '') }}>Español</option>
                    <option value="fr" {{ runtimePreselected('fr', $template->whatsapptemplatemain_language ?? '') }}>Français</option>
                    <option value="de" {{ runtimePreselected('de', $template->whatsapptemplatemain_language ?? '') }}>Deutsch</option>
                    <option value="pt" {{ runtimePreselected('pt', $template->whatsapptemplatemain_language ?? '') }}>Português</option>
                    <option value="ar" {{ runtimePreselected('ar', $template->whatsapptemplatemain_language ?? '') }}>العربية</option>
                </select>
            </div>
        </div>

        <!--buttons (optional)-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.buttons')) }}</label>
            <div class="col-sm-12">
                <div id="template-buttons-container">
                    @if(isset($template->whatsapptemplatemain_buttons) && count($template->whatsapptemplatemain_buttons) > 0)
                        @foreach($template->buttons as $index => $button)
                        <div class="input-group mb-2 template-button-row">
                            <input type="text" class="form-control form-control-sm" name="button_text[]" value="{{ $button->text }}" placeholder="Button text">
                            <input type="text" class="form-control form-control-sm" name="button_url[]" value="{{ $button->url ?? '' }}" placeholder="URL (optional)">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-danger" type="button" onclick="removeButton(this)">
                                    <i class="ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addButton()">
                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_button')) }}
                </button>
                <small class="form-text text-muted">{{ cleanLang(__('lang.template_buttons_help')) }}</small>
            </div>
        </div>

        <!--is active-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="template_is_active" name="template_is_active" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($template->whatsapptemplatemain_is_active ?? true) }}>
                <label for="template_is_active">{{ cleanLang(__('lang.active')) }}</label>
            </div>
        </div>

        <!--hidden fields for edit-->
        @if(isset($template->whatsapptemplatemain_id))
        <input type="hidden" name="template_id" value="{{ $template->whatsapptemplatemain_id }}">
        @endif

    </div>
</div>

<script>
    function addButton() {
        const container = $('#template-buttons-container');
        const buttonHtml = `
            <div class="input-group mb-2 template-button-row">
                <input type="text" class="form-control form-control-sm" name="button_text[]" placeholder="Button text">
                <input type="text" class="form-control form-control-sm" name="button_url[]" placeholder="URL (optional)">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-danger" type="button" onclick="removeButton(this)">
                        <i class="ti-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.append(buttonHtml);
    }

    function removeButton(element) {
        $(element).closest('.template-button-row').remove();
    }
</script>
