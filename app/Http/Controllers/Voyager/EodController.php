<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\ProjectTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EodController extends Controller
{
    public function eodContent() {
        $projectTarget = ProjectTarget::with(['tasks', 'project'])
                        ->whereDeveloperId(Auth::user()->id)
                        ->whereDate('created_at', today())->get();
        return response()->json(['data'=> $projectTarget]);
    }
}
