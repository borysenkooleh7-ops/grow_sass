{{-- Conversation Header Content --}}
{{-- This is rendered inside the panel header --}}

<img src="{{ $contact->avatar_url }}" alt="{{ $contact->display_name }}" class="contact-avatar">
<div style="flex: 1;">
    <div class="contact-name">{{ $contact->display_name }}</div>
    <div class="contact-phone">{{ $contact->whatsappcontact_phone }}</div>
    @if($ticket->client)
        <div style="font-size: 11px; opacity: 0.8;">
            <i class="ti-briefcase"></i> {{ $ticket->client->client_company_name }}
        </div>
    @endif
</div>
<div style="text-align: right; font-size: 11px;">
    <div>
        <span class="label label-{{ $ticket->whatsappticket_status === 'open' ? 'success' : ($ticket->whatsappticket_status === 'on_hold' ? 'warning' : 'default') }}">
            {{ ucfirst(str_replace('_', ' ', $ticket->whatsappticket_status)) }}
        </span>
    </div>
    @if($ticket->assignedAgent)
        <div style="margin-top: 5px; opacity: 0.9;">
            <i class="ti-user"></i> {{ $ticket->assignedAgent->first_name }}
        </div>
    @else
        <div style="margin-top: 5px; opacity: 0.9;">
            <i class="ti-user"></i> Unassigned
        </div>
    @endif
</div>
