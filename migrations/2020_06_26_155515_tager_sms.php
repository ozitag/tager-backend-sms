<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tager_sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template');
            $table->string('name');
            $table->text('body')->nullable();
            $table->string('recipients')->nullable();
            $table->boolean('changed_by_admin')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tager_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('recipient');
            $table->string('body');
            $table->string('status');
            $table->boolean('debug')->default(false);
            $table->text('error')->nullable();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('tager_sms_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tager_sms_logs');
        Schema::dropIfExists('tager_sms_templates');
    }
}
