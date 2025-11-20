<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function create(Request $request) {

        $total = Ticket::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_NEW], $total[Ticket::STATUS_NEW]);
        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_IN_PROGRESS], $total[Ticket::STATUS_IN_PROGRESS]);
        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_READ], $total[Ticket::STATUS_READ]);
        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_RESOLVED], $total[Ticket::STATUS_RESOLVED]);
    }
}
