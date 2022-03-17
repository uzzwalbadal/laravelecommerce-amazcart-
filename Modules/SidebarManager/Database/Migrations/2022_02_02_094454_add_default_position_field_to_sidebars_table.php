<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Modules\ModuleManager\Entities\InfixModuleManager;
use Modules\SidebarManager\Entities\Sidebar;
class AddDefaultPositionFieldToSidebarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('sidebars')){
            if(!Schema::hasColumn('sidebars', 'default_position')){
                Schema::table('sidebars', function (Blueprint $table) {
                    $table->unsignedInteger('default_position')->default(0)->nullable()->after('position');
                });
            }

            
            $default_position = include base_path('config/sidebar.php');
            foreach($default_position as $key => $sidebar){
                Sidebar::where('route', $key)->update([
                    'default_position' => $sidebar
                ]);
            }
        }

        if(Schema::hasTable('infix_module_managers')){
            $modules = InfixModuleManager::whereIn('name', ['FormBuilder', 'PageBuilder'])->get();
            foreach($modules as $module){
                $module->purchase_code = \Str::uuid();
                $module->checksum = \Str::uuid();
                $module->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('sidebars')){
            Schema::table('sidebars', function (Blueprint $table) {
                $table->dropColumn('default_position');
            });
        }
    }
}
