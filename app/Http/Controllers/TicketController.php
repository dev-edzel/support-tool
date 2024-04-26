<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Traits\HasHelper;
use App\Traits\HasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    use HasLog, HasHelper;

    public function index(Request $request)
    {
        $tickets = Ticket::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            "Searching Ticket Successful",
            TicketResource::collection($tickets)
        );
    }

    public function store(TicketRequest $request)
    {
        $data = $request->toArray();

        $tix = Ticket::create([
            ...$data,
            'ticket_number' => rand(1000000, 999999999)
        ]);

        return response()->success(

            'Storing Ticket Successful',
            new TicketResource($tix)
        );
    }

    public function show(Ticket $ticket)
    {
        return response()->success(
            "Searching Ticket Successful",
            new TicketResource($ticket)
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
            "Deleting Ticket Successful",
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
            "Searching Deleted Ticket Successful",
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
            "Restoring Ticket Successful",
            new TicketResource($ticket)
        );
    }
}
