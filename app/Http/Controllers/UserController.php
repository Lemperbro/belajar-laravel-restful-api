<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if(User::where('username', $data['username'])->count() > 0){
            //data sudah ada di dalam database
            throw new HttpResponseException(response([
                'errors' => [
                    'username' => [
                        'username already registered'
                    ]
                ]
            ], 400));
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }
}
