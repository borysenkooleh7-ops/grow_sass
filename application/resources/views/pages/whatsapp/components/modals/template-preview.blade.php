<!--Template Preview Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--template info-->
        <div class="template-preview-header mb-3">
            <h5>{{ $template->title }}</h5>
            <div class="template-meta">
                <span class="badge badge-info mr-2">{{ ucfirst($template->category) }}</span>
                <span class="badge badge-secondary">{{ strtoupper($template->language) }}</span>
            </div>
        </div>

        <!--whatsapp-style preview-->
        <div class="whatsapp-preview-container">
            <div class="whatsapp-phone-mockup">
                <div class="phone-header">
                    <div class="contact-header">
                        <div class="contact-avatar">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-name">{{ $template->connection_name ?? 'WhatsApp Business' }}</div>
                            <div class="contact-status">{{ cleanLang(__('lang.online')) }}</div>
                        </div>
                    </div>
                </div>

                <div class="phone-body">
                    <div class="message-bubble incoming">
                        <div class="message-content">
                            <div class="message-text">
                                {!! nl2br(e($template->message)) !!}
                            </div>

                            @if(isset($template->buttons) && count($template->buttons) > 0)
                            <div class="message-buttons">
                                @foreach($template->buttons as $button)
                                <button class="template-button">
                                    <i class="ti-world"></i> {{ $button->text }}
                                </button>
                                @endforeach
                            </div>
                            @endif

                            <div class="message-meta">
                                <span class="message-time">{{ date('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--variables info-->
        @if(isset($template->variables) && count($template->variables) > 0)
        <div class="alert alert-info mt-3">
            <strong>{{ cleanLang(__('lang.variables_used')) }}:</strong>
            <div class="mt-2">
                @foreach($template->variables as $var)
                    <code class="mr-2">{{ '{{' . $var . '}}' }}</code>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<\!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">
