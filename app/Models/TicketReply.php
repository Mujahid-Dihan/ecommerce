<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    //

    public function ticket() { return $this->belongsTo(SupportTicket::class, 'support_ticket_id'); }
    public function user() { return $this->belongsTo(User::class); }

}
