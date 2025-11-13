{{-- WhatsApp Message Composer Component --}}
<div class="whatsapp-composer" id="whatsapp-composer-{{ $ticket->whatsappticket_id }}">
    <div class="composer-container">

        {{-- Channel Selector --}}
        <div class="composer-channel-selector">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-success active" data-channel="whatsapp">
                    <i class="mdi mdi-whatsapp"></i> WhatsApp
                </button>
                <button type="button" class="btn btn-outline-primary" data-channel="email">
                    <i class="mdi mdi-email"></i> Email
                </button>
            </div>

            {{-- 24-hour window indicator --}}
            @if($ticket->isWithin24HourWindow())
                <span class="badge badge-success ml-2">
                    <i class="mdi mdi-check-circle"></i> {{ __('lang.within_24h_window') }}
                </span>
            @else
                <span class="badge badge-warning ml-2">
                    <i class="mdi mdi-alert"></i> {{ __('lang.template_required') }}
                </span>
            @endif
        </div>

        {{-- Message Type Tabs --}}
        <ul class="nav nav-tabs composer-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#message-tab-{{ $ticket->whatsappticket_id }}" role="tab">
                    <i class="mdi mdi-message-text"></i> {{ __('lang.message') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#internal-note-tab-{{ $ticket->whatsappticket_id }}" role="tab">
                    <i class="mdi mdi-note-text"></i> {{ __('lang.internal_note') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#templates-tab-{{ $ticket->whatsappticket_id }}" role="tab">
                    <i class="mdi mdi-file-document"></i> {{ __('lang.templates') }}
                </a>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content composer-tab-content">

            {{-- Regular Message Tab --}}
            <div class="tab-pane fade show active" id="message-tab-{{ $ticket->whatsappticket_id }}" role="tabpanel">
                <form id="message-form-{{ $ticket->whatsappticket_id }}" class="composer-form">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->whatsappticket_id }}">
                    <input type="hidden" name="channel" value="whatsapp" class="composer-channel-input">
                    <input type="hidden" name="is_internal" value="0">

                    {{-- Message Input Area --}}
                    <div class="composer-input-wrapper">
                        <textarea
                            class="form-control composer-textarea"
                            name="message"
                            rows="4"
                            placeholder="{{ __('lang.type_message') }}..."
                            required></textarea>

                        {{-- Quick Actions Toolbar --}}
                        <div class="composer-toolbar">
                            <div class="toolbar-left">
                                {{-- Emoji Picker --}}
                                <button type="button" class="btn btn-sm btn-light" id="emoji-picker-{{ $ticket->whatsappticket_id }}" title="{{ __('lang.emoji') }}">
                                    <i class="mdi mdi-emoticon-happy"></i>
                                </button>

                                {{-- Attach File --}}
                                <button type="button" class="btn btn-sm btn-light" id="attach-file-{{ $ticket->whatsappticket_id }}" title="{{ __('lang.attach_file') }}">
                                    <i class="mdi mdi-paperclip"></i>
                                </button>
                                <input type="file" id="file-input-{{ $ticket->whatsappticket_id }}" class="d-none" name="attachment" accept="image/*,video/*,audio/*,application/pdf,.doc,.docx">

                                {{-- Quick Replies Dropdown --}}
                                <div class="dropdown d-inline-block">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" title="{{ __('lang.quick_replies') }}">
                                        <i class="mdi mdi-flash"></i>
                                    </button>
                                    <div class="dropdown-menu quick-replies-menu">
                                        <div class="dropdown-header">{{ __('lang.quick_replies') }}</div>
                                        <div class="quick-replies-list" id="quick-replies-{{ $ticket->whatsappticket_id }}">
                                            <div class="text-center p-3">
                                                <i class="mdi mdi-loading mdi-spin"></i> {{ __('lang.loading') }}...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="toolbar-right">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="mdi mdi-send"></i> {{ __('lang.send') }}
                                </button>
                            </div>
                        </div>

                        {{-- Attachment Preview --}}
                        <div class="attachment-preview d-none" id="attachment-preview-{{ $ticket->whatsappticket_id }}">
                            <div class="preview-item">
                                <img src="" class="preview-image d-none">
                                <div class="preview-file d-none">
                                    <i class="mdi mdi-file"></i>
                                    <span class="preview-filename"></span>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger preview-remove">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Internal Note Tab --}}
            <div class="tab-pane fade" id="internal-note-tab-{{ $ticket->whatsappticket_id }}" role="tabpanel">
                <form id="internal-note-form-{{ $ticket->whatsappticket_id }}" class="composer-form">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->whatsappticket_id }}">
                    <input type="hidden" name="is_internal" value="1">

                    <div class="alert alert-info alert-sm">
                        <i class="mdi mdi-information"></i> {{ __('lang.internal_note_info') }}
                    </div>

                    <textarea
                        class="form-control"
                        name="message"
                        rows="4"
                        placeholder="{{ __('lang.internal_note_placeholder') }}..."
                        required></textarea>

                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="mdi mdi-note-plus"></i> {{ __('lang.add_note') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Templates Tab --}}
            <div class="tab-pane fade" id="templates-tab-{{ $ticket->whatsappticket_id }}" role="tabpanel">
                <div class="templates-container">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" id="template-search-{{ $ticket->whatsappticket_id }}" placeholder="{{ __('lang.search_templates') }}...">
                    </div>

                    <div class="templates-list" id="templates-list-{{ $ticket->whatsappticket_id }}">
                        <div class="text-center p-3">
                            <i class="mdi mdi-loading mdi-spin"></i> {{ __('lang.loading') }}...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Emoji Picker Modal (Simple) --}}
