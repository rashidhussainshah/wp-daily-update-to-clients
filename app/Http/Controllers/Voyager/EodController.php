<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EodController extends Controller
{
    public function eodContent() {
        $project = Project::with(['targets' => function ($q) {
                            $q->whereDate('created_at', today());
                            $q->whereDeveloperId(Auth::user()->id);
                        }, 'targets.tasks', 'eodConfiguration'])->first();
        return response()->json(['data'=> $project]);
    }
}
