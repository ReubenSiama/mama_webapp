<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Voerro\Laravel\VisitorTracker\Models\Visit;
use Voerro\Laravel\VisitorTracker\Facades\VisitStats;

class TrackingController extends Controller
{
    public function getTracking(){
	$visits1y = VisitStats::query()->visits()
            ->except(['ajax', 'bots'])
            ->period(Carbon::now()->subYears(1));
	dd($visits1y);
	return response()->json(['visitors'=>$visits1y]);
    }
}
