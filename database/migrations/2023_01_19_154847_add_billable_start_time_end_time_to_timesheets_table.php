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
        Schema::table('timesheets', function (Blueprint $table) {
            if (!Schema::hasColumn('timesheets', 'billable')) {
                $table->char('billable', 10)->default('Yes')->after('remark');
            }
            if (!Schema::hasColumn('timesheets', 'start_time')) {
                $table->time('start_time')->after('date');
            }
            if (!Schema::hasColumn('timesheets', 'end_time')) {
                $table->time('end_time')->after('start_time');
            }
            if (!Schema::hasColumn('timesheets', 'total_mins')) {
                $table->integer('total_mins')->after('billable');
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
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn('billable');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('total_mins');
        });
    }
};
