<!--Change Status Modal-->
<div class="row">
    <div class="col-lg-12">

        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.status')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="ticket_status" name="ticket_status">
                    <option value="open">{{ cleanLang(__('lang.open')) }}</option>
                    <option value="answered">{{ cleanLang(__('lang.answered')) }}</option>
                    <option value="on_hold">{{ cleanLang(__('lang.on_hold')) }}</option>
                    <option value="resolved">{{ cleanLang(__('lang.resolved')) }}</option>
                    <option value="closed">{{ cleanLang(__('lang.closed')) }}</option>
                </select>
            </div>
        </div>

        <!--notes-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.notes')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="3" name="status_change_note"
                    placeholder="{{ cleanLang(__('lang.add_optional_note')) }}"></textarea>
            </div>
        </div>

        <!--notify client-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="notify_client" name="notify_client" class="filled-in chk-col-light-blue" checked>
                <label for="notify_client">{{ cleanLang(__('lang.notify_client')) }}</label>
            </div>
        </div>

    </div>
</div>
