<!--checkbox actions-->
<div class="list-table-action dropdown">
    <button type="button" class="btn btn-danger btn-sm dropdown-toggle waves-effect" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="ti-check"></i>
        <span class="hidden-xs hidden-sm">{{ cleanLang(__('lang.actions')) }}</span>
    </button>
    <div class="dropdown-menu w-px-250 py-0">

        <!--assign to-->
        <div class="customSelectBox dropdown-item p-t-2 p-b-2">
            <label class="form-label font-16">{{ cleanLang(__('lang.assign_to')) }}</label>
            <select class="select2-basic form-control form-control-sm select2-preselected" id="checkbox-actions-assign-to"
                name="checkbox-actions-assign-to" data-url="{{ url('/whatsapp/actions/assign') }}"
                data-type="form" data-ajax-type="post" data-form-id="whatsapp-actions-form" data-payload-addional-fields="list_action">
                <option></option>
                @foreach(config('system.team_members') as $user)
                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="list_action" value="assign-to-user">
        </div>

        <!--change status-->
        <div class="customSelectBox dropdown-item p-t-2 p-b-2">
            <label class="form-label font-16">{{ cleanLang(__('lang.change_status')) }}</label>
            <select class="select2-basic form-control form-control-sm select2-preselected" id="checkbox-actions-change-status"
                name="checkbox-actions-change-status" data-url="{{ url('/whatsapp/actions/change-status') }}"
                data-type="form" data-ajax-type="post" data-form-id="whatsapp-actions-form" data-payload-addional-fields="list_action">
                <option></option>
                <option value="open">{{ cleanLang(__('lang.open')) }}</option>
                <option value="answered">{{ cleanLang(__('lang.answered')) }}</option>
                <option value="on_hold">{{ cleanLang(__('lang.on_hold')) }}</option>
                <option value="resolved">{{ cleanLang(__('lang.resolved')) }}</option>
                <option value="closed">{{ cleanLang(__('lang.closed')) }}</option>
            </select>
            <input type="hidden" name="list_action" value="change-status">
        </div>

        <!--change priority-->
        <div class="customSelectBox dropdown-item p-t-2 p-b-2">
            <label class="form-label font-16">{{ cleanLang(__('lang.change_priority')) }}</label>
            <select class="select2-basic form-control form-control-sm select2-preselected" id="checkbox-actions-change-priority"
                name="checkbox-actions-change-priority" data-url="{{ url('/whatsapp/actions/change-priority') }}"
                data-type="form" data-ajax-type="post" data-form-id="whatsapp-actions-form" data-payload-addional-fields="list_action">
                <option></option>
                <option value="low">{{ cleanLang(__('lang.low')) }}</option>
                <option value="normal">{{ cleanLang(__('lang.normal')) }}</option>
                <option value="high">{{ cleanLang(__('lang.high')) }}</option>
                <option value="urgent">{{ cleanLang(__('lang.urgent')) }}</option>
            </select>
            <input type="hidden" name="list_action" value="change-priority">
        </div>

        <div class="dropdown-divider m-0"></div>

        <!--add tags-->
        <a class="dropdown-item confirm-action-info js-ajax-ux-request" href="javascript:void(0)"
            data-confirm-title="{{ cleanLang(__('lang.add_tags')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
            data-ajax-type="POST"
            data-type="form"
            data-form-id="whatsapp-actions-form"
            data-url="{{ url('/whatsapp/actions/add-tags') }}"
            data-payload-addional-fields="list_action">
            <i class="sl-icon-tag"></i> {{ cleanLang(__('lang.add_tags')) }}
            <input type="hidden" name="list_action" value="add-tags">
        </a>

        <!--delete-->
        <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
            data-confirm-title="{{ cleanLang(__('lang.delete_items')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
            data-ajax-type="POST"
            data-type="form"
            data-form-id="whatsapp-actions-form"
            data-url="{{ url('/whatsapp/actions/delete') }}"
            data-payload-addional-fields="list_action">
            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
            <input type="hidden" name="list_action" value="delete">
        </a>

    </div>
</div>
