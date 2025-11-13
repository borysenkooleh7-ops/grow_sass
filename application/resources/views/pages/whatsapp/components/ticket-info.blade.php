<!--Ticket Info Sidebar-->
<div class="whatsapp-ticket-info">
    <div class="ticket-info-header">
        <h5>{{ cleanLang(__('lang.ticket_information')) }}</h5>
    </div>

    <div class="ticket-info-body">
        <!--status-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.status')) }}:</label>
            <span class="label {{ runtimeTicketStatusColors($ticket->ticket_status, 'label') }}">
                {{ runtimeLang($ticket->ticket_status) }}
            </span>
        </div>

        <!--priority-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.priority')) }}:</label>
            <span class="badge badge-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : ($ticket->priority == 'low' ? 'info' : 'secondary')) }}">
                {{ runtimeLang($ticket->priority) }}
            </span>
        </div>

        <!--client-->
        @if($ticket->client_id)
        <div class="info-item">
            <label>{{ cleanLang(__('lang.client')) }}:</label>
            <a href="/clients/{{ $ticket->client_id }}">{{ $ticket->client_company_name }}</a>
        </div>
        @endif

        <!--contact-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.contact')) }}:</label>
            <div>{{ $ticket->contact_name }}</div>
        </div>

        <!--phone-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.phone')) }}:</label>
            <a href="https://wa.me/{{ $ticket->phone_number }}" target="_blank" class="text-success">
                <i class="fab fa-whatsapp"></i> {{ $ticket->phone_number }}
            </a>
        </div>

        <!--connection-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.connection')) }}:</label>
            <div>{{ $ticket->connection_name ?? __('lang.unknown') }}</div>
        </div>

        <!--assigned to-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.assigned_to')) }}:</label>
            @if($ticket->assigned_to)
                <div class="d-flex align-items-center">
                    <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename) }}"
                        alt="user" class="img-circle avatar-xsmall mr-2">
                    <span>{{ $ticket->assigned_first_name }} {{ $ticket->assigned_last_name }}</span>
                </div>
            @else
                <span class="text-muted">{{ __('lang.not_assigned') }}</span>
            @endif
        </div>

        <!--created-->
        <div class="info-item">
            <label>{{ cleanLang(__('lang.created')) }}:</label>
            <div>{{ runtimeDate($ticket->created_at) }}</div>
            <small class="text-muted">{{ runtimeDateAgo($ticket->created_at) }}</small>
        </div>

        <!--last message-->
        @if($ticket->last_message_at)
        <div class="info-item">
            <label>{{ cleanLang(__('lang.last_message')) }}:</label>
            <div>{{ runtimeDate($ticket->last_message_at) }}</div>
            <small class="text-muted">{{ runtimeDateAgo($ticket->last_message_at) }}</small>
        </div>
        @endif

        <!--tags-->
        @if(count($ticket->tags ?? []) > 0)
        <div class="info-item">
            <label>{{ cleanLang(__('lang.tags')) }}:</label>
            <div class="tags-list">
                @foreach($ticket->tags as $tag)
                <span class="badge badge-secondary">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!--unread count-->
        @if($ticket->unread_count > 0)
        <div class="info-item">
            <label>{{ cleanLang(__('lang.unread_messages')) }}:</label>
            <span class="badge badge-danger">{{ $ticket->unread_count }}</span>
        </div>
        @endif

        <!--pinned-->
        @if($ticket->pinned == 'yes')
        <div class="info-item">
            <label>{{ cleanLang(__('lang.pinned')) }}:</label>
            <i class="sl-icon-pin text-warning"></i> {{ cleanLang(__('lang.yes')) }}
        </div>
        @endif
    </div>

    <div class="ticket-info-actions">
        <button class="btn btn-sm btn-block btn-primary" onclick="editTicket({{ $ticket->id }})">
            <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
        </button>
        <button class="btn btn-sm btn-block btn-warning" onclick="changeTicketStatus({{ $ticket->id }})">
            <i class="ti-reload"></i> {{ cleanLang(__('lang.change_status')) }}
        </button>
        @if($ticket->pinned == 'no')
        <button class="btn btn-sm btn-block btn-secondary" onclick="pinTicket({{ $ticket->id }})">
            <i class="sl-icon-pin"></i> {{ cleanLang(__('lang.pin')) }}
        </button>
        @else
        <button class="btn btn-sm btn-block btn-secondary" onclick="unpinTicket({{ $ticket->id }})">
            <i class="sl-icon-pin"></i> {{ cleanLang(__('lang.unpin')) }}
        </button>
        @endif
    </div>
</div>

<!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">
