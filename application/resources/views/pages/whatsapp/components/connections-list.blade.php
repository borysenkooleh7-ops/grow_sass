<!--WhatsApp Connections List-->
<div class="row">
    <div class="col-lg-12">

        <!--add connection button-->
        <div class="text-right mb-3">
            <button type="button" class="btn btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal"
                data-target="#commonModal"
                data-url="{{ url('/whatsapp/connections/create') }}"
                data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.add_connection')) }}"
                data-action-url="{{ url('/whatsapp/connections') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-action-ajax-loading-target="connections-list-container">
                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_connection')) }}
            </button>
        </div>

        <!--connections table-->
        <div class="table-responsive" id="connections-list-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ cleanLang(__('lang.connection_name')) }}</th>
                        <th>{{ cleanLang(__('lang.type')) }}</th>
                        <th>{{ cleanLang(__('lang.phone_number')) }}</th>
                        <th>{{ cleanLang(__('lang.status')) }}</th>
                        <th>{{ cleanLang(__('lang.last_connected')) }}</th>
                        <th>{{ cleanLang(__('lang.active')) }}</th>
                        <th>{{ cleanLang(__('lang.action')) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($connections) && count($connections) > 0)
                        @foreach($connections as $connection)
                        <tr>
                            <!--name-->
                            <td>
                                <strong>{{ $connection->connection_name }}</strong>
                            </td>

                            <!--type-->
                            <td>
                                <span class="badge badge-info">{{ ucfirst($connection->connection_type) }}</span>
                            </td>

                            <!--phone-->
                            <td>
                                @if($connection->phone_number)
                                    <a href="https://wa.me/{{ $connection->phone_number }}" target="_blank" class="text-success">
                                        <i class="fab fa-whatsapp"></i> {{ $connection->phone_number }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('lang.not_set') }}</span>
                                @endif
                            </td>

                            <!--status-->
                            <td>
                                @if($connection->status == 'connected')
                                    <span class="label label-success">{{ cleanLang(__('lang.connected')) }}</span>
                                @elseif($connection->status == 'connecting')
                                    <span class="label label-warning">{{ cleanLang(__('lang.connecting')) }}</span>
                                @elseif($connection->status == 'error')
                                    <span class="label label-danger">{{ cleanLang(__('lang.error')) }}</span>
                                @else
                                    <span class="label label-default">{{ cleanLang(__('lang.disconnected')) }}</span>
                                @endif
                            </td>

                            <!--last connected-->
                            <td>
                                @if($connection->last_connected_at)
                                    <span class="text-muted">{{ runtimeDateAgo($connection->last_connected_at) }}</span>
                                @else
                                    <span class="text-muted">{{ __('lang.never') }}</span>
                                @endif
                            </td>

                            <!--is active-->
                            <td>
                                @if($connection->is_active)
                                    <i class="ti-check text-success"></i>
                                @else
                                    <i class="ti-close text-danger"></i>
                                @endif
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
                                            data-url="{{ url('/whatsapp/connections/'.$connection->id.'/edit') }}"
                                            data-loading-target="commonModalBody"
                                            data-modal-title="{{ cleanLang(__('lang.edit_connection')) }}"
                                            data-action-url="{{ url('/whatsapp/connections/'.$connection->id) }}"
                                            data-action-method="PUT"
                                            data-action-ajax-class=""
                                            data-action-ajax-loading-target="connections-list-container">
                                            <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                                        </a>

                                        <!--test connection-->
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="testConnection({{ $connection->id }})">
                                            <i class="ti-reload"></i> {{ cleanLang(__('lang.test_connection')) }}
                                        </a>

                                        <!--view qr (baileys only)-->
                                        @if($connection->connection_type == 'baileys')
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="viewQRCode({{ $connection->id }})">
                                            <i class="ti-mobile"></i> {{ cleanLang(__('lang.view_qr_code')) }}
                                        </a>
                                        @endif

                                        <!--reconnect-->
                                        @if($connection->status != 'connected')
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="reconnectConnection({{ $connection->id }})">
                                            <i class="ti-plug"></i> {{ cleanLang(__('lang.reconnect')) }}
                                        </a>
                                        @endif

                                        <!--disconnect-->
                                        @if($connection->status == 'connected')
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="disconnectConnection({{ $connection->id }})">
                                            <i class="ti-close"></i> {{ cleanLang(__('lang.disconnect')) }}
                                        </a>
                                        @endif

                                        <div class="dropdown-divider"></div>

                                        <!--delete-->
                                        <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                                            data-confirm-title="{{ cleanLang(__('lang.delete_connection')) }}"
                                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                            data-ajax-type="DELETE"
                                            data-url="{{ url('/whatsapp/connections/'.$connection->id) }}">
                                            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!--error message if any-->
                        @if($connection->error_message && $connection->status == 'error')
                        <tr>
                            <td colspan="7" class="bg-danger-light">
                                <small><strong>{{ cleanLang(__('lang.error')) }}:</strong> {{ $connection->error_message }}</small>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">
                                <i class="ti-plug" style="font-size: 48px; opacity: 0.3;"></i>
                                <p>{{ cleanLang(__('lang.no_connections_yet')) }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    function testConnection(connectionId) {
        $.ajax({
            url: '/whatsapp/connections/' + connectionId + '/test',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: response.message || '{{ cleanLang(__("lang.connection_successful")) }}'
                    });
                } else {
                    NX.notification({
                        type: 'error',
                        message: response.message || '{{ cleanLang(__("lang.connection_failed")) }}'
                    });
                }
            }
        });
    }

    function viewQRCode(connectionId) {
        NX.loadModal({
            url: '/whatsapp/connections/' + connectionId + '/qr-code',
            title: '{{ cleanLang(__("lang.scan_qr_code")) }}',
            size: 'medium'
        });
    }

    function reconnectConnection(connectionId) {
        $.ajax({
            url: '/whatsapp/connections/' + connectionId + '/reconnect',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: response.message || '{{ cleanLang(__("lang.reconnecting")) }}'
                    });
                    // Reload connections list
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            }
        });
    }

    function disconnectConnection(connectionId) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure")) }}')) {
            $.ajax({
                url: '/whatsapp/connections/' + connectionId + '/disconnect',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        NX.notification({
                            type: 'success',
                            message: response.message || '{{ cleanLang(__("lang.disconnected")) }}'
                        });
                        // Reload connections list
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }
</script>
