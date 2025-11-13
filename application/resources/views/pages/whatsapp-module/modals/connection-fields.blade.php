<!--Dynamic Connection Type Fields - QR Code Connections Only-->
@if($type == 'evolution')
    <div class="alert alert-success">
        <i class="fab fa-whatsapp"></i>
        <strong>Evolution API - FREE QR Code Connection</strong><br>
        <small>
            1. Install Evolution API: <code>docker run -d --name evolution-api -p 8080:8080 -e AUTHENTICATION_API_KEY=your-secret-key atendai/evolution-api:latest</code><br>
            2. Enter your API URL and Key below<br>
            3. After creating, you'll get a QR code to scan with WhatsApp
        </small>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.api_url')) }}*</label>
        <div class="col-sm-12">
            <input type="text" class="form-control form-control-sm" name="api_url"
                   value="{{ $connection->whatsappconnection_webhook_url ?? 'http://localhost:8080' }}"
                   placeholder="http://localhost:8080 or http://your-server-ip:8080" required>
            <small class="form-text text-muted">Your Evolution API server URL</small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.api_key')) }}*</label>
        <div class="col-sm-12">
            <input type="password" class="form-control form-control-sm" name="api_key"
                   value="{{ $connection->whatsappconnection_api_key ?? '' }}"
                   placeholder="Your Evolution API authentication key" required>
            <small class="form-text text-muted">The AUTHENTICATION_API_KEY from your Evolution API setup</small>
        </div>
    </div>

@elseif($type == 'wati')
    <div class="alert alert-info">
        <i class="ti-info"></i>
        <strong>WATI - QR Code Connection (Paid Service)</strong><br>
        <small>
            1. Sign up at: <a href="https://app.wati.io/signup" target="_blank" class="text-primary">app.wati.io/signup</a><br>
            2. Get your API credentials from WATI dashboard<br>
            3. After creating, scan QR code from WATI dashboard
        </small>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.api_endpoint')) }}</label>
        <div class="col-sm-12">
            <input type="text" class="form-control form-control-sm" name="api_url"
                   value="{{ $connection->whatsappconnection_webhook_url ?? 'https://app-server.wati.io' }}" readonly>
            <small class="form-text text-muted">Default WATI API endpoint</small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.access_token')) }}</label>
        <div class="col-sm-12">
            <input type="password" class="form-control form-control-sm" name="access_token"
                   value="{{ $connection->whatsappconnection_api_key ?? '' }}"
                   placeholder="Get from WATI dashboard">
            <small class="form-text text-muted">Your WATI API access token</small>
        </div>
    </div>

@else
    <div class="alert alert-warning">
        <i class="ti-alert"></i> Please select a connection type above
    </div>
@endif
