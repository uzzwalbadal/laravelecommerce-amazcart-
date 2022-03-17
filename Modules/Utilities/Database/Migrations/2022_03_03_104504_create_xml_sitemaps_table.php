<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateXmlSitemapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xml_sitemaps', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        if(Schema::hasTable('xml_sitemaps')){
            $sql = [
                ['type' => 'all','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'pages','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'products','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'brands','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'tags','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'flash_deal','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'new_user_zone','status' => 1,'created_at' => now(),'updated_at' => now()],
                ['type' => 'blogs','status' => 1,'created_at' => now(),'updated_at' => now()]
            ];
            DB::table('xml_sitemaps')->insert($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xml_sitemaps');
    }
}
