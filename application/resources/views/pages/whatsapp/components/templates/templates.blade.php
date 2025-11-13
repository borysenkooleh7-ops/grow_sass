@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.whatsapp_templates') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button"
                    class="btn btn-info btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal"
                    data-target="#commonModal"
                    data-url="{{ url('/whatsapp/templates/create') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ __('lang.add_template') }}"
                    data-action-url="{{ url('/whatsapp/templates') }}"
                    data-action-method="POST"
                    data-action-ajax-class="js-ajax-ux-request"
                    data-action-ajax-loading-target="commonModalBody">
                <i class="ti-plus"></i> {{ __('lang.add_template') }}
            </button>
        </div>
    </div>

    <!--templates table-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" id="filter-category">
                                <option value="">{{ __('lang.all_categories') }}</option>
                                <option value="general">{{ __('lang.general') }}</option>
                                <option value="marketing">{{ __('lang.marketing') }}</option>
                                <option value="notification">{{ __('lang.notification') }}</option>
                                <option value="support">{{ __('lang.support') }}</option>
                                <option value="transactional">{{ __('lang.transactional') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="filter-status">
                                <option value="">{{ __('lang.all_statuses') }}</option>
                                <option value="1">{{ __('lang.active') }}</option>
                                <option value="0">{{ __('lang.inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="templates-table">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.title') }}</th>
                                    <th>{{ __('lang.category') }}</th>
                                    <th>{{ __('lang.language') }}</th>
                                    <th>{{ __('lang.status') }}</th>
                                    <th>{{ __('lang.created_by') }}</th>
                                    <th>{{ __('lang.created_at') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr>
                                    <td>{{ $template->whatsapptemplatemain_title }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ ucfirst($template->whatsapptemplatemain_category) }}
                                        </span>
                                    </td>
                                    <td>{{ strtoupper($template->whatsapptemplatemain_language) }}</td>
                                    <td>
                                        @if($template->whatsapptemplatemain_is_active)
                                            <span class="badge badge-success">{{ __('lang.active') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('lang.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $template->creator->full_name ?? '-' }}</td>
                                    <td>{{ $template->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-preview-template"
                                                data-id="{{ $template->whatsapptemplatemain_id }}"
                                                title="{{ __('lang.preview') }}">
                                            <i class="ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning btn-edit-template"
                                                data-id="{{ $template->whatsapptemplatemain_id }}"
                                                title="{{ __('lang.edit') }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary btn-duplicate-template"
                                                data-id="{{ $template->whatsapptemplatemain_id }}"
                                                title="{{ __('lang.duplicate') }}">
                                            <i class="ti-files"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete-template"
                                                data-id="{{ $template->whatsapptemplatemain_id }}"
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
                            {{ $templates->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
