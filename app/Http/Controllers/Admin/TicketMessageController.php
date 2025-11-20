<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketMessageCreateRequest;
use App\Models\Ticket;
use App\Models\TicketMessages;
use Illuminate\Http\Request;

class TicketMessageController extends Controller
{
    public function create(Ticket $ticket, TicketMessageCreateRequest $request)
    {
        $file = $request->file('image');
        $imageName = "ticket_$ticket->id" . "_" . time() . '.' . $file->getExtension();
        $path = $file->storeAs('uploads/admin/tickets/messages/', $imageName, 'public');

        TicketMessages::query()->create(
            [
                'user_id' => auth()->user()->id,
                'ticket_id' => $ticket->id,
                'message' => $request->validated()['message'],
                'status' => TicketMessages::STATUS_NEW,
                'is_answer' => true,
                'image' => asset('storage/' . $path),
                'created_at' => now(),
            ]
        );

        return redirect()->route('admin.tickets.show', ['ticket' => $ticket->id]);
    }
}
