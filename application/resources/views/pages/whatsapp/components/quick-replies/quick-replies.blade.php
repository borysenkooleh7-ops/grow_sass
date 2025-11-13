@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.quick_replies') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button"
                    class="btn btn-info btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal"
                    data-target="#commonModal"
                    data-url="{{ url('/whatsapp/quick-replies/create') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ __('lang.add_quick_reply') }}"
                    data-action-url="{{ url('/whatsapp/quick-replies') }}"
                    data-action-method="POST"
                    data-action-ajax-class="js-ajax-ux-request"
                    data-action-ajax-loading-target="commonModalBody">
                <i class="ti-plus"></i> {{ __('lang.add_quick_reply') }}
            </button>
        </div>
    </div>

    <!--quick replies table-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="quick-replies-table">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.title') }}</th>
                                    <th>{{ __('lang.shortcut') }}</th>
                                    <th>{{ __('lang.message') }}</th>
                                    <th>{{ __('lang.category') }}</th>
                                    <th>{{ __('lang.shared') }}</th>
                                    <th>{{ __('lang.created_by') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quickReplies as $reply)
                                <tr>
                                    <td>{{ $reply->whatsappquickreply_title }}</td>
                                    <td>
                                        <code>{{ $reply->whatsappquickreply_shortcut }}</code>
                                    </td>
                                    <td>
                                        {{ Str::limit($reply->whatsappquickreply_message, 50) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ ucfirst($reply->whatsappquickreply_category ?? 'general') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($reply->whatsappquickreply_is_shared)
                                            <span class="badge badge-success">{{ __('lang.yes') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('lang.no') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $reply->creator->full_name ?? '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-edit-quick-reply"
                                                data-id="{{ $reply->whatsappquickreply_id }}"
                                                title="{{ __('lang.edit') }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete-quick-reply"
                                                data-id="{{ $reply->whatsappquickreply_id }}"
                                                title="{{ __('lang.delete') }}">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            {{ $quickReplies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
