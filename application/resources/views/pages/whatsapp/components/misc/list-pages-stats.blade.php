<!--stats-->
<div class="stats-widget-wrapper card-group">

    <!--total-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.total')) }}</h6>
                        <h2 class="text-primary font-medium">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-ticket display-5 op-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--open-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="open">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.open')) }}</h6>
                        <h2 class="text-info font-medium">{{ $stats['open'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-alarm-clock display-5 op-3 text-info"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--answered-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="answered">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.answered')) }}</h6>
                        <h2 class="text-warning font-medium">{{ $stats['answered'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-comment-alt display-5 op-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--on_hold-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="on_hold">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.on_hold')) }}</h6>
                        <h2 class="text-danger font-medium">{{ $stats['on_hold'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-control-pause display-5 op-3 text-danger"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--resolved-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="resolved">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.resolved')) }}</h6>
                        <h2 class="text-success font-medium">{{ $stats['resolved'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-check display-5 op-3 text-success"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--closed-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-status" data-filter-value="closed">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.closed')) }}</h6>
                        <h2 class="text-muted font-medium">{{ $stats['closed'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-lock display-5 op-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!--unread-->
    <div class="card m-b-10 p-l-25 p-r-25">
        <a href="javascript:void(0)" data-filter="filter-unread" data-filter-value="true">
            <div class="card-body p-t-10 p-b-10">
                <div class="d-flex no-block align-items-center">
                    <div class="stats-widget">
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.unread')) }}</h6>
                        <h2 class="text-danger font-medium">{{ $stats['unread'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon ml-auto">
                        <i class="ti-email display-5 op-3 text-danger"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>
<!--stats-->
