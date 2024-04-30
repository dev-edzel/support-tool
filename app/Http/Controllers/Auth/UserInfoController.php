<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserInfoRequest;
use App\Http\Resources\UserInfoResource;
use App\Models\UserInfo;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserInfoController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        $ticket_type = UserInfo::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            "Searching User Info Successful",
            UserInfoResource::collection($ticket_type)
        );
    }

    public function store(UserInfoRequest $request)
    {
        $data = $request->toArray();

        $user_info = UserInfo::create($data);

        return response()->success(
            'Storing User Info Successful',
            new UserInfoResource($user_info)
        );
    }

    public function show(UserInfo $user_info)
    {
        return response()->success(
            'Searching User Info Successful',
            new UserInfoResource($user_info)
        );
    }

    public function update(UserInfoRequest $request, UserInfo $user_info)
    {
        $changes = DB::transaction(function () use ($request, $user_info) {
            $changes = $this->resourceParser($request, $user_info);

            if ($changes) {
                $log = $this->log('UPDATE INFO', $changes);
                $user_info->update(['last_modified_log_id' => $log->id]);
            }

            return $changes;
        });

        return response()->success(
            $changes ? 'Updating User Info Successful' : 'No changes made.',
            new UserInfoResource($user_info)
        );
    }

    public function destroy(UserInfo $user_info)
    {
        DB::transaction(function () use ($user_info) {
            $log = $this->log('REMOVE USER INFO', $user_info);
            $user_info->last_modified_log_id = $log->id;
            $user_info->save();
            $user_info->delete();
        });

        return response()->success(
            'Deleting User Info Successful',
            new UserInfoResource($user_info)
        );
    }

    public function trashed(Request $request)
    {
        $user_info = UserInfo::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Searching Deleted User Info Successful',
            UserInfoResource::collection($user_info)
        );
    }

    public function restore(string $id)
    {
        $user_info = UserInfo::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($user_info) {
            $log = $this->log('RESTORE USER INFO', $user_info);
            $user_info->last_modified_log_id = $log->id;
            $user_info->save();
            $user_info->restore();
        });

        return response()->success(
            "Restoring User Info Successful",
            new UserInfoResource($user_info)
        );
    }
}
