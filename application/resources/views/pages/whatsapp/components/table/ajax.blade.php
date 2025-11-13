@foreach($tickets as $ticket)
<!--each row-->
<tr id="whatsapp_{{ $ticket->id }}" class="{{ $ticket->pinned == 'yes' ? 'bg-success-light' : '' }}">
    @if(config('visibility.whatsapp_col_checkboxes'))
    <td class="list-checkbox-wrapper">
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-{{ $ticket->id }}" name="ids[{{ $ticket->id }}]" class="listcheckbox filled-in chk-col-light-blue"
                data-actions-container-class="whatsapp-checkbox-actions-container" data-checked-actions-container-class="whatsapp-checked-actions-container"
                data-record-id="{{ $ticket->id }}">
            <label for="listcheckbox-{{ $ticket->id }}"></label>
        </span>
    </td>
    @endif

    <!--subject-->
    <td class="whatsapp_col_subject">
        <a href="javascript:void(0)" onclick="openWhatsappConversation({{ $ticket->id }})" class="text-primary">
            {{ str_limit($ticket->ticket_subject ?? __('lang.no_subject'), 50) }}
        </a>
        @if($ticket->unread_count > 0)
        <span class="badge badge-danger badge-xs ml-2">{{ $ticket->unread_count }}</span>
        @endif
        @if($ticket->pinned == 'yes')
        <i class="sl-icon-pin text-warning ml-1" title="{{ __('lang.pinned') }}"></i>
        @endif
    </td>

    <!--client-->
    <td class="whatsapp_col_client">
        @if($ticket->client_company_name)
            <a href="/clients/{{ $ticket->client_id }}">{{ str_limit($ticket->client_company_name ?? '---', 25) }}</a>
        @else
            <span>{{ __('lang.unknown') }}</span>
        @endif
    </td>

    <!--contact-->
    <td class="whatsapp_col_contact">
        {{ $ticket->contact_name ?? __('lang.unknown') }}
    </td>

    <!--phone-->
    <td class="whatsapp_col_phone">
        <a href="https://wa.me/{{ $ticket->phone_number }}" target="_blank" class="text-success">
            <i class="fab fa-whatsapp"></i> {{ $ticket->phone_number }}
        </a>
    </td>

    <!--last message-->
    <td class="whatsapp_col_last_message">
        <span class="text-muted">{{ runtimeDateAgo($ticket->last_message_at) }}</span>
    </td>

    <!--status-->
    <td class="whatsapp_col_status">
        <span class="label {{ runtimeTicketStatusColors($ticket->ticket_status, 'label') }}">{{
            runtimeLang($ticket->ticket_status) }}</span>
    </td>

    <!--assigned-->
    <td class="whatsapp_col_assigned">
        @if($ticket->assigned_to)
            <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename) }}"
                alt="user" class="img-circle avatar-xsmall"
                title="{{ $ticket->assigned_first_name }} {{ $ticket->assigned_last_name }}">
        @else
            <span class="text-muted">{{ __('lang.not_assigned') }}</span>
        @endif
    </td>

    <!--created-->
    <td class="whatsapp_col_created">
        {{ runtimeDate($ticket->created_at) }}
    </td>

    @if(config('visibility.whatsapp_col_action'))
    <!--actions-->
    <td class="list-actions-col">
        <div class="list-actions">
            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle waves-effect" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    {{ cleanLang(__('lang.actions')) }}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0)" onclick="openWhatsappConversation({{ $ticket->id }})">
                        <i class="ti-comments"></i> {{ cleanLang(__('lang.view_conversation')) }}
                    </a>
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)"
                        data-toggle="modal"
                        data-target="#commonModal"
                        data-url="{{ url('/whatsapp/'.$ticket->id.'/edit') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.edit_ticket')) }}"
                        data-action-url="{{ url('/whatsapp/'.$ticket->id) }}"
                        data-action-method="PUT"
                        data-action-ajax-class=""
                        data-action-ajax-loading-target="whatsapp-td-container">
                        <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                    </a>
                    @if($ticket->pinned == 'no')
                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.pin_ticket')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-ajax-type="PUT"
                        data-url="{{ url('/whatsapp/'.$ticket->id.'/pin') }}">
                        <i class="sl-icon-pin"></i> {{ cleanLang(__('lang.pin')) }}
                    </a>
                    @else
                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.unpin_ticket')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-ajax-type="PUT"
                        data-url="{{ url('/whatsapp/'.$ticket->id.'/unpin') }}">
                        <i class="sl-icon-pin"></i> {{ cleanLang(__('lang.unpin')) }}
                    </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-ajax-type="DELETE"
                        data-url="{{ url('/whatsapp/'.$ticket->id) }}">
                        <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
                    </a>
                </div>
            </div>
        </div>
    </td>
    @endif
</tr>
@endforeach
<!--each row-->

<!--ajax loading-->
@include('misc.loading-spinner')
