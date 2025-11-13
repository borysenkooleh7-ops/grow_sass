<?php

namespace App\Helpers;

use App\Models\WhatsappConnection;
use App\Models\WhatsappTicket;
use App\Models\WhatsappContact;
use Carbon\Carbon;

class WhatsappHelper
{
    /**
     * Format phone number to international format
     */
    public static function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add + prefix if not present
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Validate phone number format
     */
    public static function isValidPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Check if length is between 10-15 digits
        return strlen($cleaned) >= 10 && strlen($cleaned) <= 15;
    }

    /**
     * Get active connection
     */
    public static function getActiveConnection()
    {
        return WhatsappConnection::active()->connected()->first();
    }

    /**
     * Get all active connections
     */
    public static function getActiveConnections()
    {
        return WhatsappConnection::active()->connected()->get();
    }

    /**
     * Format timestamp to human readable
     */
    public static function timeAgo($timestamp)
    {
        if (!$timestamp) {
            return 'Never';
        }

        $carbon = $timestamp instanceof Carbon ? $timestamp : Carbon::parse($timestamp);

        $diff = $carbon->diffInSeconds(now());

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' ' . str_plural('minute', $minutes) . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' ' . str_plural('hour', $hours) . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' ' . str_plural('day', $days) . ' ago';
        } else {
            return $carbon->format('M d, Y');
        }
    }

    /**
     * Get ticket status badge color
     */
    public static function getStatusColor($status)
    {
        $colors = [
            'open' => 'info',
            'in_progress' => 'warning',
            'closed' => 'success',
            'on_hold' => 'secondary',
            'resolved' => 'primary'
        ];

        return $colors[$status] ?? 'default';
    }

    /**
     * Get priority badge color
     */
    public static function getPriorityColor($priority)
    {
        $colors = [
            'low' => 'secondary',
            'normal' => 'info',
            'high' => 'warning',
            'urgent' => 'danger'
        ];

        return $colors[$priority] ?? 'default';
    }

    /**
     * Get SLA status color
     */
    public static function getSlaStatusColor($status)
    {
        $colors = [
            'met' => 'success',
            'at_risk' => 'warning',
            'breached' => 'danger'
        ];

        return $colors[$status] ?? 'default';
    }

    /**
     * Format file size
     */
    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Format duration in seconds to human readable
     */
    public static function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . 's';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . 'm ' . ($seconds % 60) . 's';
        } elseif ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . 'h ' . $minutes . 'm';
        } else {
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            return $days . 'd ' . $hours . 'h';
        }
    }

    /**
     * Check if message is within 24-hour window for WhatsApp Business API
     */
    public static function isWithin24HourWindow($lastMessageTime)
    {
        if (!$lastMessageTime) {
            return false;
        }

        $carbon = $lastMessageTime instanceof Carbon ? $lastMessageTime : Carbon::parse($lastMessageTime);
        return $carbon->diffInHours(now()) < 24;
    }

    /**
     * Generate WhatsApp deep link
     */
    public static function generateWhatsAppLink($phone, $message = null)
    {
        $phone = self::formatPhoneNumber($phone);
        $phone = str_replace('+', '', $phone);

        $url = "https://wa.me/{$phone}";

        if ($message) {
            $url .= '?text=' . urlencode($message);
        }

        return $url;
    }

    /**
     * Extract variables from template message
     */
    public static function extractTemplateVariables($message)
    {
        preg_match_all('/\{\{(\w+)\}\}/', $message, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Replace template variables
     */
    public static function replaceTemplateVariables($message, array $variables)
    {
        foreach ($variables as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }

        return $message;
    }

    /**
     * Sanitize message content
     */
    public static function sanitizeMessage($message)
    {
        // Remove potentially harmful content
        $message = strip_tags($message);

        // Remove excessive whitespace
        $message = preg_replace('/\s+/', ' ', $message);

        return trim($message);
    }

    /**
     * Check if contact is blocked
     */
    public static function isContactBlocked($phone)
    {
        $contact = WhatsappContact::where('phone_number', $phone)->first();
        return $contact ? $contact->is_blocked : false;
    }

    /**
     * Get ticket statistics summary
     */
    public static function getTicketStatsSummary()
    {
        $total = WhatsappTicket::count();
        $open = WhatsappTicket::where('status', 'open')->count();
        $inProgress = WhatsappTicket::where('status', 'in_progress')->count();
        $closed = WhatsappTicket::where('status', 'closed')->count();
        $unassigned = WhatsappTicket::whereNull('agent_id')
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        return compact('total', 'open', 'inProgress', 'closed', 'unassigned');
    }

    /**
     * Generate unique session ID
     */
    public static function generateSessionId()
    {
        return uniqid('wa_', true);
    }

    /**
     * Parse webhook payload
     */
    public static function parseWebhookPayload($payload)
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        return $payload ?? [];
    }

    /**
     * Get media icon class
     */
    public static function getMediaIconClass($type)
    {
        $icons = [
            'image' => 'fa-image',
            'video' => 'fa-video',
            'document' => 'fa-file',
            'audio' => 'fa-microphone',
        ];

        return $icons[$type] ?? 'fa-file';
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $append = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . $append;
    }

    /**
     * Check if within business hours
     */
    public static function isWithinBusinessHours($businessHours = null)
    {
        if (!$businessHours) {
            // Default: 9 AM - 6 PM weekdays
            $now = now();
            $dayOfWeek = $now->dayOfWeek; // 0 = Sunday, 6 = Saturday

            // Weekend
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                return false;
            }

            $hour = $now->hour;
            return $hour >= 9 && $hour < 18;
        }

        // Custom business hours logic
        $now = now();
        $dayName = strtolower($now->format('l'));

        if (!isset($businessHours[$dayName])) {
            return false;
        }

        $dayHours = $businessHours[$dayName];

        if ($dayHours['is_closed'] ?? false) {
            return false;
        }

        $currentTime = $now->format('H:i');
        return $currentTime >= $dayHours['open'] && $currentTime <= $dayHours['close'];
    }

    /**
     * Format markdown for WhatsApp
     */
    public static function formatWhatsAppMarkdown($text)
    {
        // Bold: *text*
        // Italic: _text_
        // Strikethrough: ~text~
        // Monospace: ```text```

        $text = preg_replace('/\*\*(.*?)\*\*/', '*$1*', $text); // Convert ** to *
        $text = preg_replace('/__(.*?)__/', '_$1_', $text); // Convert __ to _

        return $text;
    }
}
