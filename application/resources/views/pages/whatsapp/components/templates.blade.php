<!--WhatsApp Message Templates Component-->
<div class="whatsapp-templates-section">
    <div class="templates-header">
        <h6>{{ cleanLang(__('lang.message_templates')) }}</h6>
        <button class="btn btn-xs btn-primary" onclick="manageTemplates()">
            <i class="ti-settings"></i> {{ cleanLang(__('lang.manage')) }}
        </button>
    </div>

    <div class="templates-search">
        <input type="text" class="form-control form-control-sm" id="template-search" placeholder="{{ cleanLang(__('lang.search_templates')) }}" onkeyup="searchTemplates()">
    </div>

    <div class="templates-list" id="templates-list">
        @if(isset($templates) && count($templates) > 0)
            @foreach($templates as $template)
            <div class="template-item" data-id="{{ $template->id }}" data-title="{{ strtolower($template->title) }}">
                <div class="template-header-item" onclick="toggleTemplate({{ $template->id }})">
                    <div class="template-title">
                        <i class="ti-file"></i> {{ $template->title }}
                    </div>
                    <div class="template-category">
                        <span class="badge badge-info">{{ $template->category }}</span>
                    </div>
                </div>
                <div class="template-body" id="template-body-{{ $template->id }}" style="display: none;">
                    <div class="template-preview">
                        {{ $template->message }}
                    </div>
                    <div class="template-variables" v-if="{{ count($template->variables ?? []) > 0 }}">
                        <small class="text-muted">
                            {{ cleanLang(__('lang.variables')) }}:
                            @foreach($template->variables as $var)
                                <code>{{ '{{' . $var . '}}' }}</code>
                            @endforeach
                        </small>
                    </div>
                    <button class="btn btn-xs btn-success btn-block mt-2" onclick="useTemplate({{ $template->id }})">
                        <i class="ti-check"></i> {{ cleanLang(__('lang.use_template')) }}
                    </button>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center text-muted p-3">
                <i class="ti-file" style="font-size: 48px; opacity: 0.3;"></i>
                <p>{{ cleanLang(__('lang.no_templates_yet')) }}</p>
                <button class="btn btn-sm btn-primary" onclick="manageTemplates()">
                    {{ cleanLang(__('lang.create_first_template')) }}
                </button>
            </div>
        @endif
    </div>
</div>

<!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">

<!--JavaScript-->
<script src="/public/js/whatsapp/templates.js?v={{ config('system.versioning') }}"></script>