<div class="emoji-picker-popup d-none" id="emoji-popup-{{ $ticket->whatsappticket_id }}">
    <div class="emoji-grid">
        @php
        $emojis = ['😀','😃','😄','😁','😆','😅','🤣','😂','🙂','🙃','😉','😊','😇','🥰','😍','🤩','😘','😗','😚','😙','🥲','😋','😛','😜','🤪','😝','🤑','🤗','🤭','🤫','🤔','🤐','🤨','😐','😑','😶','😏','😒','🙄','😬','🤥','😌','😔','😪','🤤','😴','😷','🤒','🤕','🤢','🤮','🤧','🥵','🥶','🥴','😵','🤯','🤠','🥳','🥸','😎','🤓','🧐','👍','👎','👌','✌','🤞','🤟','🤘','🤙','👈','👉','👆','👇','☝','✋','🤚','🖐','🖖','👋','🤝','🙏','💪','🦾','🦿','🦵','🦶','👂','🦻','👃','🧠','🫀','🫁','🦷','🦴','👀','👁','👅','👄'];
        @endphp
        @foreach($emojis as $emoji)
            <span class="emoji-item" data-emoji="{{ $emoji }}">{{ $emoji }}</span>
        @endforeach
    </div>
</div>

<style>
.whatsapp-composer {
    background: #fff;
    border-top: 1px solid #e9ecef;
    padding: 15px;
}

.composer-channel-selector {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.composer-tabs {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 0;
}

.composer-tab-content {
    padding: 15px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-top: none;
}

.composer-textarea {
    border: 1px solid #ced4da;
    resize: none;
    font-size: 14px;
}

.composer-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #dee2e6;
}

.toolbar-left {
    display: flex;
    gap: 5px;
}

