<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\ModuleManager\Entities\Module;

class AddSslCommerzModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ssl_c = Module::where('name', 'SslCommerz')->orWhere('name', 'Sslcommerz')->pluck('id');
        Module::destroy($ssl_c);
        $totalCount = \Illuminate\Support\Facades\DB::table('modules')->count();
        $newModule = new Module();
        $newModule->name = 'SslCommerz';
        $newModule->status = 0;
        $newModule->order = $totalCount;
        $newModule->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
