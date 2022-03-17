<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersTable extends Migration
{

    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('type')->default('Manual');
            $table->string('slug')->nullable();
            $table->string('tracking_url')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        DB::table('carriers')->insert([
            [
                'name'=>'Manual',
                'status'=>1,
                'type'=>'Manual',
                'slug'=>'Manual',
            ]
        ]);
    }


    public function down()
    {
        Schema::dropIfExists('carriers');
    }
}
