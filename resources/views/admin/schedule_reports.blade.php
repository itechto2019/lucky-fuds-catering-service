@extends('index')
@section('schedule_reports')
<div class="for-schedule-reports">
    <div class="for-page-title">
        <h1>Reservation Reports</h1>
    </div>
    <div class="table-reservation">
        <div class="table-form">
            @if($reports->isNotEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Date Time</th>
                    <th>Client</th>
                    <th>Event</th>
                    <th>Event Schedule</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ date('h:i A', strtotime($report->time)) }}</td>
                        <td>{{ $report->info->name }}</td>
                        <td>{{ $report->event }}</td>
                        <td>{{ $report->date }}</td>
                        <td>₱{{ $report->package->price }}</td>
                        <td>{{ $report->reserve->status }}</td>
                        <td>
                            <div class="action-form">
                                <div class="action-button">
                                    <form action="{{ route('export_reservation', $report->id) }}" method="GET">
                                        <button class="action-print">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                            </svg>     
                                            Print                                 
                                        </button>
                                    </form>
                                </div>
                                <div class="action-button">
                                    <form action="{{ route('download_reservation', $report->id) }}" method="GET">
                                        <button class="action-print">    
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                              </svg>
                                              
                                            Download                                 
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>   
                @endforeach
                             
            </table>
            @else
                <div style="padding: 10px">
                    <h3><i>No such reports available!</i></h3>
                </div>
                <a href="{{ route('schedule_reservation') }}" style="text-decoration: none; color:#06283D"> <- Check reservation</a>
            @endif
        </div>
    </div>
</div>
@endsection