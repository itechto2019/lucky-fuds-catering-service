<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $client->info->name }} | Record {{ today()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 14px;
            width: 500px;
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
<center>
    <div class="receipt">
        <div class="receipt-container" style="text-align: center; border: 1px solid #D0D0D0;background-color: #F6F6F6;padding: 10px">
            <img src="{{ public_path('assets') . '/logo.jpg' }}" width="60" alt="">
            <h3>Lucky Fuds | Catering Services - Official Record</h3>
        </div>
        <div class="receipt-box">
            <h1>{{$status}}</h1>
            <table >
                <tr>
                    <td>Event</td>
                    <td align="right">{{ $client->event }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td align="right">{{ $client->info->address }}</td>
                </tr>
                <tr>
                    <td>Package</td>
                    <td align="right">{{ $client->package->name }}</td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td align="right">₱{{ $client->package->price }}</td>
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
                <tr>
                    <td>Prefered Contact</td>
                    <td align="right">{{ $client->info->method == "email" ? $client->email : ($client->info->method == "contact" ? $client->info->contact : "N/A" )}}</td>
                </tr>
            </table>
        </div>
    </div>
</center>