{{--
<link rel="stylesheet" href="css/app.css"> --}}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
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
</style>
<div class="receipt-report">
    <div class="receipt">
        <div style="
        padding:10px;
        ">
            <p>Date: {{ date('Y-m-d') }}</p>
        </div>
        <div>
            <h3 style="text-align: center" class="">Lucky Fuds | Catering Services</h3>
        </div>
        <div>
            <h4 style="text-align: center" class="">REPORTS</h4>
        </div>
        <div class="table-form">
            <table>
                <thead>
                    <tr>
                        <th scope="col" style="padding:10px">#</th>
                        <th scope="col" style="padding:10px">Image</th>
                        <th scope="col" style="padding:10px">Item</th>
                        <th scope="col" style="padding:10px">Client</th>
                        <th scope="col" style="padding:10px">Quantity</th>
                        <th scope="col" style="padding:10px">Amount</th>
                        <th scope="col" style="padding:10px">Date for use(?)</th>
                        <th scope="col" style="padding:10px">Date for return(?)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @if (is_array($result) || is_object($result)) --}}
                    @forelse ($reports as $report)
                    <tr>
                        <td class="text-center" scope="row" style="padding:10px">{{ $report->id}}</td>
                        <td class="text-center" style="padding:10px"><img
                                src="{{ public_path('stocks/'). $report->image }}" width="40" height="50" /></td>
                        <td style="padding:10px">{{ $report->item }}</td>
                        <td style="padding:10px">{{ $report->client }}</td>
                        <td style="padding:10px">{{ $report->quantity }}</td>
                        <td style="padding:10px">{{ $report->amount }}</td>
                        <td style="padding:10px"><small>{{ $report->date }}</small></td>
                        <td style="padding:10px"><small>{{ $report->return }}</small></td>
                    </tr>

                    @empty
                    <div class="alert-warning p-3">
                        <h4>No result found!</h4>
                    </div>
                    @endforelse
                    {{-- @endif --}}
                </tbody>
            </table>
        </div>
    </div>
</div>