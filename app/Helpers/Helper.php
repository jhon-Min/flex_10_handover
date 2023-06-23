<?php

namespace App\Helpers;

use App\Mail\MailType;
use Illuminate\Support\Facades\Storage;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class Helper
{

    public static function sendEmail($attributes,int $type)
    {
        //return View::make('emails.order_cancel', ['order' => $order, 'user' => $user]);
        Notification::route('mail', $attributes['mail_to_email'])
            ->route('nexmo', '5555555555')
            ->notify(new EmailNotification($attributes,$type));
        return true;
    }

    public static function fileUpload($attributes)
    {
        $file = $attributes['file'];
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = preg_replace('/\s+/', '_', $filename . '_' . time() . '.' . $extension);
        Storage::disk('public')->putFileAs($attributes['path'], $file, $fileNameToStore);
        if (isset($attributes['old_file']) && !empty($attributes['old_file'])) {
            $store_path = $attributes['path'] . $attributes['old_file'];
            Storage::disk('public')->delete($store_path);
        }
        return $fileNameToStore;
    }

    public static function truncateTable($table)
    {

        $sql = "TRUNCATE TABLE " . $table;

        DB::statement($sql);

        return true;
    }
}
