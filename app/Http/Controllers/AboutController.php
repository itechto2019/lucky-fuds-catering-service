<?php

namespace App\Http\Controllers;

use App\Models\AboutInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function view() {
        return view('admin.settings.about-update');
    }
    public function create_about(Request $request) {
        $form = $request->validate([
            'body' => 'required',
        ]);
        AboutInfo::where('user', 1)->update([
            'body' => $form['body'],
        ]);
        return back()->with([
            'message' => 'Saved'
        ]);
    }
    public function about() {
        $about = AboutInfo::where('user', 1)->first();
        return view('about')->with(compact(['about']));
    }
}
