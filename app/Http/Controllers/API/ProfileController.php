<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\User\ProfileResource;
use App\Models\Profiles;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = Profiles::with('user')->where('user_id', $user->id)->first();
        return response()->json([
            'message' => 'Tampil Data Profile',
            'data' => $profile
        ]);
    }

    public function store(ProfileRequest $request)
    {
        $data = $request->validated();
        $currentUser = auth()->user();
        $profileData = Profiles::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'age' => $data['age'],
                'bio' => $data['bio'],
                'user_id' => $currentUser->id,
            ]
        );
        return (new ProfileResource($profileData))->response()->setStatusCode(201);
    }
}
