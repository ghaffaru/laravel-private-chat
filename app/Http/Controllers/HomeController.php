<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function friends()
    {
        return UserResource::collection(User::where('id', '!=', auth()->id())->get());
    }
}
