<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLA Breach Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #dc3545;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .btn:hover {
            background: #0056b3;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ SLA Breach Alert</h1>
        </div>

        <div class="content">
            <p>Hello {{ $userName }},</p>

            <div class="alert-box">
                <strong>SLA Target Missed!</strong><br>
                A WhatsApp ticket has breached its SLA target and requires immediate attention.
            </div>

            <table class="info-table">
                <tr>
                    <td>Ticket Number:</td>
                    <td><strong>{{ $ticketNumber }}</strong></td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td>{{ $ticketSubject }}</td>
                </tr>
                <tr>
                    <td>Contact Name:</td>
                    <td>{{ $contactName }}</td>
                </tr>
                <tr>
                    <td>Contact Phone:</td>
                    <td>{{ $contactPhone }}</td>
                </tr>
                <tr>
                    <td>Breach Type:</td>
                    <td><span style="color: #dc3545; font-weight: bold;">{{ $breachType }}</span></td>
                </tr>
                <tr>
                    <td>Target Time:</td>
                    <td>{{ $targetTime }}</td>
                </tr>
            </table>

            <p><strong>Action Required:</strong></p>
            <ul>
                <li>Review the ticket immediately</li>
                <li>Respond to the customer as soon as possible</li>
                <li>Escalate if necessary</li>
            </ul>

            <center>
                <a href="{{ $ticketUrl }}" class="btn">View Ticket</a>
            </center>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                This is an automated notification from your WhatsApp SLA monitoring system.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This email was sent to {{ $userName }} regarding ticket {{ $ticketNumber }}</p>
        </div>
    </div>
</body>
</html>
