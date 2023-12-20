<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    public function up(){
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cp_number');
            $table->text('address');
            $table->tinyInteger('status')->default(1); 
            $table->string('type')->default('user'); 
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
}
