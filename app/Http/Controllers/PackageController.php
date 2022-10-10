<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }
    public function createPackage(Request $request) {
        $form = $request->validate([
            'name' => "required|min:25",
            'details' => "required|min:50",
            'price' => "required|min:0"
        ]);
        $result = Package::create([
            'name' => $form['name'],
            'details' => $form['details'],
            'price' => $form['price']
        ]);
        return redirect()->back();
    }
}
