<!--Create WhatsApp Ticket-->
<div class="row">
    <div class="col-lg-12">

        <!--connection-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.whatsapp_connection')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-basic form-control form-control-sm" id="connection_id" name="connection_id">
                    <option value="">{{ cleanLang(__('lang.select_connection')) }}</option>
                    @if(isset($connections))
                        @foreach($connections as $connection)
                        <option value="{{ $connection->whatsappconnection_id }}">
                            {{ $connection->whatsappconnection_name }} ({{ $connection->whatsappconnection_phone }})
                        </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <!--compose-->
        @include('pages.whatsapp.components.create.compose')

        <!--options-->
        @if(View::exists('pages.whatsapp.components.create.options'))
            @include('pages.whatsapp.components.create.options')
        @endif

    </div>
</div>

<!--buttons-->
<div class="text-right">
    <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
        data-url="{{ $url ?? '' }}" data-loading-target="" data-ajax-type="POST" data-form-id="commonModalForm"
        data-on-start-submit-button="disable">{{ cleanLang(__('lang.submit')) }}</button>
</div>
