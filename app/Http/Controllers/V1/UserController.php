<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query("per_page") ?? 10;

        $users = User::paginate($per_page);

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }
}
