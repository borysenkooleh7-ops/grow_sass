{{-- Client WhatsApp Tab --}}
{{-- Displays WhatsApp conversation history and contact info for a client --}}

<div class="row" id="client-whatsapp-tab">
    {{-- Contact Information Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fab fa-whatsapp text-success"></i> WhatsApp Contact
                </h5>

                @if($client->whatsappContact)
                    {{-- Contact exists --}}
                    <div class="text-center mb-4">
                        <img src="{{ $client->whatsappContact->avatar_url }}"
                             alt="{{ $client->whatsappContact->display_name }}"
                             class="img-circle"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <h6 class="mt-3 mb-0">{{ $client->whatsappContact->display_name }}</h6>
                        <p class="text-muted mb-2">{{ $client->whatsappContact->whatsappcontact_phone }}</p>

                        @if($client->whatsappContact->tags && $client->whatsappContact->tags->count() > 0)
                            <div class="mb-3">
                                @foreach($client->whatsappContact->tags as $tag)
                                    <span class="label label-{{ $tag->whatsapptag_color }}">{{ $tag->whatsapptag_name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <button class="btn btn-success btn-sm w-100"
                                onclick="openWhatsappForClient({{ $client->client_id }})">
                            <i class="fab fa-whatsapp"></i> Send WhatsApp Message
                        </button>
                    </div>

                    {{-- Contact Stats --}}
                    <div class="stats-widget m-t-30">
                        <div class="stats-widget-item">
                            <div class="stats-widget-value">{{ $client->whatsappTickets->count() }}</div>
                            <div class="stats-widget-label">Total Conversations</div>
                        </div>
                        <div class="stats-widget-item">
                            <div class="stats-widget-value">
                                {{ $client->whatsappTickets->whereIn('whatsappticket_status', ['open', 'on_hold'])->count() }}
                            </div>
                            <div class="stats-widget-label">Active Tickets</div>
                        </div>
                        <div class="stats-widget-item">
                            <div class="stats-widget-value">
                                {{ $client->whatsappContact->whatsappcontact_last_message_at ? $client->whatsappContact->whatsappcontact_last_message_at->diffForHumans() : 'Never' }}
                            </div>
                            <div class="stats-widget-label">Last Message</div>
                        </div>
                    </div>

                    {{-- Contact Notes --}}
                    @if($client->whatsappContact->whatsappcontact_notes)
                        <div class="m-t-30">
                            <h6>Notes</h6>
                            <p class="text-muted">{{ $client->whatsappContact->whatsappcontact_notes }}</p>
                        </div>
                    @endif

                @else
                    {{-- No contact linked --}}
                    <div class="text-center p-t-30 p-b-30">
                        <i class="fab fa-whatsapp" style="font-size: 64px; opacity: 0.2; color: #25D366;"></i>
                        <h6 class="m-t-20 m-b-10">No WhatsApp Contact</h6>
                        <p class="text-muted">This client is not linked to any WhatsApp contact yet.</p>
                        <button class="btn btn-info btn-sm"
                                onclick="showLinkContactModal({{ $client->client_id }})">
                            <i class="ti-link"></i> Link WhatsApp Contact
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Conversation History --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-comments"></i> Recent Conversations
                </h5>

                @if($client->whatsappTickets && $client->whatsappTickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Last Message</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->whatsappTickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);"
                                               onclick="openWhatsappConversation({{ $ticket->whatsappticket_taskid }})">
                                                {{ $ticket->whatsappticket_number }}
                                            </a>
                                        </td>
                                        <td>{{ $ticket->whatsappticket_subject ?? 'N/A' }}</td>
                                        <td>
                                            <span class="label label-{{ $ticket->whatsappticket_status === 'open' ? 'success' : ($ticket->whatsappticket_status === 'on_hold' ? 'warning' : 'default') }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->whatsappticket_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->assignedAgent)
                                                <span>{{ $ticket->assignedAgent->first_name }}</span>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->whatsappticket_last_message_at)
                                                <span data-toggle="tooltip"
                                                      title="{{ $ticket->whatsappticket_last_message_at->format('Y-m-d H:i:s') }}">
                                                    {{ $ticket->whatsappticket_last_message_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="text-muted">No messages</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-info"
                                                    onclick="openWhatsappConversation({{ $ticket->whatsappticket_taskid }})"
                                                    title="Open Conversation">
                                                <i class="ti-comments"></i>
                                            </button>
                                            @if($ticket->task)
                                                <a href="{{ url('/tasks/' . $ticket->task->task_id) }}"
                                                   class="btn btn-xs btn-default"
                                                   title="View Task">
                                                    <i class="ti-check-box"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-t-30 p-b-30">
                        <i class="ti-comments" style="font-size: 64px; opacity: 0.2;"></i>
                        <h6 class="m-t-20 m-b-10">No Conversations Yet</h6>
                        <p class="text-muted">No WhatsApp conversations have been started with this client.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Open WhatsApp panel for this client
     */
    function openWhatsappForClient(clientId) {
        $.ajax({
            url: '/whatsapp/composer/client/' + clientId,
            method: 'GET',
            success: function(response) {
                if (response.success && response.task_id) {
                    openWhatsappConversation(response.task_id);
                } else {
                    alert(response.error || 'Failed to open conversation');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.error || 'Failed to open conversation'));
            }
        });
    }

    /**
     * Show modal to link WhatsApp contact
     */
    function showLinkContactModal(clientId) {
        // TODO: Implement link contact modal
        var phone = prompt('Enter WhatsApp phone number (with country code, e.g., +1234567890):');
        if (phone) {
            linkNewContactToClient(clientId, phone);
        }
    }

    /**
     * Link new WhatsApp contact to client
     */
    function linkNewContactToClient(clientId, phone) {
        $.ajax({
            url: '/whatsapp/contacts/find-or-create',
            method: 'POST',
            data: {
                phone: phone,
                connection_id: 1, // TODO: Allow user to select connection
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Link contact to client
                    $.ajax({
                        url: '/whatsapp/contacts/' + response.contact.whatsappcontact_id + '/link-client',
                        method: 'POST',
                        data: {
                            client_id: clientId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            location.reload(); // Reload to show updated contact
                        },
                        error: function(xhr) {
                            alert('Error linking contact: ' + (xhr.responseJSON?.error || 'Unknown error'));
                        }
                    });
                } else {
                    alert(response.error || 'Failed to create contact');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.error || 'Failed to create contact'));
            }
        });
    }
</script>

<style>
    .stats-widget {
        display: flex;
        gap: 20px;
        border-top: 1px solid #f0f0f0;
        padding-top: 20px;
    }

    .stats-widget-item {
        flex: 1;
        text-align: center;
    }

    .stats-widget-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .stats-widget-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
    }
</style>
