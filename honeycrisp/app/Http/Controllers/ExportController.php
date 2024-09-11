<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Export;

class ExportController extends Controller
{
    public function index(Request $request) {
        $exports = Export::paginate(100);

        return view('exports.index', compact('exports'));
    }

    public function show(Request $request, $id) {
        $export = Export::findOrFail($id);

        return view('exports.show', compact('export'));
    }


    public function download(Export $export) {
        return response()->download($export->path);
    }
}
