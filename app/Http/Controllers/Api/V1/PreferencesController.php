<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreferenceResource;
use App\Models\User;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    /**
     * Display preferences of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('currencies')->get();
        return PreferenceResource::collection($users);
    }

    /**
     * Display preferences of a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('currencies')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return new PreferenceResource($user);
    }

    /**
     * Add preferences for a specific user.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        //need fix
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $currencyIds = $request->input('currency_id', []);
        $user->currencies()->syncWithoutDetaching($currencyIds);

        return response()->json(['message' => 'Preferences added successfully']);
    }

    /**
     * Update preferences for a specific user.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $currencyIds = $request->input('currency_id', []);
        $user->currencies()->sync($currencyIds);

        return response()->json(['message' => 'Preferences updated successfully']);
    }

    /**
     * Remove preferences for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->currencies()->detach();

        return response()->json(['message' => 'Preferences deleted successfully']);
    }
}
