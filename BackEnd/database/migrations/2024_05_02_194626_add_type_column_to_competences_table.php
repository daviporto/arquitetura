<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->enum(
                'type', [
                'language',
                'framework',
                'programmingLanguage',
                'technology',
                'other'
            ])->default('other');
        });
    }

    public function down(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
