<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketInfoRequest;
use App\Http\Resources\TicketInfoResource;
use App\Mail\TicketMail;
use App\Models\Ticket;
use App\Models\TicketInfo;
use App\Models\TicketType;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TicketInfoController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        $tickets = TicketInfo::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            'Searching Ticket Info Successful',
            TicketInfoResource::collection($tickets)
        );
    }

    public function store(TicketInfoRequest $request)
    {
        $validatedData = $request->validated();

        $ticketType = TicketType::findOrFail($validatedData['ticket_type_id']);

        $ticketNumber = $ticketType
            ->short_name . 'TICKETNO' . rand(1000000, 99999999);

        $ticketInfo = TicketInfo::create($validatedData);

        $ticket = new Ticket([
            'ticket_number' => $ticketNumber,
            'ticket_info_id' => $ticketInfo->id,
            'status' => 'OPEN'
        ]);
        $ticket->save();

        Mail::to($validatedData['email'])->send(
            new TicketMail($ticketNumber, $ticketInfo, 'OPEN')
        );

        return response()->success(
            'Storing Ticket Successful',
            new TicketInfoResource($ticketInfo)
        );
    }

    public function show(TicketInfo $ticket_info)
    {
        return response()->success(
            'Searching Ticket Info Successful',
            new TicketInfoResource($ticket_info)
        );
    }

    public function update(TicketInfoRequest $request, TicketInfo $ticket_info)
    {
        $changes = DB::transaction(function () use ($request, $ticket_info) {
            $changes = $this->resourceParser($request, $ticket_info);

            if ($changes) {
                $log = $this->log('UPDATE TICKET', $changes);
                $ticket_info->update([
                    'last_modified_log_id' => $log->id
                ]);
            }

            return $changes;
        });

        return response()->success(
            $changes
                ? 'Updating Ticket Successful'
                : 'No changes made.',
            new TicketInfoResource($ticket_info)
        );
    }

    public function destroy(TicketInfo $ticket_info)
    {
        DB::transaction(function () use ($ticket_info) {
            $log = $this->log('REMOVE TICKET INFO', $ticket_info);
            $ticket_info->last_modified_log_id = $log->id;
            $ticket_info->save();
            $ticket_info->delete();
        });

        return response()->success(
            'Deleting Ticket Info Successful',
            new TicketInfoResource($ticket_info)
        );
    }

    public function trashed(Request $request)
    {
        $ticket_info = TicketInfo::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Searching Deleted Ticket Info Successful',
            TicketInfoResource::collection($ticket_info)
        );
    }

    public function restore(string $id)
    {
        $ticket_info = TicketInfo::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($ticket_info) {
            $log = $this->log('RESTORE TICKET INFO', $ticket_info);
            $ticket_info->last_modified_log_id = $log->id;
            $ticket_info->save();
            $ticket_info->restore();
        });

        return response()->success(
            'Restoring Ticket Info Successful',
            new TicketInfoResource($ticket_info)
        );
    }
}
