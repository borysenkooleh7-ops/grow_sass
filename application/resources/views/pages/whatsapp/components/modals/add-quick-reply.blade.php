<!--Add/Edit Quick Reply Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--title-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.title')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" id="quick_reply_title" name="quick_reply_title"
                    value="{{ $quick_reply->whatsappquickreply_title ?? '' }}" placeholder="{{ cleanLang(__('lang.quick_reply_title')) }}">
            </div>
        </div>

        <!--shortcut-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.shortcut')) }}</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" id="quick_reply_shortcut" name="quick_reply_shortcut"
                    value="{{ $quick_reply->whatsappquickreply_shortcut ?? '' }}" placeholder="/greeting">
                <small class="form-text text-muted">{{ cleanLang(__('lang.shortcut_help')) }}</small>
            </div>
        </div>

        <!--message-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.message')) }}*</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="6" name="quick_reply_message" id="quick_reply_message">{{ $quick_reply->whatsappquickreply_message ?? '' }}</textarea>
                <small class="form-text text-muted">
                    {{ cleanLang(__('lang.available_variables')) }}:
                    <code>@{{contact_name}}</code>,
                    <code>@{{company_name}}</code>,
                    <code>@{{agent_name}}</code>
                </small>
            </div>
        </div>

        <!--category-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.category')) }}</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="quick_reply_category" name="quick_reply_category">
                    <option value="">{{ cleanLang(__('lang.none')) }}</option>
                    <option value="greeting" {{ runtimePreselected('greeting', $quick_reply->whatsappquickreply_category ?? '') }}>
                        {{ cleanLang(__('lang.greeting')) }}
                    </option>
                    <option value="closing" {{ runtimePreselected('closing', $quick_reply->whatsappquickreply_category ?? '') }}>
                        {{ cleanLang(__('lang.closing')) }}
                    </option>
                    <option value="information" {{ runtimePreselected('information', $quick_reply->whatsappquickreply_category ?? '') }}>
                        {{ cleanLang(__('lang.information')) }}
                    </option>
                    <option value="support" {{ runtimePreselected('support', $quick_reply->whatsappquickreply_category ?? '') }}>
                        {{ cleanLang(__('lang.support')) }}
                    </option>
                    <option value="sales" {{ runtimePreselected('sales', $quick_reply->whatsappquickreply_category ?? '') }}>
                        {{ cleanLang(__('lang.sales')) }}
                    </option>
                </select>
            </div>
        </div>

        <!--is shared-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="is_shared" name="is_shared" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($quick_reply->whatsappquickreply_is_shared ?? false) }}>
                <label for="is_shared">{{ cleanLang(__('lang.shared_with_team')) }}</label>
                <small class="form-text text-muted">{{ cleanLang(__('lang.shared_quick_reply_help')) }}</small>
            </div>
        </div>

        <!--hidden fields for edit-->
        @if(isset($quick_reply->whatsappquickreply_id))
        <input type="hidden" name="quick_reply_id" value="{{ $quick_reply->whatsappquickreply_id }}">
        @endif

    </div>
</div>
