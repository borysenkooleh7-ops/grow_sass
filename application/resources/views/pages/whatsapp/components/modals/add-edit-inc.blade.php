<!--Form Data-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.subject')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="ticket_subject" name="ticket_subject"
            value="{{ $ticket->ticket_subject ?? '' }}">
    </div>
</div>

<!--client-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.client')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="client_id" name="client_id">
            <option value="">{{ cleanLang(__('lang.none')) }}</option>
            @foreach($clients as $client)
            <option value="{{ $client->client_id }}"
                {{ runtimePreselected($client->client_id, $ticket->client_id ?? '') }}>
                {{ $client->client_company_name }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<!--contact name-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_name')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="contact_name" name="contact_name"
            value="{{ $ticket->contact_name ?? '' }}">
    </div>
</div>

<!--phone number-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.phone_number')) }}*</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number"
            value="{{ $ticket->phone_number ?? '' }}" placeholder="+1234567890">
        <small class="form-text text-muted">{{ cleanLang(__('lang.phone_number_format_help')) }}</small>
    </div>
</div>

<!--connection-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.whatsapp_connection')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="connection_id" name="connection_id">
            <option value="">{{ cleanLang(__('lang.select')) }}</option>
            @foreach($connections as $connection)
            <option value="{{ $connection->id }}"
                {{ runtimePreselected($connection->id, $ticket->connection_id ?? '') }}>
                {{ $connection->connection_name }} ({{ $connection->phone_number }})
            </option>
            @endforeach
        </select>
    </div>
</div>

<!--status-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.status')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="ticket_status" name="ticket_status">
            <option value="open" {{ runtimePreselected('open', $ticket->ticket_status ?? 'open') }}>
                {{ cleanLang(__('lang.open')) }}
            </option>
            <option value="answered" {{ runtimePreselected('answered', $ticket->ticket_status ?? '') }}>
                {{ cleanLang(__('lang.answered')) }}
            </option>
            <option value="on_hold" {{ runtimePreselected('on_hold', $ticket->ticket_status ?? '') }}>
                {{ cleanLang(__('lang.on_hold')) }}
            </option>
            <option value="resolved" {{ runtimePreselected('resolved', $ticket->ticket_status ?? '') }}>
                {{ cleanLang(__('lang.resolved')) }}
            </option>
            <option value="closed" {{ runtimePreselected('closed', $ticket->ticket_status ?? '') }}>
                {{ cleanLang(__('lang.closed')) }}
            </option>
        </select>
    </div>
</div>

<!--priority-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.priority')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="priority" name="priority">
            <option value="low" {{ runtimePreselected('low', $ticket->priority ?? '') }}>
                {{ cleanLang(__('lang.low')) }}
            </option>
            <option value="normal" {{ runtimePreselected('normal', $ticket->priority ?? 'normal') }}>
                {{ cleanLang(__('lang.normal')) }}
            </option>
            <option value="high" {{ runtimePreselected('high', $ticket->priority ?? '') }}>
                {{ cleanLang(__('lang.high')) }}
            </option>
            <option value="urgent" {{ runtimePreselected('urgent', $ticket->priority ?? '') }}>
                {{ cleanLang(__('lang.urgent')) }}
            </option>
        </select>
    </div>
</div>

<!--assigned to-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.assign_to')) }}</label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="assigned_to" name="assigned_to">
            <option value="">{{ cleanLang(__('lang.not_assigned')) }}</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ runtimePreselected($user->id, $ticket->assigned_to ?? '') }}>
                {{ $user->first_name }} {{ $user->last_name }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<!--tags-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
    <div class="col-sm-12">
        <select class="select2-multiple-tags form-control form-control-sm" multiple="multiple" id="tags" name="tags[]">
            @foreach($tags as $tag)
            <option value="{{ $tag->tag_title }}"
                {{ runtimePreselectedInArray($tag->tag_title, $ticket->tags ?? []) }}>
                {{ $tag->tag_title }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<!--hidden fields for edit-->
@if(isset($ticket->id))
<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
@endif
