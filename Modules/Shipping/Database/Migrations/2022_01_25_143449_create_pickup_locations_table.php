<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePickupLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_locations', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_location')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_2')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->integer('pin_code')->nullable();
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_set')->default(0);
            $table->boolean('is_default')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        $setting = DB::table('general_settings')->first();
        $sql = [
            'pickup_location' => 'Pickup Location 1',
            'name' => $setting->company_name,
            'email' => $setting->email,
            'phone' => $setting->phone,
            'address' => $setting->address,
            'country_id' => $setting->country_id,
            'state_id' => $setting->state_id,
            'city_id' => $setting->city_id,
            'pin_code' => $setting->zip_code,
            'is_set' => 1,
            'status' => 1,
            'created_by' => 1,
            'is_default' => 1
        ];
        DB::table('pickup_locations')->insert([$sql]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup_locations');
    }
}
