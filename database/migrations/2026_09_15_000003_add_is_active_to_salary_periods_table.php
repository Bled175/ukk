<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_periods', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('ends_at');
        });

        $latestId = DB::table('salary_periods')->orderByDesc('starts_at')->orderByDesc('id')->value('id');

        if ($latestId) {
            DB::table('salary_periods')->where('id', '!=', $latestId)->update(['is_active' => false]);
        }
    }

    public function down(): void
    {
        Schema::table('salary_periods', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
