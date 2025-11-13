<!--WhatsApp Contact Detail View-->
<div class="row">
    <div class="col-lg-4">
        <!--contact info card-->
        <div class="card">
            <div class="card-body text-center">
                <div class="contact-avatar mb-3">
                    @if($contact->avatar_url)
                        <img src="{{ $contact->avatar_url }}" alt="{{ $contact->name }}" class="rounded-circle" style="width: 120px; height: 120px;">
                    @else
                        <div class="avatar-placeholder rounded-circle mx-auto" style="width: 120px; height: 120px; background: #25D366; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 48px; color: white;">{{ substr($contact->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <h4>{{ $contact->name }}</h4>
                <p class="text-muted">
                    <a href="https://wa.me/{{ $contact->phone_number }}" target="_blank" class="text-success">
                        <i class="fab fa-whatsapp"></i> {{ $contact->phone_number }}
                    </a>
                </p>

                @if($contact->email)
                <p class="text-muted">
                    <i class="ti-email"></i> {{ $contact->email }}
                </p>
                @endif

                <!--quick actions-->
                <div class="btn-group-vertical btn-block mt-3">
                    <button class="btn btn-success btn-sm" onclick="startNewChat('{{ $contact->phone_number }}')">
                        <i class="fab fa-whatsapp"></i> {{ cleanLang(__('lang.start_chat')) }}
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="editContact({{ $contact->id }})">
                        <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit_contact')) }}
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="exportContact({{ $contact->id }})">
                        <i class="ti-download"></i> {{ cleanLang(__('lang.export_vcard')) }}
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="blockContact({{ $contact->id }})">
                        <i class="ti-na"></i> {{ cleanLang(__('lang.block_contact')) }}
                    </button>
                </div>

                <!--tags-->
                @if($contact->tags && count($contact->tags) > 0)
                <div class="contact-tags mt-3">
                    <h6>{{ cleanLang(__('lang.tags')) }}</h6>
                    @foreach($contact->tags as $tag)
                        <span class="badge badge-secondary">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                <!--stats-->
                <div class="contact-stats mt-3">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="mb-0">{{ $contact->total_tickets ?? 0 }}</h4>
                            <small class="text-muted">{{ cleanLang(__('lang.total_tickets')) }}</small>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0">{{ $contact->total_messages ?? 0 }}</h4>
                            <small class="text-muted">{{ cleanLang(__('lang.messages')) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--additional info-->
        <div class="card mt-3">
            <div class="card-header">
                <h6>{{ cleanLang(__('lang.additional_information')) }}</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>{{ cleanLang(__('lang.client')) }}:</strong></td>
                        <td>
                            @if($contact->client_id)
                                <a href="/clients/{{ $contact->client_id }}">{{ $contact->client_name }}</a>
                            @else
                                <span class="text-muted">{{ __('lang.none') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ cleanLang(__('lang.first_contact')) }}:</strong></td>
                        <td>{{ runtimeDate($contact->created_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ cleanLang(__('lang.last_contact')) }}:</strong></td>
                        <td>{{ runtimeDateAgo($contact->last_contact_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ cleanLang(__('lang.language')) }}:</strong></td>
                        <td>{{ $contact->language ?? __('lang.not_set') }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ cleanLang(__('lang.timezone')) }}:</strong></td>
                        <td>{{ $contact->timezone ?? __('lang.not_set') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!--tabs-->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tickets-tab">
                    <i class="ti-ticket"></i> {{ cleanLang(__('lang.tickets')) }} ({{ $contact->tickets->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages-tab">
                    <i class="ti-comments"></i> {{ cleanLang(__('lang.messages')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#media-tab">
                    <i class="ti-gallery"></i> {{ cleanLang(__('lang.media')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#notes-tab">
                    <i class="ti-notepad"></i> {{ cleanLang(__('lang.notes')) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity-tab">
                    <i class="ti-time"></i> {{ cleanLang(__('lang.activity')) }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!--tickets tab-->
            <div class="tab-pane fade show active" id="tickets-tab">
                <div class="card">
                    <div class="card-body">
                        @if($contact->tickets && count($contact->tickets) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ cleanLang(__('lang.subject')) }}</th>
                                            <th>{{ cleanLang(__('lang.status')) }}</th>
                                            <th>{{ cleanLang(__('lang.assigned')) }}</th>
                                            <th>{{ cleanLang(__('lang.created')) }}</th>
                                            <th>{{ cleanLang(__('lang.action')) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contact->tickets as $ticket)
                                        <tr>
                                            <td>{{ str_limit($ticket->ticket_subject, 40) }}</td>
                                            <td>
                                                <span class="label {{ runtimeTicketStatusColors($ticket->ticket_status, 'label') }}">
                                                    {{ runtimeLang($ticket->ticket_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($ticket->assigned_to)
                                                    <img src="{{ getUsersAvatar($ticket->avatar_directory, $ticket->avatar_filename) }}"
                                                        alt="user" class="img-circle avatar-xsmall" title="{{ $ticket->assigned_name }}">
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ runtimeDateAgo($ticket->created_at) }}</td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-primary" onclick="openWhatsappConversation({{ $ticket->id }})">
                                                    <i class="ti-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted p-4">
                                <i class="ti-ticket" style="font-size: 48px; opacity: 0.3;"></i>
                                <p>{{ cleanLang(__('lang.no_tickets_found')) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!--messages tab-->
            <div class="tab-pane fade" id="messages-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="messages-timeline" id="contact-messages">
                            <!--Messages loaded via AJAX-->
                        </div>
                    </div>
                </div>
            </div>

            <!--media tab-->
            <div class="tab-pane fade" id="media-tab">
                <div class="card">
                    <div class="card-body">
                        <div id="contact-media-gallery">
                            <!--Media loaded via AJAX-->
                        </div>
                    </div>
                </div>
            </div>

            <!--notes tab-->
            <div class="tab-pane fade" id="notes-tab">
                <div class="card">
                    <div class="card-body">
                        <!--add note form-->
                        <div class="add-note-form mb-3">
                            <textarea class="form-control" rows="3" id="new-note" placeholder="{{ cleanLang(__('lang.add_note_about_contact')) }}"></textarea>
                            <button class="btn btn-primary btn-sm mt-2" onclick="addContactNote()">
                                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_note')) }}
                            </button>
                        </div>

                        <!--notes list-->
                        <div id="contact-notes">
                            @if($contact->notes && count($contact->notes) > 0)
                                @foreach($contact->notes as $note)
                                <div class="note-item card mb-2">
                                    <div class="card-body">
                                        <div class="note-header d-flex justify-content-between">
                                            <div>
                                                <img src="{{ getUsersAvatar($note->user->avatar_directory, $note->user->avatar_filename) }}"
                                                    alt="user" class="img-circle avatar-xsmall mr-2">
                                                <strong>{{ $note->user->first_name }} {{ $note->user->last_name }}</strong>
                                                <small class="text-muted">• {{ runtimeDateAgo($note->created_at) }}</small>
                                            </div>
                                            <button class="btn btn-xs btn-link text-danger" onclick="deleteNote({{ $note->id }})">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </div>
                                        <div class="note-content mt-2">
                                            {{ $note->note }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted p-4">
                                    <i class="ti-notepad" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p>{{ cleanLang(__('lang.no_notes_found')) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!--activity tab-->
            <div class="tab-pane fade" id="activity-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="activity-timeline" id="contact-activity">
                            <!--Activity loaded via AJAX-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load messages when tab is clicked
    $('a[href="#messages-tab"]').on('shown.bs.tab', function() {
        if ($('#contact-messages').is(':empty')) {
            $.ajax({
                url: '/whatsapp/contacts/{{ $contact->id }}/messages',
                method: 'GET',
                success: function(response) {
                    $('#contact-messages').html(response.html);
                }
            });
        }
    });

    // Load media when tab is clicked
    $('a[href="#media-tab"]').on('shown.bs.tab', function() {
        if ($('#contact-media-gallery').is(':empty')) {
            $.ajax({
                url: '/whatsapp/contacts/{{ $contact->id }}/media',
                method: 'GET',
                success: function(response) {
                    $('#contact-media-gallery').html(response.html);
                }
            });
        }
    });

    // Load activity when tab is clicked
    $('a[href="#activity-tab"]').on('shown.bs.tab', function() {
        if ($('#contact-activity').is(':empty')) {
            $.ajax({
                url: '/whatsapp/contacts/{{ $contact->id }}/activity',
                method: 'GET',
                success: function(response) {
                    $('#contact-activity').html(response.html);
                }
            });
        }
    });

    function startNewChat(phoneNumber) {
        NX.loadModal({
            url: '/whatsapp/create?phone=' + phoneNumber,
            title: '{{ cleanLang(__("lang.new_whatsapp_ticket")) }}',
            size: 'large'
        });
    }

    function editContact(contactId) {
        NX.loadModal({
            url: '/whatsapp/contacts/' + contactId + '/edit',
            title: '{{ cleanLang(__("lang.edit_contact")) }}',
            size: 'medium'
        });
    }

    function exportContact(contactId) {
        window.location.href = '/whatsapp/contacts/' + contactId + '/export-vcard';
    }

    function blockContact(contactId) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure_block_contact")) }}')) {
            $.ajax({
                url: '/whatsapp/contacts/' + contactId + '/block',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        NX.notification({
                            type: 'success',
                            message: '{{ cleanLang(__("lang.contact_blocked")) }}'
                        });
                        window.location.reload();
                    }
                }
            });
        }
    }

    function addContactNote() {
        const note = $('#new-note').val();
        if (!note) return;

        $.ajax({
            url: '/whatsapp/contacts/{{ $contact->id }}/notes',
            method: 'POST',
            data: { note: note },
            success: function(response) {
                if (response.success) {
                    $('#contact-notes').prepend(response.html);
                    $('#new-note').val('');
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.note_added")) }}'
                    });
                }
            }
        });
    }

    function deleteNote(noteId) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure")) }}')) {
            $.ajax({
                url: '/whatsapp/contacts/notes/' + noteId,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('[data-note-id="' + noteId + '"]').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }
            });
        }
    }
</script>
