<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_vieo_files', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreignUuid('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('group_videos')->onDelete('cascade');
            $table->string('videos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_vieo_files');
    }
};
