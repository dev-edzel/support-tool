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
        $users = User::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            'Searching Users Successful',
            UserResource::collection($users)
        );
    }

    public function show(User $user)
    {
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

    public function destroy(string $id)
    {
        //
    }
}
