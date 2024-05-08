<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketTypeRequest;
use App\Http\Resources\TicketTypeResource;
use App\Models\TicketType;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketTypeController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        // $this->authorize('view-tickets');

        $tixtype = TicketType::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return TicketTypeResource::collection($tixtype);
    }

    public function store(TicketTypeRequest $request)
    {
        $data = $request->toArray();

        $ticket_type = TicketType::create($data);

        return response()->success(
            'Storing Ticket Type Successful',
            new TicketTypeResource($ticket_type)
        );
    }

    public function show(string $id)
    {
        $ticket_type = TicketType::findOrFail($id);

        return response()->success(
            'Searching Ticket Type Successful',
            new TicketTypeResource($ticket_type)
        );
    }

    public function update(TicketTypeRequest $request, TicketType $ticket_type)
    {
        $changes = DB::transaction(function () use ($request, $ticket_type) {
            $changes = $this->resourceParser($request, $ticket_type);

            if ($changes) {
                $log = $this->log('UPDATE TICKET TYPE', $changes);
                $ticket_type->update(['last_modified_log_id' => $log->id]);
            }

            return $changes;
        });

        return response()->success(
            $changes ? 'Updating Ticket Type Successful' : 'No changes made.',
            new TicketTypeResource($ticket_type)
        );
    }

    public function destroy(TicketType $ticket_type)
    {
        DB::transaction(function () use ($ticket_type) {
            $log = $this->log('REMOVE TICKET TYPE', $ticket_type);
            $ticket_type->last_modified_log_id = $log->id;
            $ticket_type->save();
            $ticket_type->delete();
        });

        return response()->success(
            'Deleting Ticket Type Successful',
            new TicketTypeResource($ticket_type)
        );
    }

    public function trashed(Request $request)
    {
        $ticket_type = TicketType::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Searching Deleted Ticket Type Successful',
            TicketTypeResource::collection($ticket_type)
        );
    }

    public function restore(string $id)
    {
        $ticket_type = TicketType::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($ticket_type) {
            $log = $this->log('RESTORE TICKET TYPE', $ticket_type);
            $ticket_type->last_modified_log_id = $log->id;
            $ticket_type->save();
            $ticket_type->restore();
        });

        return response()->success(
            "Restoring Ticket Type Successful",
            new TicketTypeResource($ticket_type)
        );
    }
}
