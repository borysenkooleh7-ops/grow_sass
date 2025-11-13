<!--Add/Edit Connection Modal Form-->
<div class="row">
    <div class="col-lg-12">

        <!--connection name-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.connection_name')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" name="name"
                       value="{{ $connection->connection_name ?? '' }}" required>
            </div>
        </div>

        <!--connection type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.connection_type')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" name="type" id="connection_type" required>
                    <option value="">{{ cleanLang(__('lang.select_type')) }}</option>
                    <option value="evolution" {{ runtimePreselected('evolution', $connection->connection_type ?? '') }}>
                        Evolution API (QR Code - FREE)
                    </option>
                    <option value="wati" {{ runtimePreselected('wati', $connection->connection_type ?? '') }}>
                        WATI (QR Code - Paid)
                    </option>
                </select>
                <small class="form-text text-muted">
                    <i class="ti-info-alt"></i> {{ cleanLang(__('lang.qr_code_connections_only')) }}
                </small>
            </div>
        </div>

        <!--phone number-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.phone_number')) }}*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" name="phone"
                       value="{{ $connection->phone_number ?? '' }}"
                       placeholder="+1234567890" required>
                <small class="form-text text-muted">{{ cleanLang(__('lang.with_country_code')) }}</small>
            </div>
        </div>

        <!--dynamic fields container-->
        <div id="connection-type-fields">
            @if(isset($connection) && isset($connection->whatsappconnection_type) && $connection->whatsappconnection_type)
                @include('pages.whatsapp-module.modals.connection-fields', ['type' => $connection->whatsappconnection_type, 'connection' => $connection])
            @endif
        </div>

        <!--is active-->
        <div class="form-group form-group-checkbox row">
            <div class="col-12 p-t-5">
                <input type="checkbox" id="connection_is_active" name="is_active" class="filled-in chk-col-light-blue"
                    {{ runtimeChecked($connection->whatsappconnection_is_active ?? true) }} value="1">
                <label for="connection_is_active">{{ cleanLang(__('lang.active')) }}</label>
            </div>
        </div>

        <!--hidden fields for edit-->
        @if(isset($connection->whatsappconnection_id))
        <input type="hidden" name="connection_id" value="{{ $connection->whatsappconnection_id }}">
        @endif

    </div>
</div>

<script src="{{ url('/') }}/public/js/whatsapp/connections.js?v={{ config('system.versioning') }}"></script>
