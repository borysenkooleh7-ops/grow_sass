<!--Action Buttons-->
<div class="col-lg-12 col-md-12 col-sm-12 text-right p-b-20 p-l-0 p-r-0">

    <!--toggle stats-->
    <button type="button" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form waves-effect waves-light hidden-lg-up"
        data-toggle="tooltip" title="{{ cleanLang(__('lang.toggle_stats')) }}" id="card-leads-toggle-stats">
        <i class="ti-stats-up"></i>
    </button>

    <!--toggle filter-->
    <button type="button" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form waves-effect waves-light hidden-lg-up"
        data-toggle="tooltip" title="{{ cleanLang(__('lang.toggle_filter_panel')) }}" id="card-leads-toggle-filter">
        <i class="mdi mdi-filter-outline"></i>
    </button>

    <!--manage connections-->
    @if(auth()->user()->role_id == 1)
    <button type="button" class="btn waves-effect waves-light btn-secondary edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
        data-toggle="modal"
        data-target="#commonModal"
        data-url="{{ url('/whatsapp/connections') }}"
        data-loading-target="commonModalBody"
        data-modal-title="{{ cleanLang(__('lang.whatsapp_connections')) }}"
        data-modal-size="modal-lg"
        data-header-close-icon="hidden"
        data-footer-visibility="hidden"
        data-action-ajax-loading-target="whatsapp-table-wrapper">
        <i class="ti-settings"></i> {{ cleanLang(__('lang.connections')) }}
    </button>
    @endif

    <!--new ticket-->
    <button type="button" class="btn waves-effect waves-light btn-danger edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
        data-toggle="modal"
        data-target="#commonModal"
        data-url="{{ url('/whatsapp/create') }}"
        data-loading-target="commonModalBody"
        data-modal-title="{{ cleanLang(__('lang.new_whatsapp_ticket')) }}"
        data-action-url="{{ url('/whatsapp') }}"
        data-action-method="POST"
        data-action-ajax-class=""
        data-action-ajax-loading-target="whatsapp-table-wrapper">
        <i class="ti-plus"></i> {{ cleanLang(__('lang.new_ticket')) }}
    </button>

    <!--checkbox actions-->
    <span class="whatsapp-checked-actions-container hidden">
        @include('pages.whatsapp.components.actions.checkbox-actions')
    </span>

</div>
