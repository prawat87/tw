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
        Schema::table('task_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('task_groups', 'project_id')) {
                $table->integer('project_id')->default(0)->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_groups', function (Blueprint $table) {
            //
        });
    }
};
