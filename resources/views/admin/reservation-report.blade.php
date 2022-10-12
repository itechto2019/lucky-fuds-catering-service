<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>{{ $client->client }} | Receipt {{ today()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-size: 17px;
            width: 400px;
        }
        .receipt {
            padding: 10px;
            border: 1px solid #36AE7C;
            width: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            border: 1px solid #D0D0D0;
        }
    </style>
</head>
<div class="receipt">
    <div class="receipt-container" style="text-align: center; border: 1px solid #D0D0D0;background-color: #F6F6F6;">
        <img src="{{ public_path('assets') . '/logo.jpg' }}" width="60" alt="">
        <h3>Lucky Fuds | Catering Services - Official Receipt</h3>
    </div>
    <div class="receipt-box">
        <table >
            <tr>
                <td>Event</td>
                <td align="right">{{ $client->event }}</td>
            </tr>
            <tr>
                <td>Amount</td>
                <td align="right">{{ $client->package->price }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td align="right">{{ $client->date }}</td>
            </tr>
            <tr>
                <td>Time</td>
                <td align="right">{{ date('h:i A', strtotime($client->time)) }}</td>
            </tr>
            <tr>
                <td>Contact</td>
                <td align="right">{{ $client->contact }}</td>
            </tr>

            <tr>
                <td>Email</td>
                <td align="right">{{ $client->email }}</td>
            </tr>
        </table>
    </div>
</div>