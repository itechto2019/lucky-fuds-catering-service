
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $rent->info->name }} | Receipt {{ today()->format('Y-m-d') }}</title>
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
            <h3>{{$status}}</h3>
            <table >
                <tr>
                    <td>Item</td>
                    <td align="right">{{ $rent->stock->item }}</td>
                </tr>
                <tr>
                    <td>Client</td>
                    <td align="right">{{ $rent->info->name }}</td>
                </tr>
                <tr>
                    <td>Method</td>
                    <td align="right">{{ $rent->delivers ? "Deliver" : ($rent->pickups ? "Pickup" : "") }}</td>
                </tr>
                <tr>
                    <td>Mode of Payment:</td>
                    <td align="right">
                        {{ $rent->transaction->payment_method == 0 ? "Cash Payment" : "Online Payment"}} / {{ $rent->transaction->extend_online_transaction ? "Online Payment" : "Cash Payment"}}
                    </td>
                </tr>
                <tr>
                    <td>Contact</td>
                    <td align="right">{{ $rent->info->method == "contact" ? $rent->info->contact : ($rent->info->method == "email" ? $rent->info->user->email : "None")  }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td align="right">{{ $rent->address }}</td>
                </tr>
                <tr>
                    <td>Quantity</td>
                    <td align="right">{{ $rent->amount / $rent->stock->price }}</td>
                </tr>
    
                <tr>
                    <td>Amount</td>
                    <td align="right">₱{{ $rent->amount }}</td>
                </tr>
    
                <tr>
                    <td>Date for use</td>
                    <td align="right">{{ $rent->extends ? $rent->extends->date : $rent->date  }}</td>
                </tr>
                <tr>
                    <td>Date for return</td>
                    <td align="right">{{ $rent->extends ? $rent->extends->return : $rent->return }}</td>
                </tr>
            </table>
        </div>
    </div>
</center>