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
        Schema::table('exams', function (Blueprint $table) {
            // Security Settings
            $table->string('access_code', 20)->nullable()->after('status');
            $table->boolean('require_access_code')->default(false)->after('access_code');
            $table->boolean('restrict_to_class')->default(true)->after('require_access_code');
            $table->boolean('detect_cheating')->default(false)->after('restrict_to_class');
            $table->boolean('detect_tab_switch')->default(false)->after('detect_cheating');
            $table->boolean('detect_device_change')->default(false)->after('detect_tab_switch');
            $table->boolean('lock_on_exit')->default(false)->after('detect_device_change');
            $table->integer('max_exit_time')->nullable()->comment('Seconds allowed before locking')->after('lock_on_exit');
            $table->boolean('require_camera')->default(false)->after('max_exit_time');
            $table->boolean('require_screen_recording')->default(false)->after('require_camera');
            
            // Auto-generation tracking
            $table->boolean('is_auto_generated')->default(false)->after('require_screen_recording');
            $table->json('auto_gen_criteria')->nullable()->comment('Store difficulty distribution, topics, etc')->after('is_auto_generated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn([
                'access_code',
                'require_access_code',
                'restrict_to_class',
                'detect_cheating',
                'detect_tab_switch',
                'detect_device_change',
                'lock_on_exit',
                'max_exit_time',
                'require_camera',
                'require_screen_recording',
                'is_auto_generated',
                'auto_gen_criteria',
            ]);
        });
    }
};