.attachment-preview {
    margin-top: 10px;
    padding: 10px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.preview-item {
    position: relative;
    display: inline-block;
}

.preview-image {
    max-width: 100px;
    max-height: 100px;
    border-radius: 4px;
}

.preview-file {
    padding: 10px 15px;
    background: #e9ecef;
    border-radius: 4px;
}

.preview-remove {
    position: absolute;
    top: -8px;
    right: -8px;
}

.quick-replies-menu {
    max-height: 300px;
    overflow-y: auto;
    width: 300px;
}

.quick-reply-item {
    padding: 8px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f1f1f1;
}

.quick-reply-item:hover {
    background: #f8f9fa;
}

.quick-reply-shortcut {
    font-size: 11px;
    color: #999;
    margin-left: 5px;
}

.templates-list {
    max-height: 400px;
    overflow-y: auto;
}

.template-item {
    padding: 10px;
    margin-bottom: 10px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
}

.template-item:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.template-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.template-preview {
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.emoji-picker-popup {
    position: absolute;
    bottom: 50px;
    left: 15px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 5px;
    max-height: 200px;
    overflow-y: auto;
}

.emoji-item {
    font-size: 24px;
    cursor: pointer;
    padding: 5px;
    text-align: center;
    border-radius: 4px;
}

.emoji-item:hover {
    background: #f0f0f0;
}
</style>

<script>
(function() {
    const ticketId = {{ $ticket->whatsappticket_id }};

    // Channel Selector
    $('.composer-channel-selector .btn-group button').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        const channel = $(this).data('channel');
        $(`#whatsapp-composer-${ticketId} .composer-channel-input`).val(channel);
    });

    // File Attachment
    $(`#attach-file-${ticketId}`).click(function() {
        $(`#file-input-${ticketId}`).click();
    });

    $(`#file-input-${ticketId}`).change(function() {
        const file = this.files[0];
        if (file) {
            const preview = $(`#attachment-preview-${ticketId}`);
            preview.removeClass('d-none');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.find('.preview-image').attr('src', e.target.result).removeClass('d-none');
                    preview.find('.preview-file').addClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.find('.preview-file').removeClass('d-none').find('.preview-filename').text(file.name);
                preview.find('.preview-image').addClass('d-none');
            }
        }
    });

    $(`#attachment-preview-${ticketId} .preview-remove`).click(function() {
        $(`#file-input-${ticketId}`).val('');
        $(`#attachment-preview-${ticketId}`).addClass('d-none');
    });

    // Emoji Picker
    $(`#emoji-picker-${ticketId}`).click(function(e) {
        e.stopPropagation();
        $(`#emoji-popup-${ticketId}`).toggleClass('d-none');
    });

    $(document).click(function() {
        $(`#emoji-popup-${ticketId}`).addClass('d-none');
    });

    $(`#emoji-popup-${ticketId} .emoji-item`).click(function(e) {
        e.stopPropagation();
        const emoji = $(this).data('emoji');
        const textarea = $(`#message-form-${ticketId} textarea[name="message"]`);
        const cursorPos = textarea[0].selectionStart;
        const text = textarea.val();
        textarea.val(text.slice(0, cursorPos) + emoji + text.slice(cursorPos));
        textarea.focus();
    });

    // Load Quick Replies
    function loadQuickReplies() {
        $.get('/whatsapp/quick-replies/list', function(data) {
            let html = '';
            if (data.quick_replies && data.quick_replies.length > 0) {
                data.quick_replies.forEach(function(reply) {
                    html += `
                        <a class="dropdown-item quick-reply-item" href="#" data-message="${reply.whatsappquickreply_message}">
                            <strong>${reply.whatsappquickreply_title}</strong>
                            ${reply.whatsappquickreply_shortcut ? '<span class="quick-reply-shortcut">' + reply.whatsappquickreply_shortcut + '</span>' : ''}
                        </a>
                    `;
                });
            } else {
                html = '<div class="dropdown-item text-muted">{{ __("lang.no_quick_replies") }}</div>';
            }
            $(`#quick-replies-${ticketId}`).html(html);
        });
    }

    loadQuickReplies();

    $(document).on('click', '.quick-reply-item', function(e) {
        e.preventDefault();
        const message = $(this).data('message');
        $(`#message-form-${ticketId} textarea[name="message"]`).val(message);
    });

    // Load Templates
    function loadTemplates() {
        $.get('/whatsapp/templates/list', function(data) {
            let html = '';
            if (data.templates && data.templates.length > 0) {
                data.templates.forEach(function(template) {
                    html += `
                        <div class="template-item" data-template-id="${template.whatsapptemplate_id}" data-message="${template.whatsapptemplate_message}">
                            <div class="template-title">${template.whatsapptemplate_title}</div>
                            <div class="template-preview">${template.whatsapptemplate_message}</div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="text-muted text-center p-3">{{ __("lang.no_templates") }}</div>';
            }
            $(`#templates-list-${ticketId}`).html(html);
        });
    }

    loadTemplates();

    $(document).on('click', '.template-item', function() {
        const message = $(this).data('message');
        $(`#message-form-${ticketId} textarea[name="message"]`).val(message);
        $(`a[href="#message-tab-${ticketId}"]`).tab('show');
    });

    // Template Search
    $(`#template-search-${ticketId}`).on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $(`#templates-list-${ticketId} .template-item`).filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Form Submission
    $(`#message-form-${ticketId}, #internal-note-form-${ticketId}`).submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();

        $btn.html('<i class="mdi mdi-loading mdi-spin"></i> {{ __("lang.sending") }}...').prop('disabled', true);

        $.ajax({
            url: '/whatsapp/messages/send',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Clear form
                    $(`#message-form-${ticketId}, #internal-note-form-${ticketId}`).find('textarea').val('');
                    $(`#file-input-${ticketId}`).val('');
                    $(`#attachment-preview-${ticketId}`).addClass('d-none');

                    // Reload messages
                    if (typeof loadMessages === 'function') {
                        loadMessages(ticketId);
                    }

                    // Show success
                    NioApp.Toast('{{ __("lang.message_sent") }}', 'success');
                }
            },
            error: function(xhr) {
                NioApp.Toast(xhr.responseJSON?.message || '{{ __("lang.error_sending_message") }}', 'error');
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    });
})();
</script>
