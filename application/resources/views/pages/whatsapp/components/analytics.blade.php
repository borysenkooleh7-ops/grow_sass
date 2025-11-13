<!--WhatsApp Analytics Dashboard-->
<div class="row">
    <div class="col-lg-12">

        <!--date range filter-->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h5 class="card-title">{{ cleanLang(__('lang.analytics')) }}</h5>
                    </div>
                    <div class="col-md-9 text-right">
                        <div class="form-inline float-right">
                            <label class="mr-2">{{ cleanLang(__('lang.client')) }}:</label>
                            <select class="form-control form-control-sm mr-2" id="analytics-client" onchange="loadAnalytics()">
                                <option value="">{{ cleanLang(__('lang.all_clients')) }}</option>
                                @if(isset($clients))
                                    @foreach($clients as $client)
                                    <option value="{{ $client->client_id }}" {{ $clientId == $client->client_id ? 'selected' : '' }}>
                                        {{ $client->client_company_name ?: $client->client_first_name . ' ' . $client->client_last_name }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>

                            <label class="mr-2">{{ cleanLang(__('lang.date_range')) }}:</label>
                            <select class="form-control form-control-sm mr-2" id="analytics-date-range" onchange="loadAnalytics()">
                                <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>{{ cleanLang(__('lang.today')) }}</option>
                                <option value="yesterday" {{ $dateRange == 'yesterday' ? 'selected' : '' }}>{{ cleanLang(__('lang.yesterday')) }}</option>
                                <option value="last_7_days" {{ $dateRange == 'last_7_days' ? 'selected' : '' }}>{{ cleanLang(__('lang.last_7_days')) }}</option>
                                <option value="last_30_days" {{ $dateRange == 'last_30_days' ? 'selected' : '' }}>{{ cleanLang(__('lang.last_30_days')) }}</option>
                                <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>{{ cleanLang(__('lang.this_month')) }}</option>
                                <option value="last_month" {{ $dateRange == 'last_month' ? 'selected' : '' }}>{{ cleanLang(__('lang.last_month')) }}</option>
                                <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>{{ cleanLang(__('lang.custom')) }}</option>
                            </select>
                            <button class="btn btn-sm btn-primary" onclick="exportAnalytics()">
                                <i class="ti-download"></i> {{ cleanLang(__('lang.export')) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--key metrics-->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="m-r-20 text-white"><i class="ti-comments display-5"></i></div>
                            <div>
                                <h3 class="card-title text-white m-b-0">{{ $analytics['total_messages'] ?? 0 }}</h3>
                                <h6 class="card-subtitle text-white op-5">{{ cleanLang(__('lang.total_messages')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="m-r-20 text-white"><i class="ti-ticket display-5"></i></div>
                            <div>
                                <h3 class="card-title text-white m-b-0">{{ $analytics['total_tickets'] ?? 0 }}</h3>
                                <h6 class="card-subtitle text-white op-5">{{ cleanLang(__('lang.total_tickets')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="m-r-20 text-white"><i class="ti-time display-5"></i></div>
                            <div>
                                <h3 class="card-title text-white m-b-0">{{ $analytics['avg_response_time'] ?? '0m' }}</h3>
                                <h6 class="card-subtitle text-white op-5">{{ cleanLang(__('lang.avg_response_time')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="m-r-20 text-white"><i class="ti-check-box display-5"></i></div>
                            <div>
                                <h3 class="card-title text-white m-b-0">{{ $analytics['resolution_rate'] ?? '0' }}%</h3>
                                <h6 class="card-subtitle text-white op-5">{{ cleanLang(__('lang.resolution_rate')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--charts-->
        <div class="row">
            <!--messages over time-->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ cleanLang(__('lang.messages_over_time')) }}</h4>
                        <canvas id="messages-chart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!--ticket status distribution-->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ cleanLang(__('lang.ticket_status_distribution')) }}</h4>
                        <canvas id="status-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!--agent performance-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ cleanLang(__('lang.agent_performance')) }}</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ cleanLang(__('lang.agent')) }}</th>
                                        <th>{{ cleanLang(__('lang.tickets_handled')) }}</th>
                                        <th>{{ cleanLang(__('lang.messages_sent')) }}</th>
                                        <th>{{ cleanLang(__('lang.avg_response_time')) }}</th>
                                        <th>{{ cleanLang(__('lang.resolution_rate')) }}</th>
                                        <th>{{ cleanLang(__('lang.satisfaction_score')) }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($analytics['agent_performance']) && count($analytics['agent_performance']) > 0)
                                        @foreach($analytics['agent_performance'] as $agent)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ getUsersAvatar($agent->avatar_directory, $agent->avatar_filename) }}"
                                                        alt="user" class="img-circle avatar-xsmall mr-2">
                                                    <span>{{ $agent->first_name }} {{ $agent->last_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $agent->tickets_handled ?? 0 }}</td>
                                            <td>{{ $agent->messages_sent ?? 0 }}</td>
                                            <td>{{ $agent->avg_response_time ?? '0m' }}</td>
                                            <td>{{ $agent->resolution_rate ?? 0 }}%</td>
                                            <td>
                                                <span class="badge badge-{{ $agent->satisfaction_score >= 4 ? 'success' : ($agent->satisfaction_score >= 3 ? 'warning' : 'danger') }}">
                                                    {{ number_format($agent->satisfaction_score ?? 0, 1) }} / 5.0
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">{{ cleanLang(__('lang.no_data_available')) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--popular hours & response time-->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ cleanLang(__('lang.popular_hours')) }}</h4>
                        <canvas id="popular-hours-chart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ cleanLang(__('lang.response_time_distribution')) }}</h4>
                        <canvas id="response-time-chart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    function loadAnalytics() {
        const dateRange = $('#analytics-date-range').val();
        const clientId = $('#analytics-client').val();
        // Reload page with parameters
        let url = '?date_range=' + dateRange;
        if (clientId) {
            url += '&client_id=' + clientId;
        }
        window.location.href = url;
    }

    function exportAnalytics() {
        const dateRange = $('#analytics-date-range').val();
        const clientId = $('#analytics-client').val();
        let url = '/whatsapp/analytics/export?date_range=' + dateRange;
        if (clientId) {
            url += '&client_id=' + clientId;
        }
        window.location.href = url;
    }

    // Initialize charts with data from backend
    // Messages over time chart
    const messagesCtx = document.getElementById('messages-chart').getContext('2d');
    const messagesChart = new Chart(messagesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($analytics['messages_timeline']['labels'] ?? []) !!},
            datasets: [{
                label: '{{ cleanLang(__("lang.messages")) }}',
                data: {!! json_encode($analytics['messages_timeline']['data'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    // Status distribution chart
    const statusCtx = document.getElementById('status-chart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($analytics['status_distribution']['labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($analytics['status_distribution']['data'] ?? []) !!},
                backgroundColor: ['#5cb85c', '#f0ad4e', '#d9534f', '#5bc0de', '#777']
            }]
        }
    });

    // Popular hours chart
    const hoursCtx = document.getElementById('popular-hours-chart').getContext('2d');
    const hoursChart = new Chart(hoursCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($analytics['popular_hours']['labels'] ?? []) !!},
            datasets: [{
                label: '{{ cleanLang(__("lang.messages")) }}',
                data: {!! json_encode($analytics['popular_hours']['data'] ?? []) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        }
    });

    // Response time chart
    const responseCtx = document.getElementById('response-time-chart').getContext('2d');
    const responseChart = new Chart(responseCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($analytics['response_time_dist']['labels'] ?? []) !!},
            datasets: [{
                label: '{{ cleanLang(__("lang.tickets")) }}',
                data: {!! json_encode($analytics['response_time_dist']['data'] ?? []) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }]
        }
    });
</script>
