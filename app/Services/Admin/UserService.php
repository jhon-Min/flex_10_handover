<?php

namespace App\Services\Admin;

use App\Mail\MailType;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Helpers\Helper;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Config;
use App\Validators\UpdateUserValidator;
use App\Notifications\AccountApprovedSuccess;

class UserService
{
    public function lists()
    {
        $data['users'] = User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->orderBy('id', 'DESC')->get();

        return view('user.users', $data);
    }

    public function userDatatable()
    {
        $users = User::query()->whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->orderBy('id', 'DESC');

        return Datatables::of($users)
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $keyword . '%');
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
            ->filterColumn('status', function ($query, $keyword) {
                $query->where(DB::raw('admin_approval_status'), 'like', '%' . $keyword . '%');
            })
            ->addColumn('action', function ($data) {
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
            ->only(['name', 'email', 'company_name', 'mobile', 'state', 'zip', 'status', 'action'])
            ->make(true);
    }

    public function userUpdate($request, $id, $status)
    {
        $data = ['id' => $id, 'status' => $status,];
        // 'account_code' => $request->account_code];
        try {
            $validatedData = UpdateUserValidator::validate($data);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => '0', 'message' => $e->getMessage()], 401);
        }

        $user = User::find($id);
        $user->admin_approval_status = $status;
        $user->account_code = isset($validatedData['account_code']) ? $validatedData['account_code'] : null;
        $updated = $user->update();


        if ($updated) {
            if ($status == 2) {
                $user->notify(new AccountApprovedSuccess()); // error fix
            } else {
                $mail_attributes = [
                    // 'mail_template' => "emails.user_account_notification",
                    'mail_to_email' => $user->email,
                    'mail_to_name' => $user->name,
                    // 'mail_subject' => "Flexible Drive : Account Update!",
                ];
                Helper::sendEmail($mail_attributes, MailType::UserNotification); // error fix
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

    public function sofDel($request)
    {
        $isActive = $request->input('is_active');
        $isActiveString = "inactivated";
        if (!empty($isActive)) {
            $isActiveString = "activated";
        }
        $id = $request->input('userId');

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
