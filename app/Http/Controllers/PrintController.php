<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Reserve;
use App\Models\UserRent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class PrintController extends Controller
{
    protected function InventoryReport($id) {
        $rent = UserRent::where('id', $id)->get()->first();
        $pdf = PDF::loadView('admin.pdf',['rent' => $rent])->setPaper('a4', 'landscape');
        // return $pdf->download('teknowize.pdf');
        return $pdf->stream();
    }
    protected function InventoryReportDownload($id) {
        $rent = UserRent::where('id', $id)->get()->first();
        $pdf = PDF::loadView('admin.pdf',['rent' => $rent])->setPaper('a4', 'landscape');
        // return $pdf->download('teknowize.pdf');
        return $pdf->download(md5($rent->info->name) . '.pdf');
    }
    protected function ReservationReport($id) {
        $client = Reserve::where('id', $id)->get()->first();
        $pdf = PDF::loadView('admin.reservation-report',['client' => $client])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    protected function ReservationReportDownload($id) {
        $client = Reserve::where('id', $id)->get()->first();
        $pdf = PDF::loadView('admin.reservation-report',['client' => $client])->setPaper('a4', 'portrait');
        return $pdf->download(md5($client->client) . '.pdf');
    }
}
