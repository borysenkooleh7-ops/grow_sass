@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-12 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? 'WhatsApp Dashboard' }}</h3>
        </div>
    </div>

    <!--stats cards-->
    <div class="row">
        <!--total conversations-->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-info">
                            <i class="ti-email"></i>
                        </div>
                        <div class="m-l-20 align-self-center">
                            <h3 class="m-b-0">{{ $stats['total_conversations'] ?? 0 }}</h3>
                            <h6 class="text-muted m-b-0">{{ __('lang.total_conversations') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--open tickets-->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-warning">
                            <i class="ti-time"></i>
                        </div>
                        <div class="m-l-20 align-self-center">
                            <h3 class="m-b-0">{{ $stats['open_tickets'] ?? 0 }}</h3>
                            <h6 class="text-muted m-b-0">{{ __('lang.open_tickets') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--total messages today-->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-success">
                            <i class="ti-comments"></i>
                        </div>
                        <div class="m-l-20 align-self-center">
                            <h3 class="m-b-0">{{ $stats['messages_today'] ?? 0 }}</h3>
                            <h6 class="text-muted m-b-0">{{ __('lang.messages_today') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--connection status-->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round align-self-center {{ \App\Models\WhatsappConnection::isWatiConnected() ? 'round-success' : 'round-danger' }}">
                            <i class="mdi mdi-whatsapp"></i>
                        </div>
                        <div class="m-l-20 align-self-center">
                            <h3 class="m-b-0">
                                @if(\App\Models\WhatsappConnection::isWatiConnected())
                                    <i class="ti-check-box text-success"></i>
                                @else
                                    <i class="ti-close text-danger"></i>
                                @endif
                            </h3>
                            <h6 class="text-muted m-b-0">
                                {{ \App\Models\WhatsappConnection::isWatiConnected() ? __('lang.connected') : __('lang.disconnected') }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--charts row-->
    <div class="row">
        <!--messages chart-->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('lang.messages_overview') }}</h4>
                    <div id="messages-chart"></div>
                </div>
            </div>
        </div>

        <!--response time-->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('lang.avg_response_time') }}</h4>
                    <div class="text-center m-t-40">
                        <h1 class="text-info">{{ $stats['avg_response_time'] ?? '0m' }}</h1>
                        <p class="text-muted">{{ __('lang.average_first_response') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--recent conversations-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">{{ __('lang.recent_conversations') }}</h4>
                        <a href="/whatsapp/conversations" class="btn btn-sm btn-info">
                            {{ __('lang.view_all') }} <i class="ti-arrow-right"></i>
                        </a>
                    </div>
                    <div class="table-responsive m-t-20">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.contact') }}</th>
                                    <th>{{ __('lang.last_message') }}</th>
                                    <th>{{ __('lang.status') }}</th>
                                    <th>{{ __('lang.assigned_to') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_conversations ?? [] as $conversation)
                                <tr>
                                    <td>{{ $conversation->whatsappticket_contact_name ?? 'Unknown' }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $conversation->whatsappticket_last_message_at ? \Carbon\Carbon::parse($conversation->whatsappticket_last_message_at)->diffForHumans() : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($conversation->whatsappticket_status) }}</span>
                                    </td>
                                    <td>{{ $conversation->assignedAgent->first_name ?? 'Unassigned' }}</td>
                                    <td>
                                        <a href="/whatsapp/ticket/{{ $conversation->whatsappticket_id }}"
                                           class="btn btn-sm btn-info">
                                            <i class="ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        {{ __('lang.no_recent_conversations') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
