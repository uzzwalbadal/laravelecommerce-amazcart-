<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Sidebar;

class AddNewPermissionToShippingModule extends Migration
{
    public function up()
    {
        if(Schema::hasTable('permissions')){

            $sql = [
                //configuration
                ['id' => 695, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Label Configuration', 'route' => 'shipping.label.terms_condition.index', 'type' => 2 ],
                ['id' => 696, 'module_id' => 44, 'parent_id' => 687, 'name' => 'Invoice', 'route' => 'shipping.invoice_generate', 'type' => 3 ],
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

            }

        }

        $sidebar_sql = [

            ['sidebar_id' => 196, 'module_id' => 41, 'parent_id' => 190,'position' => 4, 'name' => 'Label Configuration', 'route' => 'shipping.label.terms_condition.index', 'type' => 2],
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
        $ids = Permission::where('module_id', 44)->pluck('id')->toArray();
        Permission::destroy($ids);
        $ids = Sidebar::where('module_id', 41)->pluck('id')->toArray();
        Sidebar::destroy($ids);
    }
}
