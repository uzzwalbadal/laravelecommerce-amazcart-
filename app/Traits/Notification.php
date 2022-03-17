<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\GeneralSetting\Entities\NotificationSetting;
use Modules\GeneralSetting\Entities\UserNotificationSetting;
use Modules\GeneralSetting\Services\UserNotificationSettingService;
use Modules\Leave\Entities\ApplyLeave;
use Modules\OrderManage\Entities\CustomerNotification;
use Modules\RolePermission\Entities\Permission;
use Modules\Setting\Model\SmsGateway;
use Str;

trait Notification
{
    use SendMail, SendSMS;

    public $notificationUrl = "#";
    public $adminNotificationUrl = '#';
    public $routeCheck = '';
    public $typeId;

    public function notificationSend($event, $userId)
    {
        try {
            //getting notification setting according to event or delivery_process_id
            $notificationSetting = NotificationSetting::where('event', $event)->orWhere('delivery_process_id', $event)->first();

            // if the notification exist take user notification setting for that notification setting
            if ($notificationSetting) {

                $user = User::findOrFail($userId);

                // for registration
                if ($notificationSetting->user_access_status == 0 && $notificationSetting->seller_access_status == 0 && $notificationSetting->admin_access_status == 1 && $notificationSetting->staff_access_status == 1) {
                    $this->checkNotificationSettingAndSend($user, $notificationSetting);
                }
                $userNotificationSetting = UserNotificationSetting::where('notification_setting_id', $notificationSetting->id)->where('user_id', $userId)->first();

                if ($userNotificationSetting) {
                    if($user->role_id != 1){
                        $this->checkUserNotificationSettingAndSend($user, $notificationSetting, $userNotificationSetting);
                    }
                }
                if($event == 'Sub Seller Created'){
                    $this->sendNotificationForSeller($notificationSetting, $user->sub_seller->user_id);
                }else{
                    // send notification to super admin
                    $this->sendNotificationToSuperAdmin($notificationSetting);
                    //send notificatuion permission wise
                    $this->sendNotificationToPermissionWise($notificationSetting);
                }
            }
        } catch (\Exception $th) {
            
        }
    }

    public function sendNotificationMail($user, $notificationSetting)
    {
        $this->sendNotificationByMail($this->typeId, $user, $notificationSetting);
    }

    public function createSystemNotification($user, $notificationSetting)
    {
        CustomerNotification::create([
            'title' => $notificationSetting,
            'customer_id' => $user->id,
            'url' => $this->notificationUrl
        ]);
    }

    public function checkNotificationSettingAndSend($user, $notificationSetting)
    {
        if (Str::contains($notificationSetting->type, 'sms')) {
            if($user->phone != null){
                $this->sendSMS($user->phone, $notificationSetting->message);
            }
        }
        if (Str::contains($notificationSetting->type, 'email')) {
            if($user->email != null){
                $this->sendNotificationMail($user, $notificationSetting);
            }
        }
        if (Str::contains($notificationSetting->type, 'mobile')) {
            // Mobile push notification
        }
        if (Str::contains($notificationSetting->type, 'system')) {
            $this->createSystemNotification($user, $notificationSetting->message);
        }
    }

    public function checkUserNotificationSettingAndSend($user, $notificationSetting, $userNotificationSetting)
    {
        if (Str::contains($notificationSetting->type, 'sms') && Str::contains($userNotificationSetting->type, 'sms')) {
            if($user->phone != null){
                $this->sendSMS($user->phone, $notificationSetting->message);
            }
        }
        if (Str::contains($notificationSetting->type, 'email') && Str::contains($userNotificationSetting->type, 'email')) {
            if($user->email != null){
                $this->sendNotificationMail($user, $notificationSetting);
            }
        }
        if (Str::contains($notificationSetting->type, 'mobile') && Str::contains($userNotificationSetting->type, 'mobile')) {
            // Mobile push notification
        }
        if (Str::contains($notificationSetting->type, 'system') && Str::contains($userNotificationSetting->type, 'system')) {
            $this->createSystemNotification($user, $notificationSetting->message);
        }
    }

    public function isEnableEmail()
    {
        $email = app('business_settings')->where('type', 'email_verification')->where('status', 1)->first();
        if ($email)
            return true;
        return false;
    }

    public function isEnableSMS()
    {
        $email = app('business_settings')->where('type', 'sms_verification')->where('status', 1)->first();
        if ($email)
            return true;
        return false;
    }

    public function isEnableSystem()
    {
        $email = app('business_settings')->where('type', 'system_notification')->where('status', 1)->first();
        if ($email)
            return true;
        return false;
    }


