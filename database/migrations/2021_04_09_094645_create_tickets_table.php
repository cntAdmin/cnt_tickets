<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('agent_id')->nullable()->constrained('users')->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('department_type_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('priority_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('origin_type_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('ticket_type_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            $table->foreignId('ticket_status_id')->nullable()->constrained()->cascadeOnUpdate()->onDelete('no action');
            
            $table->string('custom_id', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('read_by_admin')->default(true);
            
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnUpdate()->onDelete('no action');
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->onDelete('no action');
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
        Schema::dropIfExists('tickets');
    }
}
