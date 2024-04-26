<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HasLogger
{
    public function log($activity, $data = "{}", $id = null)
    {
        try {
            $user = User::find($id) ?: Auth::user();

            $id = $user?->id ?? 0;
            $username = $user?->username ?? 'sample_admin';
            $roleId = $user?->role?->id ?? 0;

            return UserLog::create([
                'initiator_id' => $id,
                'initiator_username' => $username,
                'initiator_role' => $roleId,
                'activity' => $activity,
                'details' => $this->parseData($data)
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, "Activity cannot proceed. {$e}");
        }
    }

    public function parseData($data)
    {
        return is_array($data) ? json_encode($data) : $data;
    }
}
