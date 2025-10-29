<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite may not have the named index, so check and drop only when present.
        if (DB::getDriverName() === 'sqlite') {
            $index = DB::select("SELECT name FROM sqlite_master WHERE type = 'index' AND name = ?", ['reports_organization_unique']);
            if (!empty($index)) {
                DB::statement('DROP INDEX "reports_organization_unique"');
            }
        } else {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropUnique('reports_organization_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unique(['organization_id'], 'reports_organization_unique');
        });
    }
};
