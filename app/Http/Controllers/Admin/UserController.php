<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeActivationUserRequest;
use App\Services\Admin\UserService;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware(['auth', 'admin']);
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->lists();
    }

    public function getUserDatatable()
    {
        return $this->userService->userDatatable();
    }

    public function update(Request $request, $id, $status)
    {
        $data = ['id' => $id, 'status' => $status, 'account_code' => $request->query('account_code')];
        return $this->userService->userUpdate($request, $id, $status);
    }

    public function softDelete(ChangeActivationUserRequest $request)
    {
        return $this->userService->sofDel($request);
    }
}
