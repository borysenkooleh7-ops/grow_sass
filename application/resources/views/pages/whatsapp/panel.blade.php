{{-- WhatsApp Conversation Panel --}}
{{-- This is the sliding panel that opens from the right side --}}

<!--Styles-->
<link href="/public/css/whatsapp/panel.css?v={{ config('system.versioning') }}" rel="stylesheet">


<div class="whatsapp-conversation-panel" id="whatsapp-panel">
    {{-- Overlay --}}
    <div class="panel-overlay" onclick="closeWhatsappPanel()"></div>

    {{-- Panel Content --}}
    <div class="panel-content">
        {{-- Header --}}
        <div class="panel-header" id="panel-header">
            <button class="back-button" onclick="closeWhatsappPanel()" title="Close">
                <i class="ti-arrow-left"></i>
            </button>
            <div class="contact-info" id="panel-contact-info">
                <div class="panel-loading">Loading...</div>
            </div>
            <button class="settings-button" onclick="toggleSettingsDropdown()" title="Settings">
                <i class="ti-more-alt"></i>
            </button>
        </div>

        {{-- Settings Dropdown --}}
        <div class="settings-dropdown" id="settings-dropdown">
            <div class="settings-dropdown-item" onclick="assignTicketToMe()">
                <i class="ti-user"></i> Assign to Me
            </div>
            <div class="settings-dropdown-item" onclick="updateTicketStatus('on_hold')">
                <i class="ti-control-pause"></i> Mark as On Hold
            </div>
            <div class="settings-dropdown-item" onclick="updateTicketStatus('resolved')">
                <i class="ti-check"></i> Mark as Resolved
            </div>
            <div class="settings-dropdown-item" onclick="updateTicketStatus('closed')">
                <i class="ti-lock"></i> Close Ticket
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="panel-messages" id="panel-messages">
            <div class="panel-loading">Loading conversation...</div>
        </div>

        {{-- New Message Notification --}}
        <div class="new-message-notification" id="new-message-notification" onclick="scrollMessagesToBottom()">
            <span id="new-message-count">0</span> new messages ↓
        </div>

        {{-- Composer --}}
        <div class="panel-composer">
            <div class="composer-options">
                <label>
                    <input type="radio" name="channel" value="whatsapp" checked>
                    <i class="fab fa-whatsapp text-success"></i> WhatsApp
                </label>
                <label>
                    <input type="radio" name="channel" value="email">
                    <i class="ti-email"></i> Email
                </label>
                <label>
                    <input type="checkbox" id="is-internal-note">
                    <i class="ti-eye-off"></i> Internal Note
                </label>
            </div>
            <form class="composer-form" id="whatsapp-message-form" onsubmit="sendWhatsappMessage(event)">
                <button type="button" class="attach-button" onclick="attachFile()" title="Attach File">
                    <i class="ti-paperclip"></i>
                </button>
                <textarea
                    id="message-input"
                    rows="1"
                    placeholder="Type a message..."
                    onkeydown="handleMessageInputKeydown(event)"
                ></textarea>
                <button type="submit" title="Send Message">
                    <i class="ti-location-arrow"></i>
                </button>
            </form>
            <input type="file" id="file-input" style="display: none;" onchange="handleFileSelect(event)">
            <input type="hidden" id="current-ticket-id" value="">
            <input type="hidden" id="current-task-id" value="">
        </div>
    </div>
</div>

{{-- Include JavaScript --}}
<script src="/public/js/whatsapp/panel.js?v={{ config('system.versioning') }}"></script>
