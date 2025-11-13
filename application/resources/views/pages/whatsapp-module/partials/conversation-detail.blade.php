{{-- WhatsApp Conversation Detail View --}}
<div class="whatsapp-conversation-detail" id="conversation-detail-{{ $ticket->whatsappticket_id }}">

    {{-- Conversation Header --}}
    <div class="conversation-header">
        <div class="contact-info">
            <div class="contact-avatar">
                <img src="{{ $ticket->contact->avatar }}" alt="{{ $ticket->contact->display_name }}">
            </div>
            <div class="contact-details">
                <h5 class="contact-name">
                    {{ $ticket->contact->display_name }}
                    @if($ticket->contact->isLinkedToClient())
                        <span class="badge badge-info badge-sm">
                            <i class="mdi mdi-link"></i> {{ __('lang.linked_to_client') }}
                        </span>
                    @endif
                </h5>
                <div class="contact-meta">
                    <span class="contact-phone">{{ $ticket->contact->whatsappcontact_phone }}</span>
                    @if($ticket->contact->whatsappcontact_company)
                        <span class="ml-2"><i class="mdi mdi-domain"></i> {{ $ticket->contact->whatsappcontact_company }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="conversation-actions">
            {{-- Edit Contact Button --}}
            <button type="button" class="btn btn-sm btn-light" id="btn-edit-contact-{{ $ticket->whatsappticket_id }}" title="{{ __('lang.edit_contact') }}">
                <i class="mdi mdi-account-edit"></i>
            </button>

            {{-- Assign Ticket --}}
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" title="{{ __('lang.assign') }}">
                    <i class="mdi mdi-account-plus"></i>
                    @if($ticket->assignedAgent)
                        {{ $ticket->assignedAgent->first_name }}
                    @else
                        {{ __('lang.unassigned') }}
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">{{ __('lang.assign_to') }}</div>
                    @foreach($agents as $agent)
                        <a class="dropdown-item assign-ticket-btn" href="#" data-ticket-id="{{ $ticket->whatsappticket_id }}" data-agent-id="{{ $agent->id }}">
                            {{ $agent->first_name }} {{ $agent->last_name }}
                        </a>
                    @endforeach
                    @if($ticket->assignedAgent)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger assign-ticket-btn" href="#" data-ticket-id="{{ $ticket->whatsappticket_id }}" data-agent-id="">
                            <i class="mdi mdi-close"></i> {{ __('lang.unassign') }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Change Status --}}
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-{{ $ticket->whatsappticket_status == 'open' ? 'success' : 'warning' }} dropdown-toggle" data-toggle="dropdown">
                    {{ __('' lang.status_' . $ticket->whatsappticket_status) }}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item change-status-btn" href="#" data-ticket-id="{{ $ticket->whatsappticket_id }}" data-status="on_hold">
                        {{ __('lang.on_hold') }}
                    </a>
                    <a class="dropdown-item change-status-btn" href="#" data-ticket-id="{{ $ticket->whatsappticket_id }}" data-status="open">
                        {{ __('lang.open') }}
                    </a>
                    <a class="dropdown-item change-status-btn" href="#" data-ticket-id="{{ $ticket->whatsappticket_id }}" data-status="resolved">
                        {{ __('lang.resolved') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" id="btn-close-ticket-{{ $ticket->whatsappticket_id }}">
                        <i class="mdi mdi-close-circle"></i> {{ __('lang.close_ticket') }}
                    </a>
                </div>
            </div>

            {{-- Ticket Number --}}
            <span class="badge badge-light ml-2">#{{ $ticket->whatsappticket_number }}</span>
        </div>
    </div>

    {{-- Messages Container --}}
    <div class="messages-container" id="messages-container-{{ $ticket->whatsappticket_id }}">
        <div class="messages-list" id="messages-list-{{ $ticket->whatsappticket_id }}">
            {{-- Messages will be loaded via AJAX --}}
            <div class="text-center p-5">
                <i class="mdi mdi-loading mdi-spin mdi-48px"></i>
                <p class="mt-3">{{ __('lang.loading_messages') }}...</p>
            </div>
        </div>
    </div>

    {{-- Message Composer --}}
    @include('pages.whatsapp-module.partials.composer', ['ticket' => $ticket])

</div>

<style>
.whatsapp-conversation-detail {
    display: flex;
    flex-direction: column;
    height: 100vh;
    max-height: calc(100vh - 100px);
    background: #fff;
}

.conversation-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

.contact-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.contact-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.contact-name {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.contact-meta {
    font-size: 13px;
    color: #6c757d;
}

.conversation-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #e5ddd5; /* WhatsApp-like background */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 800 800'%3E%3Cg fill='none' stroke='%23e0e0e0' stroke-width='1'%3E%3Cpath d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/%3E%3Cpath d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/%3E%3Cpath d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/%3E%3Cpath d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/%3E%3Cpath d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/%3E%3C/g%3E%3Cg fill='%23e9e9e9'%3E%3Ccircle cx='769' cy='229' r='5'/%3E%3Ccircle cx='539' cy='269' r='5'/%3E%3Ccircle cx='603' cy='493' r='5'/%3E%3Ccircle cx='731' cy='737' r='5'/%3E%3Ccircle cx='520' cy='660' r='5'/%3E%3Ccircle cx='309' cy='538' r='5'/%3E%3Ccircle cx='295' cy='764' r='5'/%3E%3Ccircle cx='40' cy='599' r='5'/%3E%3Ccircle cx='102' cy='382' r='5'/%3E%3Ccircle cx='127' cy='80' r='5'/%3E%3Ccircle cx='370' cy='105' r='5'/%3E%3Ccircle cx='578' cy='42' r='5'/%3E%3Ccircle cx='237' cy='261' r='5'/%3E%3Ccircle cx='390' cy='382' r='5'/%3E%3C/g%3E%3C/svg%3E");
}

.messages-list {
    max-width: 900px;
    margin: 0 auto;
}

.message-bubble {
    display: flex;
    margin-bottom: 15px;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-bubble.incoming {
    justify-content: flex-start;
}

.message-bubble.outgoing {
    justify-content: flex-end;
}

.message-bubble.internal-note {
    justify-content: center;
}

.message-content {
    max-width: 65%;
    padding: 10px 15px;
    border-radius: 8px;
    position: relative;
    word-wrap: break-word;
}

.message-bubble.incoming .message-content {
    background: #fff;
    border-bottom-left-radius: 0;
    box-shadow: 0 1px 0.5px rgba(0,0,0,.13);
}

.message-bubble.outgoing .message-content {
    background: #dcf8c6;
    border-bottom-right-radius: 0;
    box-shadow: 0 1px 0.5px rgba(0,0,0,.13);
}

.message-bubble.internal-note .message-content {
    background: #fff3cd;
    border-left: 3px solid #ffc107;
    max-width: 80%;
}

.message-header {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #25d366;
}

.message-body {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 5px;
}

.message-media {
    margin-bottom: 10px;
}

.message-media img {
    max-width: 300px;
    border-radius: 8px;
}

.message-footer {
    font-size: 11px;
    color: #667781;
    text-align: right;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 5px;
}

.message-status {
    display: inline-flex;
    align-items: center;
}

.date-separator {
    text-align: center;
    margin: 20px 0;
}

.date-separator span {
    background: #fff;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    color: #667781;
    box-shadow: 0 1px 0.5px rgba(0,0,0,.13);
}
</style>

<script>
(function() {
    const ticketId = {{ $ticket->whatsappticket_id }};

    // Load Messages
    function loadMessages(scrollToBottom = true) {
        $.get(`/whatsapp/conversation/ticket/${ticketId}`, function(response) {
            let html = '';
            let lastDate = '';

            if (response.messages && response.messages.length > 0) {
                response.messages.forEach(function(msg) {
                    // Date separator
                    const msgDate = new Date(msg.whatsappmessage_created).toLocaleDateString();
                    if (msgDate !== lastDate) {
                        html += `
                            <div class="date-separator">
                                <span>${msgDate}</span>
                            </div>
                        `;
                        lastDate = msgDate;
                    }

                    // Message bubble
                    const isIncoming = msg.whatsappmessage_direction === 'incoming';
                    const isInternal = msg.whatsappmessage_is_internal_note;
                    const bubbleClass = isInternal ? 'internal-note' : (isIncoming ? 'incoming' : 'outgoing');

                    html += `
                        <div class="message-bubble ${bubbleClass}">
                            <div class="message-content">
                                ${!isInternal && !isIncoming ? '<div class="message-header">' + (msg.user ? msg.user.first_name : 'You') + '</div>' : ''}
                                ${!isInternal && isIncoming ? '<div class="message-header">' + msg.sender_name + '</div>' : ''}
                                ${isInternal ? '<div class="message-header"><i class="mdi mdi-note"></i> Internal Note</div>' : ''}

                                ${msg.whatsappmessage_media_url ? `
                                    <div class="message-media">
                                        ${msg.whatsappmessage_type === 'image' ?
                                            '<img src="' + msg.whatsappmessage_media_url + '" alt="Image">' :
                                            '<a href="' + msg.whatsappmessage_media_url + '" target="_blank"><i class="mdi mdi-file"></i> ' + msg.whatsappmessage_media_filename + '</a>'
                                        }
                                    </div>
                                ` : ''}

                                <div class="message-body">${msg.whatsappmessage_content}</div>

                                <div class="message-footer">
                                    ${new Date(msg.whatsappmessage_created).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                    ${!isInternal && !isIncoming ? '<span class="message-status">' + msg.status_icon + '</span>' : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="text-center p-5 text-muted">{{ __("lang.no_messages") }}</div>';
            }

            $(`#messages-list-${ticketId}`).html(html);

            if (scrollToBottom) {
                const container = $(`#messages-container-${ticketId}`);
                container.scrollTop(container[0].scrollHeight);
            }
        });
    }

    // Make loadMessages global for composer
    window.loadMessages = loadMessages;

    // Initial load
    loadMessages();

    // Auto-refresh every 5 seconds
    setInterval(function() {
        loadMessages(false);
    }, 5000);

    // Edit Contact
    $(`#btn-edit-contact-${ticketId}`).click(function() {
        const contactId = {{ $ticket->contact->whatsappcontact_id }};
        showEditContactModal(contactId);
    });

    // Assign Ticket
    $('.assign-ticket-btn').click(function(e) {
        e.preventDefault();
        const agentId = $(this).data('agent-id');

        $.post('/whatsapp/tickets/assign', {
            ticket_id: ticketId,
            agent_id: agentId
        }, function(response) {
            if (response.success) {
                NioApp.Toast('{{ __("lang.ticket_assigned") }}', 'success');
                location.reload();
            }
        });
    });

    // Change Status
    $('.change-status-btn').click(function(e) {
        e.preventDefault();
        const status = $(this).data('status');

        $.post('/whatsapp/tickets/status', {
            ticket_id: ticketId,
            status: status
        }, function(response) {
            if (response.success) {
                NioApp.Toast('{{ __("lang.status_updated") }}', 'success');
                location.reload();
            }
        });
    });

    // Close Ticket (with type selector)
    $(`#btn-close-ticket-${ticketId}`).click(function(e) {
        e.preventDefault();
        showCloseTicketModal(ticketId);
    });
})();
</script>
