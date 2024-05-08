<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketInfoResource;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        // $this->authorize('view-tickets');

        $tix = Ticket::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return TicketResource::collection($tix);
    }

    public function show(string $id)
    {
        $tix = Ticket::findOrFail($id);

        return response()->success(
            'Searching Ticket Successful',
            new TicketResource($tix)
        );
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        $changes = DB::transaction(function () use ($request, $ticket) {
            $changes = $this->resourceParser($request, $ticket);

            if ($changes) {
                $log = $this->log('UPDATE TICKET', $changes);
                $ticket->update([
                    'last_modified_log_id' => $log->id
                ]);
            }

            return $changes;
        });

        return response()->success(
            $changes
                ? 'Updating Ticket Successful'
                : 'No changes made.',
            new TicketResource($ticket)
        );
    }

    public function destroy(Ticket $ticket)
    {

        DB::transaction(function () use ($ticket) {
            $log = $this->log('REMOVE TICKET', $ticket);
            $ticket->last_modified_log_id = $log->id;
            $ticket->save();
            $ticket->delete();
        });

        return response()->success(
            'Deleting Ticket Successful',
            new TicketResource($ticket)
        );
    }

    public function trashed(Request $request)
    {
        $tickets = Ticket::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Searching Deleted Ticket Successful',
            TicketResource::collection($tickets)
        );
    }

    public function restore(string $id)
    {
        $ticket = Ticket::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($ticket) {
            $log = $this->log('RESTORE TICKET', $ticket);
            $ticket->last_modified_log_id = $log->id;
            $ticket->save();
            $ticket->restore();
        });

        return response()->success(
            'Restoring Ticket Successful',
            new TicketResource($ticket)
        );
    }

    public function getTicketInfoByNumber(TicketRequest $request)
    {
        $ticketNumber = $request->input('ticket_number');

        $ticket = Ticket::with('ticket_info')
            ->where('ticket_number', $ticketNumber)->first();

        if (!$ticket) {
            return response()->failed('Ticket information not found');
        }

        return response()->success(
            'Retrieving Ticket Information Successful',
            [
                'status' => $ticket->status,
                'ticket_number' => $ticket->ticket_number,
                'ticket_info' => new TicketInfoResource($ticket->ticket_info),
            ]
        );
    }
    public function assign_ticket(Request $request)
    {
        $this->authorize('assign-ticket');

        $request->validate([
            'ticket_id' => ['required', 'exists:tickets,id'],
            'username' => ['required', 'exists:users,username'],
            'status' => ['nullable', 'string', Rule::in([
                'OPEN', 'ASSIGNED', 'CANCELLED', 'CLOSED'
            ])],
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        $ticket->update([
            'assigned_to' => $request->username,
        ]);

        return response()->success(
            'Ticket assigned successfully',
            new TicketResource($ticket)
        );
    }
}
