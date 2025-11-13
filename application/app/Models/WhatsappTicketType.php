<?php

/**
 * @fileoverview WhatsApp Ticket Type Model
 * @description Categories for WhatsApp tickets (Support, Sales, etc.)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTicketType extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'whatsapp_ticket_types';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'whatsapptickettype_id';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'whatsapptickettype_created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'whatsapptickettype_updated';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'whatsapptickettype_uniqueid',
        'whatsapptickettype_name',
        'whatsapptickettype_color',
        'whatsapptickettype_icon',
    ];

    /**
     * Relationship: Type has many Tickets
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\WhatsappTicket', 'whatsappticket_typeid', 'whatsapptickettype_id');
    }
}
