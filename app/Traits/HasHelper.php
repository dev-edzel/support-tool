<?php

namespace App\Traits;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

trait HasHelper
{
    public function relatedRss($rss, $rid, $r)
    {
        if ($rss->resource instanceof MissingValue) {
            return [$rid => $this->getAttribute($rid)];
        } else {
            return [$r => $rss];
        }
    }

    public function resourceParser($request, $resource, $data = []): array
    {
        $data = array_filter(
            array_merge(
                $data,
                $request->validated() ?: $request->all()
            )
        );

        $ini = $resource->getAttributes();

        $resource->update($data);

        return $this->parseChanges(
            $ini,
            $resource->getChanges(),
            $resource->getHidden()
        );
    }

    public function sort($callback, $options = SORT_REGULAR, $descending = false)
    {
        if (is_array($callback) && !is_callable($callback)) {
            return $this->sortByMany($callback, $options);
        }

        $results = [];

        $callback = $this->valueRetriever($callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // grab all the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options)
            : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }

    private function getCredentials(LoginRequest $request)
    {
        $input = $request->filled('username') ? 'username' : 'email';
        return [
            $input => $request->input($input),
            'password' => $request->input('password'),
        ];
    }

    private function findUser(array $credentials)
    {
        $input = array_key_exists('username', $credentials) ? 'username' : 'email';
        return User::where($input, $credentials[$input])->first();
    }

    private function validatePassword(LoginRequest $request, $user)
    {
        return Hash::check($request->input('password'), $user->password);
    }

    private function generateToken($user)
    {
        return JWTAuth::fromUser($user);
    }
}
