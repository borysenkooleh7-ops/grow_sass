"use strict";

$(document).ready(function() {
    // Initialize search functionality
    initializeSearch();
    
    // Initialize user selection
    initializeUserSelection();
    
    // Initialize emoji tab handlers globally to prevent conflicts
    initializeEmojiTabHandlers();
});

/**
 * Initialize emoji functionality (simplified approach)
 */
function initializeEmojiTabHandlers() {
    console.log('Initializing emoji functionality in messages-search.js (simplified approach)');
    
    // Add a test function to debug emoji functionality
    window.testEmojiSearchFunctionality = function() {
        console.log('=== Testing Emoji Functionality in messages-search.js ===');
        
        // Test if emoji picker exists
        const emojiPicker = $('#emoji_picker');
        console.log('Emoji picker found:', emojiPicker.length > 0);
        
        if (emojiPicker.length > 0) {
            // Test if emoji tabs exist
            const emojiTabs = emojiPicker.find('.emoji-tab');
            console.log('Emoji tabs found:', emojiTabs.length);
            
            // Test if emoji grid exists
            const emojiGrid = emojiPicker.find('#emoji_grid');
            console.log('Emoji grid found:', emojiGrid.length > 0);
            
            // Test if NX functions exist
            console.log('NX.populateEmojiGrid exists:', typeof NX !== 'undefined' && typeof NX.populateEmojiGrid === 'function');
            console.log('NX.emojiCategories exists:', typeof NX !== 'undefined' && NX.emojiCategories !== undefined);
            
            // Test switchEmojiCategory function
            console.log('window.switchEmojiCategory exists:', typeof window.switchEmojiCategory === 'function');
            
            // Test clicking first tab
            if (emojiTabs.length > 0) {
                console.log('Testing click on first emoji tab...');
                emojiTabs.first().click();
            }
        }
        
        console.log('=== End Emoji Functionality Test ===');
    };
    
    console.log('Emoji functionality initialized successfully (using direct onclick handlers)');
    
    // Add beautiful CSS styling for emoji tabs
    addEmojiTabStyles();
}

/**
 * Add beautiful CSS styling for emoji tabs
 */
function addEmojiTabStyles() {
    // Check if styles are already added
    if (document.getElementById('emoji-tabs-styles')) {
        return;
    }
    
    const style = document.createElement('style');
    style.id = 'emoji-tabs-styles';
    style.textContent = `
        /* Beautiful Emoji Tabs Styling */
        .emoji-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }
        
        .emoji-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 4px;
            min-width: 45px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .emoji-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 10px;
        }
        
        .emoji-tab:hover {
            transform: translateY(-1px) scale(1.03);
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15), 0 2px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        }
        
        .emoji-tab:hover::before {
            opacity: 1;
        }
        
        .emoji-tab.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3), 0 2px 4px rgba(0, 0, 0, 0.15);
        }
        
        .emoji-tab.active::before {
            opacity: 0;
        }
        
        .emoji-tab .tab-icon {
            font-size: 16px;
            margin-bottom: 2px;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .emoji-tab:hover .tab-icon {
            transform: scale(1.15) rotate(3deg);
        }
        
        .emoji-tab.active .tab-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        .emoji-tab .tab-label {
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .emoji-tab:hover .tab-label {
            color: #3b82f6;
            transform: translateY(-1px);
        }
        
        .emoji-tab.active .tab-label {
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        /* Emoji Grid Styling */
        .emoji-content {
            background: #ffffff;
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }
        
        .emoji-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(32px, 1fr));
            gap: 6px;
            padding: 12px;
            max-height: 180px;
            overflow-y: auto;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .emoji-grid::-webkit-scrollbar {
            width: 6px;
        }
        
        .emoji-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .emoji-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .emoji-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Emoji Picker Container */
        .emoji-picker {
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            background: white;
        }
        
        .emoji-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .emoji-header span {
            font-weight: 600;
            font-size: 14px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .close-emoji {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 6px;
            color: white;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-emoji:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        /* Responsive Design */
        @media (max-width: 480px) {
            .emoji-tabs {
                gap: 3px;
                padding: 6px;
            }
            
            .emoji-tab {
                min-width: 40px;
                padding: 4px 3px;
            }
            
            .emoji-tab .tab-icon {
                font-size: 14px;
            }
            
            .emoji-tab .tab-label {
                font-size: 7px;
            }
            
            .emoji-grid {
                grid-template-columns: repeat(auto-fill, minmax(28px, 1fr));
                gap: 4px;
                padding: 8px;
            }
        }
        
        /* Animation for tab switching */
        @keyframes tabSwitch {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .emoji-tab.active {
            animation: tabSwitch 0.3s ease-out;
        }
        
        /* Pulse effect for active tab */
        .emoji-tab.active::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
        }
        
        /* Switching animation */
        .emoji-tab.switching {
            transform: scale(0.95);
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-color: #f59e0b;
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
        }
        
        .emoji-tab.switching .tab-icon {
            transform: scale(1.2) rotate(8deg);
        }
        
        .emoji-tab.switching .tab-label {
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
    `;
    
    document.head.appendChild(style);
    console.log('Beautiful emoji tab styles added successfully');
}

