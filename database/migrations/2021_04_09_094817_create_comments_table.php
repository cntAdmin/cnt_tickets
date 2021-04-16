<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->text('description')->nullable()->default('text');
            $table->string('_token', 36)->default('token');
            $table->unsignedSmallInteger('expires_in')->default(3600);
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnUpdate()->onDelete('no action');
            $table->softDeletes();
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
        Schema::dropIfExists('comments');
    }
}
