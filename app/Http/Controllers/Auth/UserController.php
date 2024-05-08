<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        // $this->authorize('view-tickets');

        $user = User::search($request->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return UserResource::collection($user);
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->success(
            'Searching User Successful',
            new UserResource($user)
        );
    }

    public function update(RegisterRequest $request, User $user)
    {
        $changes = DB::transaction(function () use ($request, $user) {
            $changes = $this->resourceParser($request, $user);

            if ($changes) {
                $log = $this->log('UPDATE USER', $changes);
                $user->update([
                    'last_modified_log_id' => $log->id
                ]);
            }

            return $changes;
        });

        return response()->success(
            $changes
                ? 'Updating Category Successful'
                : 'No changes made.',
            new UserResource($user)
        );
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $log = $this->log('REMOVE USER', $user);
            $user->last_modified_log_id = $log->id;
            $user->save();
            $user->delete();
        });

        return response()->success(
            'Deleting User Info Successful',
            new UserResource($user)
        );
    }

    public function trashed(Request $request)
    {
        return response()->success(
            'Searching Deleted User Successful',
            UserResource::collection(
                User::search($request->input('search'))
                    ->onlyTrashed()->paginate(10)
            )
        );
    }

    public function restore(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($user) {
            $log = $this->log('RESTORE USER', $user);
            $user->last_modified_log_id = $log->id;
            $user->save();
            $user->restore();
        });

        return response()->success(
            'Restoring User Successful',
            new UserResource($user)
        );
    }
}
