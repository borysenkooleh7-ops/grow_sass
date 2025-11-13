{{-- Individual Message Bubble --}}
{{-- Renders a single message with appropriate styling --}}

<div class="message-bubble {{ $message->whatsappmessage_direction }} {{ $message->isInternalNote() ? 'internal-note' : '' }}"
     data-message-id="{{ $message->whatsappmessage_id }}">
    <div class="message-content">
        {{-- Internal Note Badge --}}
        @if($message->isInternalNote())
            <div style="font-size: 11px; font-weight: 600; color: #856404; margin-bottom: 5px;">
                <i class="ti-eye-off"></i> INTERNAL NOTE
            </div>
        @endif

        {{-- Channel Badge (if Email) --}}
        @if($message->whatsappmessage_channel === 'email')
            <div style="font-size: 11px; font-weight: 600; color: #0066cc; margin-bottom: 5px;">
                <i class="ti-email"></i> EMAIL
            </div>
        @endif

        {{-- Media Content --}}
        @if($message->hasMedia())
            <div class="message-media">
                @if($message->whatsappmessage_type === 'image')
                    <img src="{{ $message->whatsappmessage_media_url }}" alt="Image">
                @elseif($message->whatsappmessage_type === 'video')
                    <video controls style="max-width: 100%; border-radius: 8px;">
                        <source src="{{ $message->whatsappmessage_media_url }}" type="{{ $message->whatsappmessage_media_mime }}">
                    </video>
                @elseif($message->whatsappmessage_type === 'audio')
                    <audio controls style="width: 100%;">
                        <source src="{{ $message->whatsappmessage_media_url }}" type="{{ $message->whatsappmessage_media_mime }}">
                    </audio>
                @elseif($message->whatsappmessage_type === 'document')
                    <div style="background: rgba(0,0,0,0.05); padding: 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <i class="ti-file" style="font-size: 24px;"></i>
                        <div style="flex: 1; overflow: hidden;">
                            <div style="font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $message->whatsappmessage_media_filename }}
                            </div>
                            <div style="font-size: 11px; color: #667781;">
                                {{ $message->whatsappmessage_media_size ? round($message->whatsappmessage_media_size / 1024, 1) . ' KB' : '' }}
                            </div>
                        </div>
                        <a href="{{ $message->whatsappmessage_media_url }}" download target="_blank"
                           style="background: #25D366; color: #fff; padding: 8px 12px; border-radius: 5px; text-decoration: none; font-size: 12px;">
                            <i class="ti-download"></i>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        {{-- Text Content --}}
        <div class="message-text">{{ $message->whatsappmessage_content }}</div>

        {{-- Message Meta (Time + Status) --}}
        <div class="message-meta">
            {{-- Sender Name (for internal notes) --}}
            @if($message->isInternalNote() && $message->user)
                <span style="font-weight: 600; margin-right: 5px;">
                    {{ $message->user->first_name }}
                </span>
            @endif

            {{-- Time --}}
            <span class="message-time">
                {{ $message->whatsappmessage_created->format('g:i A') }}
            </span>

            {{-- Status Icon (for outgoing messages) --}}
            @if($message->isOutgoing() && !$message->isInternalNote())
                <span class="message-status">
                    @if($message->whatsappmessage_status === 'pending')
                        <i class="mdi mdi-clock-outline" style="color: #999;"></i>
                    @elseif($message->whatsappmessage_status === 'sent')
                        <i class="mdi mdi-check" style="color: #999;"></i>
                    @elseif($message->whatsappmessage_status === 'delivered')
                        <i class="mdi mdi-check-all" style="color: #4FC3F7;"></i>
                    @elseif($message->whatsappmessage_status === 'read')
                        <i class="mdi mdi-check-all" style="color: #2196F3;"></i>
                    @elseif($message->whatsappmessage_status === 'failed')
                        <i class="mdi mdi-alert-circle" style="color: #f44336;"></i>
                    @endif
                </span>
            @endif
        </div>

        {{-- Error Message (if failed) --}}
        @if($message->whatsappmessage_status === 'failed' && !empty($message->whatsappmessage_error))
            <div style="font-size: 11px; color: #f44336; margin-top: 5px; border-top: 1px solid rgba(244, 67, 54, 0.2); padding-top: 5px;">
                <i class="ti-alert"></i> {{ $message->whatsappmessage_error }}
            </div>
        @endif
    </div>
</div>
