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
use Illuminate\Support\Facades\Storage;

class UserInfoController extends Controller
{
    use HasLogger, HasHelper;

    const MSG_SEARCH_SUCCESS = "Searching User Info Successful";

    public function index(Request $request)
    {
        $user_info = UserInfo::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            self::MSG_SEARCH_SUCCESS,
            UserInfoResource::collection($user_info)
        );
    }

    public function store(UserInfoRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('photo')) {
                $imagePath = $request
                    ->file('photo')
                    ->store('public/images');
                $imageName = basename($imagePath);
                $validated['photo'] = $imageName;
            }

            $user_info = UserInfo::create($validated);

            return response()->success(
                'Storing User Info Successful',
                new UserInfoResource($user_info)
            );
        } catch (\Exception $e) {
            // Rollback image upload if an error occurs
            if (isset($imagePath)) {
                Storage::delete($imagePath);
            }

            return response()->failed(
                'Storing User Info Failed',
                $e->getMessage()
            );
        }
    }

    public function show(UserInfo $user_info)
    {
        return response()->success(
            self::MSG_SEARCH_SUCCESS,
            new UserInfoResource($user_info)
        );
    }

    public function update(UserInfoRequest $request, UserInfo $user_info)
    {
        try {
            $imageName = $user_info->logo_path;
            if ($request->hasFile('photo')) {
                $imagePath = $request->file('photo')
                    ->store('public/images');
                $imageName = basename($imagePath);
            }

            $changes = DB::transaction(function ()
            use ($request, $user_info, $imageName) {
                $changes = $this->resourceParser(
                    $request,
                    $user_info,
                    ['photo' => $imageName]
                );

                if ($changes) {
                    $log = $this->log('UPDATE USER INFO', $changes);
                    $user_info->update([
                        'last_modified_log_id' => $log->id
                    ]);
                }

                return $changes;
            });

            return response()->success(
                $changes
                    ? 'Updating User Info Successful'
                    : 'No changes made.',
                new UserInfoResource($user_info)
            );
        } catch (\Exception $e) {
            // Rollback image upload if an error occurs
            if (isset($imagePath)) {
                Storage::delete($imagePath);
            }

            return response()->failed(
                'Error updating User Info',
                $e->getMessage()
            );
        }
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
        return response()->success(
            self::MSG_SEARCH_SUCCESS,
            UserInfoResource::collection(
                UserInfo::search($request->input('search'))
                    ->onlyTrashed()->paginate(10)
            )
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
            'Restoring User Info Successful',
            new UserInfoResource($user_info)
        );
    }
}
