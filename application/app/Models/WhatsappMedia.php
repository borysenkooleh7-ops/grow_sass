<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappMedia extends Model
{
    protected $table = 'whatsapp_media';

    protected $fillable = [
        'message_id',
        'ticket_id',
        'type',
        'filename',
        'extension',
        'mime_type',
        'size',
        'url',
        'thumbnail_url',
        'sender_type',
        'sender_id',
        'sender_name'
    ];

    /**
     * Get the message this media belongs to
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(WhatsappMessage::class, 'message_id');
    }

    /**
     * Get the ticket this media belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(WhatsappTicket::class, 'ticket_id');
    }

    /**
     * Get the sender (User)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope by media type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope images only
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope videos only
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope documents only
     */
    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }

    /**
     * Scope audio only
     */
    public function scopeAudio($query)
    {
        return $query->where('type', 'audio');
    }

    /**
     * Get human readable file size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if media is an image
     */
    public function isImage()
    {
        return $this->type === 'image';
    }

    /**
     * Check if media is a video
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Check if media is a document
     */
    public function isDocument()
    {
        return $this->type === 'document';
    }

    /**
     * Check if media is audio
     */
    public function isAudio()
    {
        return $this->type === 'audio';
    }
}
