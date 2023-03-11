<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectToUserprojectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userprojects', function (Blueprint $table) {
            $table->string('is_active', 100)->nullable()->after('project_id');
            $table->string('permission', 100)->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userprojects', function (Blueprint $table) {
            $table->string('is_active');
            $table->string('permission');
        });
    }
}
