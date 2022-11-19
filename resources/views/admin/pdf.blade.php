
<head>
    <title>{{ $report->client }} | Receipt {{ today()->format('Y-m-d') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
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
<div class="receipt">
    <div class="receipt-container" style="text-align: center; border: 1px solid #D0D0D0;background-color: #F6F6F6;">
        <img src="{{ public_path('assets') . '/logo.jpg' }}" width="60" alt="">
        <h3>Lucky Fuds | Catering Services - Official Receipt</h3>
    </div>
    <div class="receipt-box">
        <table >

            <tr>
                <td>Item</td>
                <td align="right">{{ $report->item }}</td>
            </tr>
            <tr>
                <td>Client</td>
                <td align="right">{{ $report->client }}</td>
            </tr>
            <tr>
                <td>Method</td>
                <td align="right">{{ $report->method }}</td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td align="right">{{ $report->quantity }}</td>
            </tr>

            <tr>
                <td>Amount</td>
                <td align="right">{{ $report->amount }}</td>
            </tr>

            <tr>
                <td>Date for use</td>
                <td align="right">{{ $report->date }}</td>
            </tr>
            <tr>
                <td>Date for return</td>
                <td align="right">{{ $report->return }}</td>
            </tr>
        </table>
    </div>
</div>