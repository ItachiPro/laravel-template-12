<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $per_page = $request->query("per_page") ?? 10;

        $users = User::paginate($per_page);

        return $this->success($users, "OK", 200);
    }
}
