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
                    Schema::table('users', function (Blueprint $table) {
                        $table->foreignId('area_id')->nullable()->after('id');
                        $table->foreignId('cargo_id')->nullable()->after('area_id');
                        $table->boolean('activo')->default(true)->after('password');
                    });
                }

                public function down(): void
                {
                    Schema::table('users', function (Blueprint $table) {
                        $table->dropColumn(['area_id', 'cargo_id', 'activo']);
                    });
                }

};
