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
        Schema::create('request_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onUpdate('cascade')->onDelete('cascade'); // 外部キーとして従業員ID
            $table->date('requested_date'); // リクエストされた日付
            $table->time('new_clock_in')->nullable(); // 新しい出勤時間
            $table->time('new_clock_out')->nullable(); // 新しい退勤時間
            $table->boolean('is_requested_day_off'); //0=出勤、１＝休み
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
        Schema::dropIfExists('request_shifts');
    }
};
