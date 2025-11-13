@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? 'WhatsApp Conversations' }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button"
                    class="btn btn-info btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal"
                    data-target="#commonModal"
                    data-url="{{ url('/whatsapp/conversations/create') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ cleanLang(__('lang.new_conversation')) }}"
                    data-action-url="{{ url('/whatsapp/conversations/store') }}"
                    data-action-method="POST"
                    data-action-ajax-class="ajax-request"
                    data-action-ajax-loading-target=""
                    data-footer-visibility="hidden">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.new_conversation')) }}
            </button>
        </div>
    </div>

    <!--status tabs-->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ !request('status') || request('status') == 'open' ? 'active' : '' }}"
                       href="/whatsapp/conversations?status=open">
                        <i class="ti-email"></i> {{ __('lang.open') }}
                        <span class="badge badge-pill badge-info">{{ $statusCounts['open'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'on_hold' ? 'active' : '' }}"
                       href="/whatsapp/conversations?status=on_hold">
                        <i class="ti-time"></i> {{ __('lang.on_hold') }}
                        <span class="badge badge-pill badge-warning">{{ $statusCounts['on_hold'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'closed' ? 'active' : '' }}"
                       href="/whatsapp/conversations?status=closed">
                        <i class="ti-check"></i> {{ __('lang.closed') }}
                        <span class="badge badge-pill badge-success">{{ $statusCounts['closed'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!--filters-->
    <div class="row m-t-10">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="/whatsapp/conversations">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{ __('lang.search') }}..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="tag">
                                    <option value="">{{ __('lang.all_tags') }}</option>
                                    @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                            {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="ti-search"></i> {{ __('lang.filter') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--conversations list-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.contact') }}</th>
                                    <th>{{ __('lang.subject') }}</th>
                                    <th>{{ __('lang.status') }}</th>
                                    <th>{{ __('lang.assigned_to') }}</th>
                                    <th>{{ __('lang.last_message') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr class="{{ $ticket->whatsappticket_unread_count > 0 ? 'font-weight-bold' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-info">
                                                        {{ substr($ticket->whatsappticket_contact_name ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $ticket->whatsappticket_contact_name ?? 'Unknown' }}</strong><br>
                                                <small class="text-muted">{{ $ticket->whatsappticket_contact_phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $ticket->whatsappticket_subject ?? 'No subject' }}
                                        @if($ticket->whatsappticket_unread_count > 0)
                                        <span class="badge badge-danger badge-pill">{{ $ticket->whatsappticket_unread_count }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->whatsappticket_status == 'open')
                                        <span class="badge badge-info">{{ __('lang.open') }}</span>
                                        @elseif($ticket->whatsappticket_status == 'on_hold')
                                        <span class="badge badge-warning">{{ __('lang.on_hold') }}</span>
                                        @else
                                        <span class="badge badge-success">{{ __('lang.closed') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $ticket->assignedAgent->first_name ?? 'Unassigned' }}
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $ticket->whatsappticket_last_message_at ? \Carbon\Carbon::parse($ticket->whatsappticket_last_message_at)->diffForHumans() : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="/whatsapp/ticket/{{ $ticket->whatsappticket_id }}"
                                           class="btn btn-sm btn-info">
                                            <i class="ti-eye"></i> {{ __('lang.view') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-info m-20">
                                            <i class="ti-info-alt"></i>
                                            {{ __('lang.no_conversations_found') }}
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!--pagination-->
                    @if($tickets->hasPages())
                    <div class="p-20">
                        {{ $tickets->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

<!--JavaScript for conversations module-->
<script src="/public/js/whatsapp/conversations.js?v={{ config('system.versioning') }}"></script>
