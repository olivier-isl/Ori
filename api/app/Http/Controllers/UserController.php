<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Use User Model
use App\Models\User;

//Use Resources to convert into json
use App\Http\Resources\UserResource as UserResource;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return [
            'data' => UserResource::collection($users),
            'response' => 200
        ];
    }
    public function userByName($name)
    {
        $user = User::select('*')->where('name', $name)->get();
        return [
            'data' => UserResource::collection($user),
            'response' => 200
        ];
    }
}
