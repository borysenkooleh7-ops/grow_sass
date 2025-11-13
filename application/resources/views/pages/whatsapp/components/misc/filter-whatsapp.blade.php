<!--FILTER BAR-->
<div class="filter-options">

    <!--stats-->
    <button type="button" class="btn btn-sm btn-default waves-effect waves-light filter-item" id="filter-stats-toggle">
        <i class="ti-stats-up"></i>
        <span class="hidden-xs">{{ cleanLang(__('lang.stats')) }}</span>
    </button>

    <!--status filter-->
    <select class="select2-basic form-control form-control-sm filter-item" id="filter-status" name="filter-status"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        <option value="">{{ cleanLang(__('lang.status')) }}</option>
        <option value="open">{{ cleanLang(__('lang.open')) }}</option>
        <option value="answered">{{ cleanLang(__('lang.answered')) }}</option>
        <option value="on_hold">{{ cleanLang(__('lang.on_hold')) }}</option>
        <option value="resolved">{{ cleanLang(__('lang.resolved')) }}</option>
        <option value="closed">{{ cleanLang(__('lang.closed')) }}</option>
    </select>

    <!--priority filter-->
    <select class="select2-basic form-control form-control-sm filter-item" id="filter-priority" name="filter-priority"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        <option value="">{{ cleanLang(__('lang.priority')) }}</option>
        <option value="low">{{ cleanLang(__('lang.low')) }}</option>
        <option value="normal">{{ cleanLang(__('lang.normal')) }}</option>
        <option value="high">{{ cleanLang(__('lang.high')) }}</option>
        <option value="urgent">{{ cleanLang(__('lang.urgent')) }}</option>
    </select>

    <!--assigned filter-->
    <select class="select2-basic form-control form-control-sm filter-item" id="filter-assigned" name="filter-assigned"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        <option value="">{{ cleanLang(__('lang.assigned_to')) }}</option>
        <option value="unassigned">{{ cleanLang(__('lang.unassigned')) }}</option>
        @foreach(config('system.team_members') as $user)
        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
        @endforeach
    </select>

    <!--connection filter-->
    <select class="select2-basic form-control form-control-sm filter-item" id="filter-connection" name="filter-connection"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        <option value="">{{ cleanLang(__('lang.connection')) }}</option>
        @foreach($connections as $connection)
        <option value="{{ $connection->id }}">{{ $connection->connection_name }}</option>
        @endforeach
    </select>

    <!--client filter-->
    <select class="select2-basic form-control form-control-sm filter-item" id="filter-client" name="filter-client"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        <option value="">{{ cleanLang(__('lang.client')) }}</option>
        @foreach(config('system.clients') as $client)
        <option value="{{ $client->client_id }}">{{ $client->client_company_name }}</option>
        @endforeach
    </select>

    <!--date created filter-->
    <div class="filter-item">
        <input type="text" class="form-control form-control-sm pickadate" autocomplete="off" name="filter-date-created-start"
            placeholder="{{ cleanLang(__('lang.date_created_start')) }}"
            data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
            data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
    </div>
    <div class="filter-item">
        <input type="text" class="form-control form-control-sm pickadate" autocomplete="off" name="filter-date-created-end"
            placeholder="{{ cleanLang(__('lang.date_created_end')) }}"
            data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
            data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
    </div>

    <!--tags filter-->
    <select class="select2-multiple-tags form-control form-control-sm filter-item" multiple="multiple" id="filter-tags" name="filter-tags[]"
        data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
        data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
        @foreach($tags as $tag)
        <option value="{{ $tag->tag_title }}">{{ $tag->tag_title }}</option>
        @endforeach
    </select>

    <!--unread only-->
    <div class="filter-item">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="filter-unread" name="filter-unread"
                data-url="{{ url('/whatsapp/filter') }}" data-type="form" data-ajax-type="post"
                data-form-id="filter-form" data-page-loading-target="whatsapp-table-wrapper">
            <label class="custom-control-label" for="filter-unread">{{ cleanLang(__('lang.unread_only')) }}</label>
        </div>
    </div>

    <!--reset button-->
    <button type="button" class="btn btn-sm btn-danger waves-effect waves-light filter-item" id="filter-reset-button">
        {{ cleanLang(__('lang.reset')) }}
    </button>

</div>
