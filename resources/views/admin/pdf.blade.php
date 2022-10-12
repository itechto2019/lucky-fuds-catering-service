{{--
<link rel="stylesheet" href="css/app.css"> --}}

<head>
    <title>{{ $report->client }} | Receipt {{ today()->format('Y-m-d') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
{{-- <style>
    * {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
    }

    table {
        caption-side: bottom;
        border-collapse: collapse;
        width: 100%;
        text-align: center
    }

    /* Reservation */
    .table-reservation {
        padding: 10px;
    }

    .table-form {
        padding: 10px;
        overflow-y: auto;
        max-height: 400px;
    }

    .table-form::-webkit-scrollbar {
        display: none;
    }

    .table-form table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-form table th {
        padding: 10px;
        background-color: #70AD47;
        border: 1px solid #047b58;
        color: #F6F6F6;
    }

    .table-form table td {
        text-align: left;
        padding: 10px;
        border: 1px solid #047b58;
    }

    .table-form table tr {
        background-color: #EBF1E9;
    }

    .table-form table tr:nth-child(even) {
        background-color: #D5E3CF;
    }

    .table-form .action-form {
        display: grid;
        grid-template-columns: auto auto auto;
        place-content: center;
        column-gap: 5px;
    }

    .table-form .action-button button {
        padding: 10px;
        border: 1px solid #047b58;
        background-color: #70AD47;
        width: 100%;
        color: #f6f6f6;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .table-form .action-button .action-print {
        background-color: #28A745;
        padding: 5px;
    }

    .table-form .action-button .action-print svg {
        width: 25px;
        height: 25px;
    }
    .receipt-report {
        display: grid;
        place-items: center;
        place-content: center;
    }
</style> --}}
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