/**
 * Initialize search functionality for messages
 */
function initializeSearch() {
    const searchInput = $('#messages_search');
    const searchClear = $('#search_clear');
    
    if (!searchInput.length) {
        console.log('Search input not found');
        return;
    }
    
    // Search input event handler
    searchInput.on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        if (searchTerm.length > 0) {
            searchClear.show();
            performSearch(searchTerm);
        } else {
            searchClear.hide();
            showAllItems();
        }
    });
    
    // Clear search button
    searchClear.on('click', function() {
        searchInput.val('');
        searchClear.hide();
        showAllItems();
    });
    
    // Clear search on escape key
    searchInput.on('keydown', function(e) {
        if (e.key === 'Escape') {
            $(this).val('');
            searchClear.hide();
            showAllItems();
        }
    });
}

/**
 * Perform search across users and connections
 */
function performSearch(searchTerm) {
    if (!searchTerm) {
        showAllItems();
        return;
    }
    
    // Search in users
    $('.user-item').each(function() {
        const userName = $(this).find('.user-name').text().toLowerCase();
        const userEmail = $(this).find('.user-email').text().toLowerCase();
        const userRole = $(this).find('.user-role').text().toLowerCase();
        
        if (userName.includes(searchTerm) || userEmail.includes(searchTerm) || userRole.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    // Search in WhatsApp connections
    $('.whatsapp-connection-item').each(function() {
        const connectionName = $(this).find('.connection-name').text().toLowerCase();
        const phoneNumber = $(this).find('.connection-phone').text().toLowerCase();
        const connectionType = $(this).find('.connection-type').text().toLowerCase();
        
        if (connectionName.includes(searchTerm) || phoneNumber.includes(searchTerm) || connectionType.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    // Search in recent contacts
    $('.contact-item').each(function() {
        const contactName = $(this).find('.contact-name').text().toLowerCase();
        const contactPhone = $(this).find('.contact-phone').text().toLowerCase();
        const contactStatus = $(this).find('.contact-status').text().toLowerCase();
        
        if (contactName.includes(searchTerm) || contactPhone.includes(searchTerm) || contactStatus.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

/**
 * Show all items (clear search)
 */
function showAllItems() {
    $('.user-item, .whatsapp-connection-item, .contact-item').show();
}

/**
 * Initialize user selection functionality
 */
function initializeUserSelection() {
    // Handle user item clicks
    $(document).on('click', '.user-item', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        const userEmail = $(this).data('user-email');
        const channel = $(this).data('channel');
        
        // Remove active class from all user items
        $('.user-item, .whatsapp-connection-item, .contact-item').removeClass('active');
        
        // Add active class to clicked item
        $(this).addClass('active');
        
        // Update global variables
        window.currentSelectedUser = userId;
        window.currentSelectedConnection = null;
        window.currentSelectedTicket = null;
        
        // Update URL to reflect selected user
        updateURLForUser(userId);
        
        // Replace welcome state with chat room
        showChatRoom(userId, userName, userEmail, 'user');
        
        // Load chat history for this user
        if (typeof NX !== 'undefined' && typeof NX.loadChatHistory === 'function') {
            NX.loadChatHistory(userId);
        } else {
            console.error('NX.loadChatHistory function not available');
        }
        
        console.log('Selected user:', { userId, userName, userEmail, channel });
    });
    
    // Handle WhatsApp connection clicks
    $(document).on('click', '.whatsapp-connection-item', function() {
        const connectionId = $(this).data('connection-id');
        const connectionName = $(this).data('connection-name');
        const phoneNumber = $(this).data('phone-number');
        const channel = $(this).data('channel');
        
        // Remove active class from all items
        $('.user-item, .whatsapp-connection-item, .contact-item').removeClass('active');
        
        // Add active class to clicked item
        $(this).addClass('active');
        
        // Load chat history for this connection
        if (typeof NX !== 'undefined' && typeof NX.loadChatHistory === 'function') {
            NX.loadChatHistory(null, connectionId);
        } else {
            console.error('NX.loadChatHistory function not available');
        }
        
        // Update chat header
        if (typeof NX !== 'undefined' && typeof NX.updateChatHeader === 'function') {
            NX.updateChatHeader(connectionName, 'whatsapp', phoneNumber);
        } else {
            console.error('NX.updateChatHeader function not available');
        }
        
        console.log('Selected WhatsApp connection:', { connectionId, connectionName, phoneNumber, channel });
    });
    
    // Handle contact item clicks
    $(document).on('click', '.contact-item', function() {
        const ticketId = $(this).data('ticket-id');
        const contactName = $(this).data('contact-name');
        const contactPhone = $(this).data('contact-phone');
        const channel = $(this).data('channel');
        
        // Remove active class from all items
        $('.user-item, .whatsapp-connection-item, .contact-item').removeClass('active');
        
        // Add active class to clicked item
        $(this).addClass('active');
        
        // Load chat history for this contact
        if (typeof NX !== 'undefined' && typeof NX.loadChatHistory === 'function') {
            NX.loadChatHistory(null, null, ticketId);
        } else {
            console.error('NX.loadChatHistory function not available');
        }
        
        // Update chat header
        if (typeof NX !== 'undefined' && typeof NX.updateChatHeader === 'function') {
            NX.updateChatHeader(contactName, 'whatsapp', contactPhone);
        } else {
            console.error('NX.updateChatHeader function not available');
        }
        
        console.log('Selected contact:', { ticketId, contactName, contactPhone, channel });
    });
}







/**
 * Update URL to reflect selected user
 */
function updateURLForUser(userId) {
    const newUrl = `/messages/${userId}`;
    window.history.pushState({ userId: userId }, '', newUrl);
    console.log('URL updated to:', newUrl);
}

/**
 * Show chat room for selected user
 */
function showChatRoom(userId, userName, userEmail, type) {
    const contentContainer = $('#messages-content-container');
    if (!contentContainer.length) {
        console.error('Messages content container not found');
        return;
    }
    
    // Create chat room HTML
    const chatRoomHTML = `
        <div class="chat-room" data-user-id="${userId}" data-user-type="${type}" data-name="${userName}" data-company="" data-tags="">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-contact-info">
                    <div class="contact-avatar">
                        <div class="avatar-placeholder">
                            ${userName.charAt(0).toUpperCase()}
                        </div>
                        <div class="status-indicator online"></div>
                    </div>
                    <div class="contact-details">
                        <h3 class="contact-name">${userName}</h3>
                        <div class="contact-meta">
                            <span class="contact-role">User • General</span>
                            <span class="contact-email">${userEmail}</span>
                        </div>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="action-btn" title="Search in chat">
                        <i class="fas fa-search"></i>
                    </button>
                    <div class="action-menu" style="position: relative;">
                        <button class="action-btn" title="More options" id="chat_header_more_btn" onclick="window.toggleChatHeaderQuickView(event)">
                            <i class="fas fa-ellipsis-vertical"></i>
                        </button>
                        <div id="chat_header_quick_view" class="quick-view-dropdown" style="display:none; position:absolute; right:0; top:44px; width:320px; background:#ffffff; border:1px solid var(--border-color); box-shadow: var(--shadow-medium); border-radius:8px; z-index:1000; padding:12px;">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <strong style="font-size:14px; color:var(--text-primary);">Quick View</strong>
                                <button type="button" id="chat_header_close_quick_view" class="action-btn" style="width:28px; height:28px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                <label style="font-size:12px; color:var(--text-muted);">Name</label>
                                <input type="text" id="quick_name_input" class="form-control" value="${userName}" style="height:34px; font-size:13px;">

                                <label style="font-size:12px; color:var(--text-muted);">Company</label>
                                <input type="text" id="quick_company_input" class="form-control" value="" style="height:34px; font-size:13px;">

                                <label style="font-size:12px; color:var(--text-muted);">Tags (comma separated)</label>
                                <input type="text" id="quick_tags_input" class="form-control" value="" style="height:34px; font-size:13px;">

                                <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:4px;">
                                    <button type="button" id="quick_view_cancel_btn" class="btn btn-light btn-sm">Cancel</button>
                                    <button type="button" id="quick_view_save_btn" class="btn btn-primary btn-sm">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages Container -->
            <div class="chat-messages" id="chat_messages">
                <!-- Messages will be loaded here via JavaScript -->
            </div>

            <!-- Message Composer -->
            <div class="message-composer">
                <!-- Channel Selector -->
                <div class="channel-selector">
                    <label for="channel_dropdown">Channel:</label>
                    <select id="channel_dropdown" class="channel-dropdown">
                        <option value="internal">Internal</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="email">Email</option>
                    </select>
                </div>

                <!-- Message Input Area -->
                <div class="message-input-area">
                    <!-- Input Actions -->
                    <div class="input-actions">
                        <button class="input-action-btn" id="emoji_btn" title="Add emoji">
                            <i class="fas fa-smile"></i>
                        </button>
                        <button class="input-action-btn" id="file_upload_btn" title="Attach file">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>

                    <!-- Input Wrapper -->
                    <div class="input-wrapper">
                        <textarea 
                            class="message-input" 
                            id="message_input" 
                            placeholder="Type a message..." 
                            rows="1"
                            maxlength="1000"></textarea>
                    </div>

                    <!-- Send Button -->
                    <button class="send-button" id="send_message_btn" title="Send message">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>

                <!-- Input Footer -->
                <div class="input-footer">
                    <div class="input-options">
                        <label class="option-checkbox">
                            <input type="checkbox" id="save_draft">
                            <span class="checkmark"></span>
                            Save as draft
                        </label>
                    </div>
                    <div class="char-counter">
                        <span id="char_count">0</span>/1000
                    </div>
                </div>

                <!-- Templates Menu (Hidden by default) -->
                <div class="templates-dropdown" id="templates_menu" style="display: none;">
                    <div class="template-item" data-template="Hello! How can I help you today?">
                        <span class="template-text">Hello! How can I help you today?</span>
                    </div>
                    <div class="template-item" data-template="Thank you for contacting us. We'll get back to you soon.">
                        <span class="template-text">Thank you for contacting us. We'll get back to you soon.</span>
                    </div>
                    <div class="template-item" data-template="Is there anything else I can help you with?">
                        <span class="template-text">Is there anything else I can help you with?</span>
                    </div>
                </div>

                <!-- Emoji Picker (Hidden by default) -->
                <div class="emoji-picker" id="emoji_picker" style="display: none;" onclick="event.stopPropagation();">
                    <div class="emoji-header">
                        <span>Select Emoji</span>
                        <button class="close-emoji" id="close_emoji" onclick="document.getElementById('emoji_picker').style.display = 'none';">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Emoji Category Tabs -->
                    <div class="emoji-tabs">
                        <button class="emoji-tab active" data-category="smileys" title="Smileys & People" onclick="window.switchEmojiCategory('smileys', this)">
                            <span class="tab-icon">😀</span>
                            <span class="tab-label">Smileys</span>
                        </button>
                        <button class="emoji-tab" data-category="gestures" title="Gestures & Body Parts" onclick="window.switchEmojiCategory('gestures', this)">
                            <span class="tab-icon">👋</span>
                            <span class="tab-label">Gestures</span>
                        </button>
                        <button class="emoji-tab" data-category="activities" title="Activities & Events" onclick="window.switchEmojiCategory('activities', this)">
                            <span class="tab-icon">🎉</span>
                            <span class="tab-label">Activities</span>
                        </button>
                        <button class="emoji-tab" data-category="travel" title="Travel & Places" onclick="window.switchEmojiCategory('travel', this)">
                            <span class="tab-icon">🚀</span>
                            <span class="tab-label">Travel</span>
                        </button>
                        <button class="emoji-tab" data-category="objects" title="Objects & Symbols" onclick="window.switchEmojiCategory('objects', this)">
                            <span class="tab-icon">🎨</span>
                            <span class="tab-label">Objects</span>
                        </button>
                        <button class="emoji-tab" data-category="nature" title="Nature & Weather" onclick="window.switchEmojiCategory('nature', this)">
                            <span class="tab-icon">🌟</span>
                            <span class="tab-label">Nature</span>
                        </button>
                        <button class="emoji-tab" data-category="music" title="Music & Entertainment" onclick="window.switchEmojiCategory('music', this)">
                            <span class="tab-icon">🎵</span>
                            <span class="tab-label">Music</span>
                        </button>
                        <button class="emoji-tab" data-category="love" title="Love & Hearts" onclick="window.switchEmojiCategory('love', this)">
                            <span class="tab-icon">💝</span>
                            <span class="tab-label">Love</span>
                        </button>
                        <button class="emoji-tab" data-category="sports" title="Sports & Games" onclick="window.switchEmojiCategory('sports', this)">
                            <span class="tab-icon">🎯</span>
                            <span class="tab-label">Sports</span>
                        </button>
                        <button class="emoji-tab" data-category="flags" title="Flags & Symbols" onclick="window.switchEmojiCategory('flags', this)">
                            <span class="tab-icon">🏁</span>
                            <span class="tab-label">Flags</span>
                        </button>
                    </div>
                    
                    <!-- Emoji Grid Container -->
                    <div class="emoji-content">
                        <div class="emoji-grid" id="emoji_grid">
                            <!-- Emojis will be populated by JavaScript based on selected category -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Replace content with chat room
    contentContainer.html(chatRoomHTML);
    
    // Initialize message composer functionality
    initializeMessageComposer();
    
    // Update chat header
    if (typeof NX !== 'undefined' && typeof NX.updateChatHeader === 'function') {
        NX.updateChatHeader(userName, 'user');
    }
    
    console.log('Chat room displayed for user:', { userId, userName, userEmail, type });
}

/**
 * Initialize message composer functionality
 */
function initializeMessageComposer() {
    const messageInput = $('#message_input');
    const sendButton = $('#send_message_btn');
    const emojiButton = $('#emoji_btn');
    const attachmentButton = $('#file_upload_btn');
    const charCounter = $('#char_count');
    const channelSelect = $('#channel_dropdown');
    
    // Auto-resize textarea
    if (messageInput.length) {
        messageInput.on('input', function() {
            // Auto-resize
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            
            // Update character counter
            const charCount = $(this).val().length;
            if (charCounter.length) {
                charCounter.text(charCount);
                
                // Add warning/error classes
                charCounter.removeClass('warning error');
                if (charCount > 800) {
                    charCounter.addClass('warning');
                }
                if (charCount > 950) {
                    charCounter.addClass('error');
                }
            }
            
            // Enable/disable send button
            if (sendButton.length) {
                if (charCount > 0) {
                    sendButton.prop('disabled', false);
                } else {
                    sendButton.prop('disabled', true);
                }
            }
        });
        
        // Handle Enter key
        messageInput.on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Send button click
    if (sendButton.length) {
        sendButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            if (typeof NX !== 'undefined' && typeof NX.sendMessage === 'function') {
                NX.sendMessage();
            } else {
                console.error('NX.sendMessage function not available');
                alert('Message function not available. Please refresh the page.');
            }
            
            return false;
        });
    }
    
    // Emoji button click
    if (emojiButton.length) {
        emojiButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Emoji button clicked in messages-search.js');
            
            const emojiPicker = $('#emoji_picker');
            if (emojiPicker.is(':visible')) {
                console.log('Hiding emoji picker in messages-search.js');
                emojiPicker.hide();
            } else {
                console.log('Showing emoji picker in messages-search.js');
                emojiPicker.show();
                
                // Wait a moment for the DOM to be ready, then initialize
                setTimeout(function() {
                    console.log('Initializing emoji picker after timeout in messages-search.js');
                    
                    // Check if elements exist
                    console.log('Emoji picker exists:', $('#emoji_picker').length > 0);
                    console.log('Emoji tabs exist:', $('#emoji_picker .emoji-tab').length);
                    console.log('Emoji grid exists:', $('#emoji_grid').length > 0);
                    
                    // Don't call NX.initializeEmojiTabs() here as it conflicts with messages.js
                    // Using direct onclick handlers instead of event delegation
                    console.log('Using direct onclick handlers for emoji tabs (no conflicts)');
                    
                    // Populate with default category (smileys)
                    if (typeof NX !== 'undefined' && typeof NX.populateEmojiGrid === 'function') {
                        console.log('Calling NX.populateEmojiGrid with smileys from messages-search.js');
                        NX.populateEmojiGrid('smileys');
                    } else {
                        console.error('NX.populateEmojiGrid function not found in messages-search.js!');
                    }
                    
                    // Emoji tab handlers are now bound globally, no need to bind here
                }, 100);
            }
        });
    }
    
    // Attachment button click
    if (attachmentButton.length) {
        attachmentButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (typeof NX !== 'undefined' && typeof NX.openFileUpload === 'function') {
                NX.openFileUpload();
            } else {
                console.error('NX.openFileUpload function not available');
                alert('File upload function not available. Please refresh the page.');
            }
        });
    }
    
    // Channel select change
    if (channelSelect.length) {
        channelSelect.on('change', function() {
            const selectedChannel = $(this).val();
            if (typeof NX !== 'undefined' && typeof NX.updateChannelSelector === 'function') {
                NX.updateChannelSelector(selectedChannel);
            }
            console.log('Channel changed to:', selectedChannel);
        });
    }
    
    // Close emoji picker when clicking outside
    $(document).off('click.messages.outside').on('click.messages.outside', function(e) {
        if (!$(e.target).closest('#emoji_picker, #emoji_btn').length) {
            $('#emoji_picker').hide();
        }
    });

    // Close emoji picker button
    $(document).off('click.messages', '#close_emoji').on('click.messages', '#close_emoji', function() {
        $('#emoji_picker').hide();
    });

    // Emoji selection
    $(document).off('click.messages', '.emoji').on('click.messages', '.emoji', function() {
        const emoji = $(this).data('emoji') || $(this).text();
        if (emoji && typeof NX !== 'undefined' && typeof NX.insertEmoji === 'function') {
            NX.insertEmoji(emoji);
        }
    });
    
    // Initialize NX emoji functions if they don't exist
    if (typeof NX === 'undefined') {
        window.NX = {};
    }
    
    // Add emoji categories if not already defined
    if (!NX.emojiCategories) {
        NX.emojiCategories = {
            smileys: [
                '😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚',
                '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😣',
                '😖', '😫', '😩', '🥺', '😢', '😭', '😤', '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱', '😨', '😰', '😥', '😓', '🤗',
                '🤔', '🤭', '🤫', '🤥', '😶', '😐', '😑', '😯', '😦', '😧', '😮', '😲', '🥱', '😴', '🤤', '😪', '😵', '🤐', '🥴', '🤢'
            ],
            gestures: [
                '👍', '👎', '👌', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👋', '🤚', '🖐️', '✋', '🖖', '👏',
                '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💅', '🤳', '💪', '🦾', '🦿', '🦵', '🦶', '👂', '🦻', '👃', '🧠', '🦷', '🦴', '👀', '👁️'
            ],
            activities: [
                '🎉', '🎊', '🎈', '🎁', '🎀', '🎂', '🍰', '🧁', '🍭', '🍬', '🍫', '🍩', '🍪', '🍯', '🍺', '🍻', '🥂', '🍷', '🍸', '🍹',
                '🍾', '🥃', '🍵', '☕', '🥤', '🧃', '🧉', '🧊', '🍼', '🥛', '🍯', '🧈', '🧀', '🥚', '🍳', '🥞', '🧇', '🥓', '🥩', '🍗'
            ],
            travel: [
                '🚀', '✈️', '🛫', '🛬', '🛩️', '💺', '🛰️', '🚁', '🚂', '🚃', '🚄', '🚅', '🚆', '🚇', '🚈', '🚉', '🚊', '🚝', '🚞', '🚋',
                '🚌', '🚍', '🚎', '🚐', '🚑', '🚒', '🚓', '🚔', '🚕', '🚖', '🚗', '🚘', '🚙', '🚚', '🚛', '🚜', '🏎️', '🏍️', '🛵', '🚲'
            ],
            objects: [
                '🎨', '🎭', '🎪', '🎯', '🎲', '🎳', '🎮', '🕹️', '🎰', '🧩', '🎱', '🔮', '🪄', '📱', '📲', '💻', '🖥️', '🖨️', '⌨️', '🖱️',
                '🖲️', '💽', '💾', '💿', '📀', '📼', '📷', '📸', '📹', '🎥', '📽️', '🎞️', '📞', '☎️', '📟', '📠', '📺', '📻', '🎙️', '🎚️'
            ],
            nature: [
                '🌟', '⭐', '🌠', '☀️', '🌤️', '⛅', '🌥️', '☁️', '🌦️', '🌧️', '⛈️', '🌩️', '🌨️', '❄️', '☃️', '⛄', '🌬️', '💨', '🌪️', '🌫️',
                '🌈', '☔', '☂️', '🌊', '🌋', '🌍', '🌎', '🌏', '🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘', '🌙', '🌚', '🌛', '🌜'
            ],
            music: [
                '🎵', '🎶', '🎤', '🎧', '📻', '🎷', '🎺', '🎸', '🪕', '🎻', '🪗', '🎹', '🥁', '🪘', '🎼', '🎹', '🎸', '🎺', '🎷', '🎤',
                '🎧', '📻', '🎵', '🎶', '🎼', '🎹', '🎸', '🎺', '🎷', '🎤', '🎧', '📻', '🎵', '🎶', '🎼', '🎹', '🎸', '🎺', '🎷', '🎤'
            ],
            love: [
                '💝', '💖', '💗', '💓', '💞', '💕', '💟', '❣️', '💔', '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💯', '💢',
                '💥', '💫', '💦', '💨', '🕳️', '💣', '💤', '💢', '💥', '💫', '💦', '💨', '🕳️', '💣', '💤', '💢', '💥', '💫', '💦', '💨'
            ],
            sports: [
                '🎯', '🎲', '🎳', '🎮', '🕹️', '🎰', '🧩', '🎱', '🔮', '🪄', '⚽', '🏀', '🏈', '⚾', '🥎', '🎾', '🏐', '🏉', '🎱', '🪀',
                '🏓', '🏸', '🏒', '🏑', '🥍', '🏏', '🪃', '🥅', '⛳', '🪁', '🏹', '🎣', '🤿', '🥊', '🥋', '🎽', '🛹', '🛷', '⛸️', '🥌'
            ],
            flags: [
                '🏁', '🚩', '🎌', '🏴', '🏳️', '🏳️‍🌈', '🏳️‍⚧️', '🏴‍☠️', '🇦🇨', '🇦🇩', '🇦🇪', '🇦🇫', '🇦🇬', '🇦🇮', '🇦🇱', '🇦🇲', '🇦🇴', '🇦🇶', '🇦🇷', '🇦🇸',
                '🇦🇹', '🇦🇺', '🇦🇼', '🇦🇽', '🇦🇿', '🇧🇦', '🇧🇧', '🇧🇩', '🇧🇪', '🇧🇫', '🇧🇬', '🇧🇭', '🇧🇮', '🇧🇯', '🇧🇱', '🇧🇲', '🇧🇳', '🇧🇴', '🇧🇶', '🇧🇷'
            ]
        };
    }
    
    // Add populateEmojiGrid function if not already defined
    if (!NX.populateEmojiGrid) {
        NX.populateEmojiGrid = function(category = 'smileys') {
            console.log('NX.populateEmojiGrid called with category:', category);
            
            const emojiGrid = $('#emoji_grid');
            if (!emojiGrid.length) {
                console.error('Emoji grid not found!');
                return;
            }
            
            console.log('Emoji grid found, length:', emojiGrid.length);
            
            // Check if emoji categories exist
            if (!NX.emojiCategories) {
                console.error('NX.emojiCategories not defined!');
                return;
            }
            
            // Get emojis for the selected category
            const emojis = NX.emojiCategories[category] || NX.emojiCategories.smileys;
            if (!emojis || !Array.isArray(emojis)) {
                console.error('No emojis found for category:', category);
                return;
            }
            
            console.log('Emojis for category', category, ':', emojis.length, 'emojis');
            
            emojiGrid.empty();
            
            emojis.forEach(function(emoji, index) {
                // Create beautiful emoji with modern styling
                const emojiHtml = `
                    <div class="emoji" 
                         data-emoji="${emoji}" 
                         title="${emoji}"
                         style="cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 24px; border-radius: 12px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; background: transparent; border: 2px solid transparent;"
                         onmouseover="this.style.transform='scale(1.15) translateY(-2px)'; this.style.borderColor='#3b82f6'; this.style.boxShadow='0 8px 20px rgba(59, 130, 246, 0.3), 0 4px 8px rgba(0, 0, 0, 0.1)'"
                         onmouseout="this.style.transform='scale(1) translateY(0)'; this.style.borderColor='transparent'; this.style.boxShadow='none'"
                         onclick="NX.insertEmoji('${emoji}')">
                        <span style="position: relative; z-index: 1; transition: transform 0.3s ease;">${emoji}</span>
                    </div>
                `;
                emojiGrid.append(emojiHtml);
            });
            
            console.log('Emoji grid populated with', emojis.length, 'emojis for category:', category);
        };
    }
    
    // Add insertEmoji function if not already defined
    if (!NX.insertEmoji) {
        NX.insertEmoji = function(emoji) {
            const messageInput = $('#message_input');
            if (!messageInput.length) {
                return;
            }
            
            const input = messageInput[0];
            const currentValue = input.value;
            const cursorPos = input.selectionStart;
            
            // Insert emoji at cursor position
            const newValue = currentValue.slice(0, cursorPos) + emoji + currentValue.slice(cursorPos);
            input.value = newValue;
            
            // Set cursor position after the inserted emoji
            const newCursorPos = cursorPos + emoji.length;
            input.setSelectionRange(newCursorPos, newCursorPos);
            
            // Trigger input event to update UI
            messageInput.trigger('input');
            
            // Hide emoji picker
            $('#emoji_picker').hide();
            
            // Focus back to input
            input.focus();
        };
    }
    
    // Add global emoji category switching function
    window.switchEmojiCategory = function(category, clickedElement) {
        console.log('switchEmojiCategory called with category:', category);
        
        // Add a simple alert to verify the function is being called
        console.log('✅ Emoji category switch triggered:', category);
        
        // Add visual feedback - briefly highlight the clicked tab
        $(clickedElement).addClass('switching');
        setTimeout(() => {
            $(clickedElement).removeClass('switching');
        }, 200);
        
        // Remove active class from all tabs
        $('#emoji_picker .emoji-tab').removeClass('active');
        
        // Add active class to clicked tab with a slight delay for smooth transition
        setTimeout(() => {
            $(clickedElement).addClass('active');
        }, 50);
        
        // Populate emoji grid with selected category
        if (typeof NX !== 'undefined' && typeof NX.populateEmojiGrid === 'function') {
            console.log('Calling NX.populateEmojiGrid with category:', category);
            NX.populateEmojiGrid(category);
        } else {
            console.error('NX.populateEmojiGrid function not available!');
        }
        
        return false; // Prevent default behavior
    };

    // Template selection
    $(document).off('click.messages', '.template-item').on('click.messages', '.template-item', function() {
        const templateText = $(this).data('template');
        const messageInput = $('#message_input');
        messageInput.val(templateText);
        messageInput.trigger('input');
        $('#templates_menu').hide();
    });
}


