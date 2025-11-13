<?php

/**
 * @fileoverview WhatsApp Tag Model
 * @description Tags for organizing WhatsApp contacts
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTag extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_tags';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsapptag_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsapptag_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsapptag_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsapptag_uniqueid',
        'whatsapptag_name',
        'whatsapptag_color',
    ];
}
