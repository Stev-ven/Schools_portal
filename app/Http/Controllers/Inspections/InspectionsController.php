<?php

namespace App\Http\Controllers\Inspections;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspectionsController extends Controller
{
    public function inspectionReport(){
        return view('inspectionreports.inspections-reports');
    }
}
