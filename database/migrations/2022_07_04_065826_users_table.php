<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users', function (Blueprint $table) {
                // primary key 
                $table->id();

                // unique user_id if this one or may omit this column
                $table->string('user_id')->unique();
                $table->string('name');
                $table->string('description')->nullable();
                $table->unsignedTinyInteger('age');

                // create columns: "created_at" and "updated_at"
                $table->timestamps(); 
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
