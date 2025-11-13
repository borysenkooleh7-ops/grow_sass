<!--WhatsApp table-->
<table id="whatsapp-list-table" class="table table-hover m-t-0 w-100 table-card dataTable no-footer" data-url="{{ url('/whatsapp') }}" data-type="form">
    <thead>
        <tr>
            @if(config('visibility.whatsapp_col_checkboxes'))
            <th class="list-checkbox-wrapper sorting_disabled" data-orderable="false">
                <!--bulk actions-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-whatsapp" name="foo" class="listcheckbox-all filled-in chk-col-light-blue"
                        data-actions-container-class="whatsapp-checkbox-actions-container" data-checked-actions-container-class="whatsapp-checked-actions-container">
                    <label for="listcheckbox-whatsapp"></label>
                </span>
            </th>
            @endif
            <th class="sorting" data-orderable="true" data-order-column="ticket_subject">{{ cleanLang(__('lang.subject')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="client_company_name">{{ cleanLang(__('lang.client')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="contact_name">{{ cleanLang(__('lang.contact')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="phone_number">{{ cleanLang(__('lang.phone')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="last_message_at">{{ cleanLang(__('lang.last_message')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="ticket_status">{{ cleanLang(__('lang.status')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="assigned_to">{{ cleanLang(__('lang.assigned')) }}</th>
            <th class="sorting" data-orderable="true" data-order-column="created_at">{{ cleanLang(__('lang.date_created')) }}</th>
            @if(config('visibility.whatsapp_col_action'))
            <th class="list-actions-col sorting_disabled" data-orderable="false">{{ cleanLang(__('lang.action')) }}</th>
            @endif
        </tr>
    </thead>
    <tbody id="whatsapp-td-container">
        <!--ajax content here-->
        @include('pages.whatsapp.components.table.ajax')
        <!--ajax content here-->
    </tbody>
</table>
<!--WhatsApp table-->
