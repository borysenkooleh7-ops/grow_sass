<!--Quick Reply Component-->
<div class="whatsapp-quick-reply-section">
    <div class="quick-reply-header">
        <h6>{{ cleanLang(__('lang.quick_replies')) }}</h6>
        <button class="btn btn-xs btn-primary" onclick="addQuickReply()">
            <i class="ti-plus"></i> {{ cleanLang(__('lang.add')) }}
        </button>
    </div>

    <div class="quick-reply-list" id="quick-reply-list">
        @if(isset($quick_replies) && count($quick_replies) > 0)
            @foreach($quick_replies as $reply)
            <div class="quick-reply-item" data-id="{{ $reply->id }}" onclick="insertQuickReply({{ $reply->id }})">
                <div class="quick-reply-title">{{ $reply->title }}</div>
                <div class="quick-reply-preview">{{ str_limit($reply->message, 50) }}</div>
            </div>
            @endforeach
        @else
            <div class="text-center text-muted p-3">
                <i class="ti-comment-alt" style="font-size: 48px; opacity: 0.3;"></i>
                <p>{{ cleanLang(__('lang.no_quick_replies_yet')) }}</p>
                <button class="btn btn-sm btn-primary" onclick="addQuickReply()">
                    {{ cleanLang(__('lang.create_first_quick_reply')) }}
                </button>
            </div>
        @endif
    </div>
</div>

<!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">

<!--JavaScript-->
<script src="/public/js/whatsapp/quick-reply.js?v={{ config('system.versioning') }}"></script>
