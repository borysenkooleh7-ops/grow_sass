{{-- Messages Container --}}
{{-- Renders all messages in the conversation --}}

@if($messages->isEmpty())
    <div style="text-align: center; padding: 50px 20px; color: #667781;">
        <i class="fab fa-whatsapp" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
        <div>No messages yet. Start the conversation!</div>
    </div>
@else
    @foreach($messages as $message)
        @include('pages.whatsapp.components.message-item', ['message' => $message, 'ticket' => $ticket])
    @endforeach
@endif
