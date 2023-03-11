<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('priority');
            $table->text('description');
            $table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('due_date')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->integer('assign_to');
            $table->integer('project_id');
            $table->integer('milestone_id')->nullable();
            $table->string('status')->default('todo');
            $table->integer('stage')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
