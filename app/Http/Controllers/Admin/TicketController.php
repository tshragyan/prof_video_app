<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function new(Request $request)
    {
        $tickets = Ticket::query()->where('status', '=', Ticket::STATUS_NEW)->paginate(20);
        $title = 'New Tickets';
        return view('admin.tickets.index', compact('tickets', 'title'));
    }

    public function inProgress(Request $request)
    {
        $tickets = Ticket::query()->where('status', '=', Ticket::STATUS_IN_PROGRESS)->paginate(20);
        $title = 'In Progress Tickets';
        return view('admin.tickets.index', compact('tickets', 'title'));
    }

    public function read(Request $request)
    {
        $tickets = Ticket::query()->where('status', '=', Ticket::STATUS_READ)->paginate(20);
        $title = 'Read Tickets';
        return view('admin.tickets.index', compact('tickets', 'title'));
    }

    public function resolved(Request $request)
    {
        $tickets = Ticket::query()->where('status', '=', Ticket::STATUS_RESOLVED)->paginate(20);
        $title = 'Resolved Tickets';
        return view('admin.tickets.index', compact('tickets', 'title'));
    }

    public function show(Ticket $ticket)
    {
//           cache()->get(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_NEW]),
//           cache()->get(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_IN_PROGRESS]),
//           cache()->get(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_READ]),
//           cache()->get(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_RESOLVED])
//       );
//
//        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_NEW], $total[Ticket::STATUS_NEW]);
//        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_IN_PROGRESS], $total[Ticket::STATUS_IN_PROGRESS]);
//        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_READ], $total[Ticket::STATUS_READ]);
//        cache()->put(Ticket::STATUS_CACHE_COUNT_MAP[Ticket::STATUS_RESOLVED], $total[Ticket::STATUS_RESOLVED]);

        if ($ticket->status == Ticket::STATUS_NEW) {
            $ticket->status = Ticket::STATUS_READ;
            $ticket->save();
        }

        if ($ticket->ticketMessages) {
            $ticket->ticketMessages()
                ->where('is_question', '=', true)
                ->update(['status' => TicketMessages::STATUS_READ]);
        }

        return view('admin.tickets.show', compact('ticket'));
    }

    public function update(Ticket $ticket, Request $request)
    {
        $ticket->status = $request->input('status');
        $ticket->save();
        return redirect()->route('admin.tickets.show', ['ticket' => $ticket->id])->with(['success' => 'Ticket Updated']);
    }
}
