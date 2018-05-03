<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{

    private $table;

    /**
     * CreatePackageTable constructor.
     */
    public function __construct()
    {
        $this->table = 'devices';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('uuid')->unique();
                $table->morphs('owner');

                $table->text("push_code")->required();

                $table->enum('device_os', \Newestapps\Push\Enum\OS::toArray());
                $table->string( "device_os_version")->required();

                $table->integer('app_version')->required();

                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists($this->table);
    }
}
