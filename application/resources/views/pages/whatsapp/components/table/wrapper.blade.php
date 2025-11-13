<!-- WhatsApp Table Wrapper -->
<div class="card" id="whatsapp-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($tickets ?? []) > 0)
                @include('pages.whatsapp.components.table.table')
            @else
                <!--nothing found-->
                @include('notifications.general', ['notification_class' => 'warning', 'notification_message' => __('lang.no_results_found')])
                <!--nothing found-->
            @endif
        </div>
    </div>
</div>
<!--WhatsApp table wrapper-->
