@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.whatsapp_broadcasts') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button"
                    class="btn btn-info btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal"
                    data-target="#commonModal"
                    data-url="{{ url('/whatsapp/broadcasts/create') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ __('lang.new_broadcast') }}"
                    data-action-url="{{ url('/whatsapp/broadcasts') }}"
                    data-action-method="POST"
                    data-action-ajax-class="js-ajax-ux-request"
                    data-action-ajax-loading-target="commonModalBody">
                <i class="ti-announcement"></i> {{ __('lang.new_broadcast') }}
            </button>
        </div>
    </div>

    <!--broadcasts table-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="broadcasts-table">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.name') }}</th>
                                    <th>{{ __('lang.connection') }}</th>
                                    <th>{{ __('lang.recipients') }}</th>
                                    <th>{{ __('lang.status') }}</th>
                                    <th>{{ __('lang.sent') }}</th>
                                    <th>{{ __('lang.delivered') }}</th>
                                    <th>{{ __('lang.failed') }}</th>
                                    <th>{{ __('lang.scheduled_at') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($broadcasts as $broadcast)
                                <tr>
                                    <td>{{ $broadcast->whatsappbroadcast_name }}</td>
                                    <td>{{ $broadcast->connection->whatsappconnection_name ?? '-' }}</td>
                                    <td>{{ $broadcast->recipients_count ?? 0 }}</td>
                                    <td>
                                        @if($broadcast->whatsappbroadcast_status == 'completed')
                                            <span class="badge badge-success">{{ __('lang.completed') }}</span>
                                        @elseif($broadcast->whatsappbroadcast_status == 'scheduled')
                                            <span class="badge badge-primary">{{ __('lang.scheduled') }}</span>
                                        @elseif($broadcast->whatsappbroadcast_status == 'draft')
                                            <span class="badge badge-secondary">{{ __('lang.draft') }}</span>
                                        @elseif($broadcast->whatsappbroadcast_status == 'sending')
                                            <span class="badge badge-info">{{ __('lang.sending') }}</span>
                                        @elseif($broadcast->whatsappbroadcast_status == 'pending')
                                            <span class="badge badge-warning">{{ __('lang.pending') }}</span>
                                        @elseif($broadcast->whatsappbroadcast_status == 'processing')
                                            <span class="badge badge-info">{{ __('lang.processing') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('lang.failed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $broadcast->whatsappbroadcast_sent_count ?? 0 }}</td>
                                    <td>{{ $broadcast->whatsappbroadcast_delivered_count ?? 0 }}</td>
                                    <td>{{ $broadcast->whatsappbroadcast_failed_count ?? 0 }}</td>
                                    <td>{{ $broadcast->whatsappbroadcast_scheduled_at ? $broadcast->whatsappbroadcast_scheduled_at->format('M d, Y H:i') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-view-broadcast"
                                                data-id="{{ $broadcast->whatsappbroadcast_id }}"
                                                title="{{ __('lang.view') }}">
                                            <i class="ti-eye"></i>
                                        </button>
                                        @if(in_array($broadcast->whatsappbroadcast_status, ['pending', 'draft']))
                                        <button class="btn btn-sm btn-warning btn-edit-broadcast"
                                                data-id="{{ $broadcast->whatsappbroadcast_id }}"
                                                title="{{ __('lang.edit') }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            {{ $broadcasts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
