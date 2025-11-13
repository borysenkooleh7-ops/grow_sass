<!--Options Section-->

<!--client-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.client')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="client_id" name="client_id">
            <option value="">{{ cleanLang(__('lang.none')) }}</option>
            @if(isset($clients))
                @foreach($clients as $client)
                <option value="{{ $client->client_id }}">{{ $client->client_company_name ?: $client->client_first_name . ' ' . $client->client_last_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!--status-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.status')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="ticket_status" name="ticket_status">
            <option value="open" selected>{{ cleanLang(__('lang.open')) }}</option>
            <option value="answered">{{ cleanLang(__('lang.answered')) }}</option>
            <option value="on_hold">{{ cleanLang(__('lang.on_hold')) }}</option>
            <option value="resolved">{{ cleanLang(__('lang.resolved')) }}</option>
            <option value="closed">{{ cleanLang(__('lang.closed')) }}</option>
        </select>
    </div>
</div>

<!--priority-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.priority')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="priority" name="priority">
            <option value="low">{{ cleanLang(__('lang.low')) }}</option>
            <option value="normal" selected>{{ cleanLang(__('lang.normal')) }}</option>
            <option value="high">{{ cleanLang(__('lang.high')) }}</option>
            <option value="urgent">{{ cleanLang(__('lang.urgent')) }}</option>
        </select>
    </div>
</div>

<!--assign to-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.assign_to')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="assigned_to" name="assigned_to">
            <option value="">{{ cleanLang(__('lang.not_assigned')) }}</option>
            @if(isset($users))
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!--tags-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
    <div class="col-sm-12">
        <select class="select2-multiple-tags form-control form-control-sm" multiple="multiple" id="tags" name="tags[]">
            @if(isset($tags))
                @foreach($tags as $tag)
                <option value="{{ $tag->tag_title }}">{{ $tag->tag_title }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!--send immediately-->
<div class="form-group form-group-checkbox row">
    <div class="col-12 p-t-5">
        <input type="checkbox" id="send_immediately" name="send_immediately" class="filled-in chk-col-light-blue" checked>
        <label for="send_immediately">{{ cleanLang(__('lang.send_message_immediately')) }}</label>
        <small class="form-text text-muted">{{ cleanLang(__('lang.uncheck_to_create_ticket_only')) }}</small>
    </div>
</div>
