<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\AccountApprovedSuccess;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $data['users'] = User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->orderBy('id', 'DESC')->get();

        return view('user.users', $data);
    }

    public function getUserDatatable()
    {

        $users = User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->orderBy('id', 'DESC')->get();

        return Datatables::of($users)
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->editColumn('email', function ($data) {
                return $data->email;
            })
            ->editColumn('company_name', function ($data) {
                return $data->company_name;
            })
            ->editColumn('mobile', function ($data) {
                return $data->mobile;
            })
            ->editColumn('state', function ($data) {
                return $data->state;
            })
            ->editColumn('zip', function ($data) {
                return $data->zip;
            })
            ->editColumn('status', function ($data) {
                return $data->status();
            })
            ->editColumn('action', function ($data) {
                if ($data->admin_approval_status == 1) {
                    $url_approve = route('user.update', ['id' => $data->id, 'status' => 2]);
                    $url_decline = route('user.update', ['id' => $data->id, 'status' => 3]);

                    return "<a title=\"Approve\" href=\"javascript:void(0)\" onclick=\"account_code_alert('Account','Approved', '" . $url_approve . "')\" class=\"badge badge-success color-white\" id=\"sweetalert_demo_9\"><i class=\"zmdi zmdi-check zmdi-hc-fw\"></i></a>
                    <a title=\"Decline\" href=\"javascript:void(0)\" onclick=\"confirmation_alert('Account','Decline','" . $url_decline . "')\" class=\"badge badge-danger color-white\"><i class=\"zmdi zmdi-close zmdi-hc-fw\"></i></a>";
                } elseif ($data->admin_approval_status == 2) {
                    $url_soft_delete = route('user.soft-delete');
                    if ($data->is_active == 1) {
                        return "<label class=\"switch\"><input checked class=\"userStatusToggel\" type=\"checkbox\" name=\"is_active\" onchange=\"toggleCheckbox(this,'" . $url_soft_delete . "','" . $data->id . "')\" ><div class=\"slider round\"><span class=\"on\">Active</span><span class=\"off\">In Active</span></div></label>";
                    } else {
                        return "<label class=\"switch\"><input class=\"userStatusToggel\" type=\"checkbox\" name=\"is_active\" onchange=\"toggleCheckbox(this,'" . $url_soft_delete . "','" . $data->id . "')\" ><div class=\"slider round\"><span class=\"on\">Active</span><span class=\"off\">In Active</span></div></label>";
                    }
                } else {
                    return "";
                }
            })
            ->rawColumns(['name', 'email', 'company_name', 'mobile', 'state', 'zip', 'status', 'action'])
            ->make(true);
    }

    public function update(Request $request, $id, $status)
    {
        $data = ['id' => $id, 'status' => $status, 'account_code' => $request->query('account_code')];
        $validator = Validator::make($data, [
            'id' => 'required|exists:users,id',
            'status' => 'required|in:1,2,3',
            // 'account_code' => 'required|string'
        ]);


        if ($validator->fails()) {
            return response()->json(['success' => '0', 'message' => $validator->errors()], 401);
        }

        $user = User::find($id);
        $user->admin_approval_status = $status;
        $user->account_code = $request->query('account_code');
        $updated = $user->save();

        if ($updated) {
            if ($status == 2) {
                $user->notify(new AccountApprovedSuccess());
            } else {
                $mail_attributes = [
                    'mail_template' => "emails.user_account_notification",
                    'mail_to_email' => $user->email,
                    'mail_to_name' => $user->name,
                    'mail_subject' => "Flexible Drive : Account Update!",
                ];
                Helper::sendEmail($mail_attributes);
            }

            $badge = Config::get('constant.user_account_status_lables')[$status];
            $response = [
                'success' => '1',
                'message' => 'Account has been ' . Config::get('constant.user_account_status')[$status],
                'badge' => $badge,
                'badge_data' => $id,
                'remove_action' => 'td_action_' . $id
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['success' => '0', 'message' => 'something went wrong!'], 200);
        }
    }

    public function softDelete(Request $request)
    {
        $isActive = $request->input('is_active');
        $isActiveString = "inactivated";
        if (!empty($isActive)) {
            $isActiveString = "activated";
        }
        $id = $request->input('userId');
        $data = ['id' => $id, 'is_active' => $isActive,];
        $validator = Validator::make($data, [
            'id' => 'required|exists:users,id',
            'is_active' => 'required|in:1,0',
        ]);


        if ($validator->fails()) {
            return response()->json(['success' => '0', 'message' => $validator->errors()], 401);
        }

        $user = User::find($id);
        $user->is_active = $isActive;
        $updated = $user->save();

        if ($updated) {
            $response = [
                'success' => '1',
                'message' => 'Account has been ' . $isActiveString,
                'badge' => $isActiveString,
                'badge_data' => $id,
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['success' => '0', 'message' => 'something went wrong!'], 200);
        }
    }
}
