<!--Quick Replies Manager-->
<div class="row">
    <div class="col-lg-12">

        <!--add quick reply button-->
        <div class="text-right mb-3">
            <button type="button" class="btn btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal"
                data-target="#commonModal"
                data-url="{{ url('/whatsapp/quick-replies/create') }}"
                data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.add_quick_reply')) }}"
                data-action-url="{{ url('/whatsapp/quick-replies') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-action-ajax-loading-target="quick-replies-list-container">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_quick_reply')) }}
            </button>
        </div>

        <!--category filter-->
        <div class="mb-3">
            <select class="form-control form-control-sm" id="filter-category" onchange="filterQuickReplies()" style="max-width: 200px;">
                <option value="">{{ cleanLang(__('lang.all_categories')) }}</option>
                <option value="greeting">{{ cleanLang(__('lang.greeting')) }}</option>
                <option value="closing">{{ cleanLang(__('lang.closing')) }}</option>
                <option value="information">{{ cleanLang(__('lang.information')) }}</option>
                <option value="support">{{ cleanLang(__('lang.support')) }}</option>
                <option value="sales">{{ cleanLang(__('lang.sales')) }}</option>
            </select>
        </div>

        <!--quick replies table-->
        <div class="table-responsive" id="quick-replies-list-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ cleanLang(__('lang.title')) }}</th>
                        <th>{{ cleanLang(__('lang.shortcut')) }}</th>
                        <th>{{ cleanLang(__('lang.category')) }}</th>
                        <th>{{ cleanLang(__('lang.message_preview')) }}</th>
                        <th>{{ cleanLang(__('lang.shared')) }}</th>
                        <th>{{ cleanLang(__('lang.created')) }}</th>
                        <th>{{ cleanLang(__('lang.action')) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($quick_replies) && count($quick_replies) > 0)
                        @foreach($quick_replies as $reply)
                        <tr data-category="{{ $reply->category }}">
                            <!--title-->
                            <td>
                                <strong>{{ $reply->title }}</strong>
                            </td>

                            <!--shortcut-->
                            <td>
                                @if($reply->shortcut)
                                    <code>{{ $reply->shortcut }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!--category-->
                            <td>
                                @if($reply->category)
                                    <span class="badge badge-secondary">{{ ucfirst($reply->category) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!--message preview-->
                            <td>
                                <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ str_limit($reply->message, 50) }}
                                </div>
                            </td>

                            <!--shared-->
                            <td>
                                @if($reply->is_shared)
                                    <i class="ti-check text-success" title="{{ __('lang.shared_with_team') }}"></i>
                                @else
                                    <i class="ti-lock text-warning" title="{{ __('lang.private') }}"></i>
                                @endif
                            </td>

                            <!--created-->
                            <td>
                                <span class="text-muted">{{ runtimeDate($reply->created_at) }}</span>
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
                                            data-url="{{ url('/whatsapp/quick-replies/'.$reply->id.'/edit') }}"
                                            data-loading-target="commonModalBody"
                                            data-modal-title="{{ cleanLang(__('lang.edit_quick_reply')) }}"
                                            data-action-url="{{ url('/whatsapp/quick-replies/'.$reply->id) }}"
                                            data-action-method="PUT"
                                            data-action-ajax-class=""
                                            data-action-ajax-loading-target="quick-replies-list-container">
                                            <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                                        </a>

                                        <!--duplicate-->
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="duplicateQuickReply({{ $reply->id }})">
                                            <i class="ti-files"></i> {{ cleanLang(__('lang.duplicate')) }}
                                        </a>

                                        <!--copy message-->
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="copyToClipboard('{{ addslashes($reply->message) }}')">
                                            <i class="ti-clipboard"></i> {{ cleanLang(__('lang.copy_message')) }}
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <!--delete-->
                                        <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                                            data-confirm-title="{{ cleanLang(__('lang.delete_quick_reply')) }}"
                                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                            data-ajax-type="DELETE"
                                            data-url="{{ url('/whatsapp/quick-replies/'.$reply->id) }}">
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
                                <i class="ti-comment-alt" style="font-size: 48px; opacity: 0.3;"></i>
                                <p>{{ cleanLang(__('lang.no_quick_replies_yet')) }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    function filterQuickReplies() {
        const category = $('#filter-category').val();
        const rows = $('#quick-replies-list-container tbody tr');

        if (category === '') {
            rows.show();
        } else {
            rows.each(function() {
                if ($(this).data('category') === category) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    }

    function duplicateQuickReply(replyId) {
        $.ajax({
            url: '/whatsapp/quick-replies/' + replyId + '/duplicate',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.quick_reply_duplicated")) }}'
                    });
                    location.reload();
                }
            }
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            NX.notification({
                type: 'success',
                message: '{{ cleanLang(__("lang.copied_to_clipboard")) }}'
            });
        });
    }
</script>
