<!--Templates Manager-->
<div class="row">
    <div class="col-lg-12">

        <!--add template button-->
        <div class="text-right mb-3">
            <button type="button" class="btn btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal"
                data-target="#commonModal"
                data-url="{{ url('/whatsapp/templates/create') }}"
                data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.add_template')) }}"
                data-modal-size="modal-lg"
                data-action-url="{{ url('/whatsapp/templates') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-action-ajax-loading-target="templates-list-container">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_template')) }}
            </button>
        </div>

        <!--templates table-->
        <div class="table-responsive" id="templates-list-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ cleanLang(__('lang.title')) }}</th>
                        <th>{{ cleanLang(__('lang.category')) }}</th>
                        <th>{{ cleanLang(__('lang.language')) }}</th>
                        <th>{{ cleanLang(__('lang.preview')) }}</th>
                        <th>{{ cleanLang(__('lang.active')) }}</th>
                        <th>{{ cleanLang(__('lang.created')) }}</th>
                        <th>{{ cleanLang(__('lang.action')) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($templates) && count($templates) > 0)
                        @foreach($templates as $template)
                        <tr>
                            <!--title-->
                            <td>
                                <strong>{{ $template->title }}</strong>
                            </td>

                            <!--category-->
                            <td>
                                <span class="badge badge-info">{{ ucfirst($template->category) }}</span>
                            </td>

                            <!--language-->
                            <td>
                                {{ strtoupper($template->language) }}
                            </td>

                            <!--preview-->
                            <td>
                                <button class="btn btn-xs btn-outline-secondary" onclick="previewTemplate({{ $template->id }})">
                                    <i class="ti-eye"></i> {{ cleanLang(__('lang.preview')) }}
                                </button>
                            </td>

                            <!--is active-->
                            <td>
                                @if($template->is_active)
                                    <span class="label label-success">{{ cleanLang(__('lang.active')) }}</span>
                                @else
                                    <span class="label label-default">{{ cleanLang(__('lang.inactive')) }}</span>
                                @endif
                            </td>

                            <!--created-->
                            <td>
                                <span class="text-muted">{{ runtimeDate($template->created_at) }}</span>
                            </td>

                            <!--actions-->
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle waves-effect" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ cleanLang(__('lang.actions')) }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <!--edit-->
                                        <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                            href="javascript:void(0)"
                                            data-toggle="modal"
                                            data-target="#commonModal"
                                            data-url="{{ url('/whatsapp/templates/'.$template->id.'/edit') }}"
                                            data-loading-target="commonModalBody"
                                            data-modal-title="{{ cleanLang(__('lang.edit_template')) }}"
                                            data-modal-size="modal-lg"
                                            data-action-url="{{ url('/whatsapp/templates/'.$template->id) }}"
                                            data-action-method="PUT"
                                            data-action-ajax-class=""
                                            data-action-ajax-loading-target="templates-list-container">
                                            <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                                        </a>

                                        <!--duplicate-->
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="duplicateTemplate({{ $template->id }})">
                                            <i class="ti-files"></i> {{ cleanLang(__('lang.duplicate')) }}
                                        </a>

                                        <!--toggle status-->
                                        @if($template->is_active)
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="toggleTemplateStatus({{ $template->id }}, 0)">
                                            <i class="ti-close"></i> {{ cleanLang(__('lang.deactivate')) }}
                                        </a>
                                        @else
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="toggleTemplateStatus({{ $template->id }}, 1)">
                                            <i class="ti-check"></i> {{ cleanLang(__('lang.activate')) }}
                                        </a>
                                        @endif

                                        <div class="dropdown-divider"></div>

                                        <!--delete-->
                                        <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                                            data-confirm-title="{{ cleanLang(__('lang.delete_template')) }}"
                                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                            data-ajax-type="DELETE"
                                            data-url="{{ url('/whatsapp/templates/'.$template->id) }}">
                                            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">
                                <i class="ti-file" style="font-size: 48px; opacity: 0.3;"></i>
                                <p>{{ cleanLang(__('lang.no_templates_yet')) }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    function previewTemplate(templateId) {
        NX.loadModal({
            url: '/whatsapp/templates/' + templateId + '/preview',
            title: '{{ cleanLang(__("lang.template_preview")) }}',
            size: 'medium'
        });
    }

    function duplicateTemplate(templateId) {
        $.ajax({
            url: '/whatsapp/templates/' + templateId + '/duplicate',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.template_duplicated")) }}'
                    });
                    location.reload();
                }
            }
        });
    }

    function toggleTemplateStatus(templateId, status) {
        $.ajax({
            url: '/whatsapp/templates/' + templateId + '/toggle-status',
            method: 'POST',
            data: { is_active: status },
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: response.message
                    });
                    location.reload();
                }
            }
        });
    }
</script>
