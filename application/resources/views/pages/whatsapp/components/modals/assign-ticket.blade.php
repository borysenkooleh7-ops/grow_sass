<!--Assign Ticket Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--assign to-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.assign_to')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="assigned_to" name="assigned_to">
                    <option value="">{{ cleanLang(__('lang.select_user')) }}</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ runtimePreselected($user->id, $ticket->assigned_to ?? '') }}>
                        {{ $user->first_name }} {{ $user->last_name }}
                        @if($user->id == auth()->id())
                            ({{ __('lang.me') }})
                        @endif
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--note-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.note')) }}</label>
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" rows="3" name="assignment_note"
                    placeholder="{{ cleanLang(__('lang.add_optional_note')) }}"></textarea>
            </div>
        </div>

        <!--notify assignee-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="notify_assignee" name="notify_assignee" class="filled-in chk-col-light-blue" checked>
                <label for="notify_assignee">{{ cleanLang(__('lang.notify_assignee')) }}</label>
            </div>
        </div>

        <!--notify client-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="notify_client_assign" name="notify_client_assign" class="filled-in chk-col-light-blue">
                <label for="notify_client_assign">{{ cleanLang(__('lang.notify_client')) }}</label>
            </div>
        </div>

    </div>
</div>
