/**
 * WhatsApp Conversation Panel JavaScript
 * Handles all WhatsApp panel interactions and real-time messaging
 */

// Global variables
let pollingInterval = null;
let lastMessageId = 0;
let userScrolledUp = false;

/**
 * Open WhatsApp conversation panel for a task
 * @param {number} taskId - The task ID to load conversation for
 */
function openWhatsappConversation(taskId) {
    // Reset state
    lastMessageId = 0;
    userScrolledUp = false;

    // Show panel
    const panel = document.getElementById('whatsapp-panel');
    panel.classList.add('active');

    // Store current task ID
    document.getElementById('current-task-id').value = taskId;

    // Load conversation
    loadConversation(taskId);

    // Start polling for new messages
    startMessagePolling(taskId);
}

/**
 * Close WhatsApp panel
 */
function closeWhatsappPanel() {
    const panel = document.getElementById('whatsapp-panel');
    panel.classList.remove('active');

    // Stop polling
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }

    // Reset hidden dropdown
    document.getElementById('settings-dropdown').classList.remove('active');
}

/**
 * Load conversation from server
 * @param {number} taskId - The task ID to load
 */
function loadConversation(taskId) {
    $.ajax({
        url: `/whatsapp/conversation/task/${taskId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Update header
                document.getElementById('panel-contact-info').innerHTML = response.header_html;

                // Update messages
                document.getElementById('panel-messages').innerHTML = response.messages_html;

                // Store ticket ID
                document.getElementById('current-ticket-id').value = response.ticket.whatsappticket_id;

                // Get last message ID for polling
                const messageElements = document.querySelectorAll('.message-bubble[data-message-id]');
                if (messageElements.length > 0) {
                    const lastElement = messageElements[messageElements.length - 1];
                    lastMessageId = parseInt(lastElement.getAttribute('data-message-id'));
                }

                // Scroll to bottom
                scrollMessagesToBottom();

                // Check 24-hour window and show warning if needed
                if (!response.within_24h_window) {
                    showWindowExpiredWarning();
                }
            } else {
                alert(response.error || 'Failed to load conversation');
                closeWhatsappPanel();
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Failed to load conversation'));
            closeWhatsappPanel();
        }
    });
}

/**
 * Send WhatsApp message
 * @param {Event} event - Form submit event
 */
function sendWhatsappMessage(event) {
    event.preventDefault();

    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();

    if (!message) {
        return;
    }

    const ticketId = document.getElementById('current-ticket-id').value;
    const channel = document.querySelector('input[name="channel"]:checked').value;
    const isInternalNote = document.getElementById('is-internal-note').checked ? 1 : 0;

    // Disable input
    messageInput.disabled = true;

    $.ajax({
        url: '/whatsapp/messages/send',
        method: 'POST',
        data: {
            ticket_id: ticketId,
            content: message,
            channel: channel,
            is_internal_note: isInternalNote,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Append message to conversation
                const messagesContainer = document.getElementById('panel-messages');
                messagesContainer.insertAdjacentHTML('beforeend', response.message_html);

                // Update last message ID
                lastMessageId = response.message.whatsappmessage_id;

                // Clear input
                messageInput.value = '';

                // Scroll to bottom
                scrollMessagesToBottom();

                // Reset internal note checkbox
                document.getElementById('is-internal-note').checked = false;
            } else {
                if (response.code === 'WINDOW_EXPIRED') {
                    // Suggest using email
                    if (confirm(response.error + '\n\nWould you like to send via Email instead?')) {
                        document.querySelector('input[name="channel"][value="email"]').checked = true;
                    }
                } else {
                    alert(response.error || 'Failed to send message');
                }
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Failed to send message'));
        },
        complete: function() {
            // Re-enable input
            messageInput.disabled = false;
            messageInput.focus();
        }
    });
}

/**
 * Handle message input keydown (Ctrl+Enter to send)
 * @param {Event} event - Keydown event
 */
function handleMessageInputKeydown(event) {
    if (event.ctrlKey && event.key === 'Enter') {
        event.preventDefault();
        sendWhatsappMessage(event);
    }

    // ESC to close panel
    if (event.key === 'Escape') {
        closeWhatsappPanel();
    }
}

/**
 * Scroll messages container to bottom
 */
function scrollMessagesToBottom() {
    const messagesContainer = document.getElementById('panel-messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    userScrolledUp = false;

    // Hide new message notification
    document.getElementById('new-message-notification').classList.remove('show');
}

/**
 * Detect when user scrolls up (to show new message notification)
 */
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('panel-messages');
    if (messagesContainer) {
        messagesContainer.addEventListener('scroll', function() {
            const scrollBottom = this.scrollHeight - this.scrollTop - this.clientHeight;
            userScrolledUp = scrollBottom > 50; // Consider scrolled up if more than 50px from bottom
        });
    }
});

/**
 * Start polling for new messages
 * @param {number} taskId - The task ID to poll for
 */
function startMessagePolling(taskId) {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }

    pollingInterval = setInterval(function() {
        pollForNewMessages(taskId);
    }, 5000); // Poll every 5 seconds
}

/**
 * Poll for new messages
 * @param {number} taskId - The task ID to poll for
 */
function pollForNewMessages(taskId) {
    if (!lastMessageId) {
        return; // No messages loaded yet
    }

    $.ajax({
        url: `/whatsapp/conversation/task/${taskId}/poll`,
        method: 'GET',
        data: {
            last_message_id: lastMessageId
        },
        success: function(response) {
            if (response.success && response.has_new_messages) {
                // Append new messages
                const messagesContainer = document.getElementById('panel-messages');
                messagesContainer.insertAdjacentHTML('beforeend', response.messages_html);

                // Update last message ID
                lastMessageId = response.last_message_id;

                // If user is at bottom, scroll down; otherwise show notification
                if (userScrolledUp) {
                    showNewMessageNotification(response.count);
                } else {
                    scrollMessagesToBottom();
                }
            }
        },
        error: function(xhr) {
            console.error('Polling error:', xhr);
        }
    });
}

/**
 * Show new message notification
 * @param {number} count - Number of new messages
 */
function showNewMessageNotification(count) {
    const notification = document.getElementById('new-message-notification');
    document.getElementById('new-message-count').textContent = count;
    notification.classList.add('show');
}

/**
 * Toggle settings dropdown
 */
function toggleSettingsDropdown() {
    const dropdown = document.getElementById('settings-dropdown');
    dropdown.classList.toggle('active');
}

/**
 * Close settings dropdown when clicking outside
 */
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('settings-dropdown');
    const settingsButton = document.querySelector('.settings-button');

    if (dropdown && !dropdown.contains(event.target) && event.target !== settingsButton) {
        dropdown.classList.remove('active');
    }
});

/**
 * Assign ticket to current user
 */
function assignTicketToMe() {
    const ticketId = document.getElementById('current-ticket-id').value;
    const userId = $('meta[name="user-id"]').attr('content'); // Assuming user ID is in meta tag

    if (!userId) {
        alert('User ID not found');
        return;
    }

    $.ajax({
        url: `/whatsapp/tickets/${ticketId}/assign`,
        method: 'POST',
        data: {
            user_id: userId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Reload conversation to show updated assignment
                const taskId = document.getElementById('current-task-id').value;
                loadConversation(taskId);

                // Close dropdown
                document.getElementById('settings-dropdown').classList.remove('active');
            } else {
                alert(response.error || 'Failed to assign ticket');
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Failed to assign ticket'));
        }
    });
}

/**
 * Update ticket status
 * @param {string} status - New status (on_hold, open, resolved, closed)
 */
function updateTicketStatus(status) {
    const ticketId = document.getElementById('current-ticket-id').value;

    $.ajax({
        url: `/whatsapp/tickets/${ticketId}/status`,
        method: 'POST',
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Reload conversation to show updated status
                const taskId = document.getElementById('current-task-id').value;
                loadConversation(taskId);

                // Close dropdown
                document.getElementById('settings-dropdown').classList.remove('active');

                // Show success message
                alert('Status updated to: ' + status.replace('_', ' '));
            } else {
                alert(response.error || 'Failed to update status');
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Failed to update status'));
        }
    });
}

/**
 * Trigger file input click
 */
function attachFile() {
    document.getElementById('file-input').click();
}

/**
 * Handle file selection
 * @param {Event} event - File input change event
 */
function handleFileSelect(event) {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    // Validate file size (16MB max)
    if (file.size > 16 * 1024 * 1024) {
        alert('File size must be less than 16MB');
        return;
    }

    const ticketId = document.getElementById('current-ticket-id').value;

    // Create FormData
    const formData = new FormData();
    formData.append('file', file);
    formData.append('ticket_id', ticketId);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    // Show uploading message
    const messageInput = document.getElementById('message-input');
    messageInput.placeholder = 'Uploading file...';
    messageInput.disabled = true;

    $.ajax({
        url: '/whatsapp/upload',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                // Append message to conversation
                const messagesContainer = document.getElementById('panel-messages');
                messagesContainer.insertAdjacentHTML('beforeend', response.message_html);

                // Update last message ID
                lastMessageId = response.message.whatsappmessage_id;

                // Scroll to bottom
                scrollMessagesToBottom();
            } else {
                alert(response.error || 'Failed to upload file');
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Failed to upload file'));
        },
        complete: function() {
            // Reset file input and message input
            messageInput.placeholder = 'Type a message...';
            messageInput.disabled = false;
            event.target.value = '';
        }
    });
}

/**
 * Show warning when 24-hour window has expired
 */
function showWindowExpiredWarning() {
    // Switch to email channel
    document.querySelector('input[name="channel"][value="email"]').checked = true;

    // Show warning in composer
    const messageInput = document.getElementById('message-input');
    messageInput.placeholder = 'WhatsApp 24h window expired. Sending via Email...';
}

/**
 * Handle channel change (WhatsApp <-> Email)
 */
document.addEventListener('DOMContentLoaded', function() {
    const channelInputs = document.querySelectorAll('input[name="channel"]');
    channelInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const messageInput = document.getElementById('message-input');
            if (this.value === 'whatsapp') {
                messageInput.placeholder = 'Type a message...';
            } else {
                messageInput.placeholder = 'Type an email message...';
            }
        });
    });
});
