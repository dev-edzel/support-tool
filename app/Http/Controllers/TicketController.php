<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Traits\HasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    use HasLog;

    public function index(Request $request)
    {
        $tickets = Ticket::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            "Searching Ticket Successful",
            TicketResource::collection($tickets)
        );
    }

    public function store(TicketRequest $request)
    {
        $tix = $request->toArray();

        $ticket = Ticket::create($tix);

        return response()->success(
            'Storing Ticket Successful',
            new TicketResource($ticket)
        );
    }

    public function show(Ticket $ticket)
    {
        return response()->success(
            "Searching Ticket Successful",
            new TicketResource($ticket)
        );
    }
}