    public function sendNotification($type, $email, $subject, $content, $number, $message)
    {
        if ($this->isEnableEmail()) {
            $this->sendMail($email, $subject, $content);
        }
        if ($this->isEnableSMS()) {
            $this->sendSMS($number, $message);
        }
        if ($this->isEnableSystem()) {
            $class = get_class($type);
            $explode = explode('\\', $class);
            if (end($explode) == 'Sale') {
                $url = 'sale.show';
            }
            if (end($explode) == 'PurchaseOrder') {
                $url = 'purchase_order.show';
            }
            if (end($explode) == 'Voucher') {
                $url = 'vouchers.show';
            }
            if (end($explode) == 'Payroll') {
                $url = 'staffs.show';
            }
            if (end($explode) == 'Staff') {
                $url = 'staffs.show';
            }
            if (end($explode) == 'ApplyLeave') {
                $url = 'staffs.show';
            }
            \Modules\Notification\Entities\Notification::create([
                'type' => end($explode),
                'data' => $message,
                'url' => $url,
                'notifiable_id' => $type->id,
                'notifiable_type' => $class,
            ]);
        }
        return true;
    }

    public function sendNotificationToSuperAdmin($notificationSetting){
        $superadmins = User::where('is_active', 1)->whereHas('role', function($query){
            return $query->where('type', 'superadmin');
        })->get();
        foreach($superadmins as $admin){
            $userNotificationSetting = UserNotificationSetting::where('notification_setting_id', $notificationSetting->id)->where('user_id', $admin->id)->first();
            $this->notificationUrl = $this->adminNotificationUrl;
            if($admin && $notificationSetting && $userNotificationSetting){
                $this->checkUserNotificationSettingAndSendForSuperAdmin($notificationSetting, $userNotificationSetting, $admin);
            }
        }
    }
    public function checkUserNotificationSettingAndSendForSuperAdmin($notificationSetting, $userNotificationSetting, $user){

        if (Str::contains($notificationSetting->type, 'sms') && Str::contains($userNotificationSetting->type, 'sms')) {
            if($user->phone != null){
                $this->sendSMS($user->phone, $notificationSetting->admin_msg);
            }
        }
        if (Str::contains($notificationSetting->type, 'email') && Str::contains($userNotificationSetting->type, 'email')) {
            if($user->email != null){
                $this->sendNotificationMail($user, $notificationSetting);
            }
        }
        if (Str::contains($notificationSetting->type, 'mobile') && Str::contains($userNotificationSetting->type, 'mobile')) {
            // Mobile push notification
        }
        if (Str::contains($notificationSetting->type, 'system') && Str::contains($userNotificationSetting->type, 'system')) {
            $this->createSystemNotification($user, $notificationSetting->admin_msg);
        }
    }

    // for send Notification to permission wise
    public function sendNotificationToPermissionWise($notificationSetting){
        $users = User::where('is_active', 1)->whereHas('role', function($query){
            return $query->where('type', 'admin')->orWhere('type', 'staff');
        })->get();

        foreach($users as $user){
            $has_permission = false;
            $permission = Permission::where('route', $this->routeCheck)->first();
            if($permission){
                $access = DB::table('role_permission')->where('role_id', $user->role_id)->where('permission_id', $permission->id)->first();
                if($access){
                    $has_permission = true;
                }
            }
            if($has_permission){
                $userNotificationSetting = UserNotificationSetting::where('notification_setting_id', $notificationSetting->id)->where('user_id', $user->id)->first();
                $this->notificationUrl = $this->adminNotificationUrl;
                if($user && $notificationSetting && $userNotificationSetting){
                    $this->checkUserNotificationSettingAndSendForPermissionWise($notificationSetting, $userNotificationSetting, $user);
                }
            }
        }
    }

    public function checkUserNotificationSettingAndSendForPermissionWise($notificationSetting, $userNotificationSetting, $user){

        if (Str::contains($notificationSetting->type, 'sms') && Str::contains($userNotificationSetting->type, 'sms')) {
            if($user->phone != null){
                $this->sendSMS($user->phone, $notificationSetting->admin_msg);
            }
        }
        if (Str::contains($notificationSetting->type, 'email') && Str::contains($userNotificationSetting->type, 'email')) {
            if($user->email != null){
                $this->sendNotificationMail($user, $notificationSetting);
            }
        }
        if (Str::contains($notificationSetting->type, 'mobile') && Str::contains($userNotificationSetting->type, 'mobile')) {
            // Mobile push notification
        }
        if (Str::contains($notificationSetting->type, 'system') && Str::contains($userNotificationSetting->type, 'system')) {
            $this->createSystemNotification($user, $notificationSetting->admin_msg);
        }
    }

    // for send Notification to seller
    public function sendNotificationForSeller($notificationSetting, $seller_id){
        $user = User::find($seller_id);

        if($user){
            $has_permission = false;
            $permission = Permission::where('route', $this->routeCheck)->first();
            if($permission){
                $access = DB::table('role_permission')->where('role_id', $user->role_id)->where('permission_id', $permission->id)->first();
                if($access){
                    $has_permission = true;
                }
            }
            if($has_permission){
                $userNotificationSetting = UserNotificationSetting::where('notification_setting_id', $notificationSetting->id)->where('user_id', $user->id)->first();
                $this->notificationUrl = $this->adminNotificationUrl;
                if($user && $notificationSetting && $userNotificationSetting){
                    $this->checkUserNotificationSettingAndSendForPermissionWise($notificationSetting, $userNotificationSetting, $user);
                }
            }
        }
    }
}
