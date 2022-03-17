<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\ModuleManager\Entities\InfixModuleManager;
use Modules\ModuleManager\Entities\Module;
use Illuminate\Support\Facades\Storage;

class AddMultivendorToModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $multiVendor = Module::where('name', 'MultiVendor')->first();
        if(!$multiVendor){
            $totalCount = \Illuminate\Support\Facades\DB::table('modules')->count();
            $newModule = new \Modules\ModuleManager\Entities\Module();
            $newModule->name = 'MultiVendor';
            $newModule->status = 0;
            $newModule->order = $totalCount;
            $newModule->save();
        }

        $vendor = Storage::exists('.vendor')?Storage::get('.vendor'):'single';
        $infix_module = InfixModuleManager::where('name', 'MultiVendor')->first();
        if(strtolower($vendor) == 'single' && !$infix_module){
            InfixModuleManager::create([
                'name' => 'MultiVendor',
                'email' => 'support@spondonit.com'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
