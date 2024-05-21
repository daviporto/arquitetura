<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description');
            $table->boolean('is_available');
            $table->unsignedInteger('applications_amount');
            $table->unsignedInteger('salary')->nullable();
            $table->enum('salary_time_unit', ['hour', 'week', 'day', 'month'])->nullable();
            $table->dateTime('accept_application_until')->nullable();
            $table->enum('work_model', ['hybrid', 'onSite', 'homeOffice'])->nullable();
            $table->enum('employment_type', ['full-time', 'part-time'])->nullable();
            $table->unsignedMediumInteger('week_workload')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('company_id')->constrained('companies');

            $table->timestamps();

            $table->softDeletes();

            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
