<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\GeneralSetting\Entities\GeneralSetting;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Sidebar;

class AddHomepageSeoMetaToGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->string('meta_site_title')->nullable()->after('guest_checkout');
                $table->string('meta_tags')->nullable()->after('meta_site_title');
                $table->longText('meta_description')->nullable()->after('meta_tags');
            });
            GeneralSetting::first()->update([
                'meta_site_title' => 'Amaz cart',
                'meta_tags' => 'amazcart,amazcart cms,ecommerce',
                'meta_description' => 'We are an industry-leading company that values honesty, integrity, and efficiency. Building quality products and caring for the users are what made us stand out since the beginning.'
            ]);
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                //configuration
                ['id' => 699, 'module_id' => 18, 'parent_id' => 329, 'name' => 'Homepage SEO Setup', 'route' => 'generalsetting.seo-setup', 'type' => 2 ],
                ['id' => 700, 'module_id' => 18, 'parent_id' => 699, 'name' => 'Update', 'route' => 'generalsetting.seo-setup-update', 'type' => 3 ]
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

            }
        }

        $sidebar_sql = [
            ['sidebar_id' => 197, 'module_id' => 16, 'parent_id' => 75,'position' => 7777, 'name' => 'Homepage SEO Setup', 'route' => 'generalsetting.seo-setup', 'type' => 2]
        ];

        try{
            $users =  User::whereHas('role', function($query){
                $query->where('type', 'superadmin')->orWhere('type', 'admin')->orWhere('type', 'staff')->orWhere('type', 'seller');
            })->pluck('id');
            foreach ($users as $key=> $user)
            {
                $user_array[$key] = ['user_id' => $user];
                foreach ($sidebar_sql as $row)
                {
                    $final_row = array_merge($user_array[$key],$row);
                    Sidebar::insert($final_row);
                }
            }
        }catch(Exception $e){

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('meta_site_title');
            $table->dropColumn('meta_tags');
            $table->dropColumn('meta_description');
        });
        Permission::destroy([699,700]);
        $ids = Sidebar::where('sidebar_id', 197)->pluck('id')->toArray();
        Sidebar::destroy($ids);
    }
}
