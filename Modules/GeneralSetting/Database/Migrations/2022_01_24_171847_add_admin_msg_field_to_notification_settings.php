<?php

use App\Models\User;
use App\Traits\GenerateSlug;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\GeneralSetting\Entities\NotificationSetting;
use Modules\GeneralSetting\Entities\UserNotificationSetting;
use Modules\OrderManage\Entities\CustomerNotification;
use Modules\OrderManage\Entities\DeliveryProcess;

class AddAdminMsgFieldToNotificationSettings extends Migration
{
    use GenerateSlug;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('notification_settings')){
            Schema::table('notification_settings', function (Blueprint $table) {
                $table->longText('admin_msg')->nullable()->after('message');
            });

            $settings = NotificationSetting::all();
            foreach($settings as $setting){
                if($setting->event == 'Register' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'A customer has been registered.';
                    $setting->admin_access_status = 1;
                    $setting->staff_access_status = 1;
                    $setting->save();
                }
                elseif($setting->event == 'Offline recharge' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'Offline recharge done to user.';
                    $setting->save();
                }
                elseif($setting->event == 'Withdraw request declined' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'A withdraw request has been declined.';
                    $setting->user_access_status = 0;
                    $setting->save();
                }
                elseif($setting->event == 'Withdraw request approve' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'A withdraw request has been approved.';
                    $setting->user_access_status = 0;
                    $setting->save();
                }
                elseif($setting->event == 'Order confirmation' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'An order has been approved.';
                    $setting->save();
                }
                elseif($setting->event == 'Product review' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'A product reviewed by customer.';
                    $setting->admin_access_status = 1;
                    $setting->staff_access_status = 1;
                    $setting->save();
                }
                elseif($setting->event == 'Product disable' && $setting->delivery_process_id == null){
                    $setting->admin_msg = 'A product has been disabled.';
                    $setting->admin_access_status = 1;
                    $setting->staff_access_status = 1;
                    $setting->save();
                }
                elseif($setting->delivery_process_id != null){
                    $process = DeliveryProcess::find($setting->delivery_process_id);
                    if($process){
                        $setting->admin_msg = 'An order process change to '. $process->name;
                        $setting->save();
                    }  
                }
            }
        }

        if(Schema::hasTable('customer_notifications')){
            CustomerNotification::query()->truncate();
        }

        if(Schema::hasTable('user_notification_settings')){
            $users = User::whereHas('role', function($query){
                return $query->where('type', 'superadmin')->orWhere('type', 'admin')->orWhere('type', 'staff');
            })->get();

            foreach($users as $user){
                UserNotificationSetting::create([
                    'user_id' => $user->id,
                    'notification_setting_id' => 1,
                    'type' => 'system' 
                ]);
            }
        }
        if(Schema::hasTable('users')){
            $admin = User::whereHas('role', function($q){
                return $q->where('type', 'superadmin');
            })->first();
            $setting = DB::table('general_settings')->first();

            $admin->slug = $this->productSlug($setting->site_title);
            $admin->save();
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropColumn('admin_msg');
        });
    }
}
