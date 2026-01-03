<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->boolean('email_notifications')->default(true)->after('last_login_at');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            $table->boolean('marketing_emails')->default(false)->after('sms_notifications');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'avatar', 'date_of_birth', 'gender',
                'last_login_at', 'email_notifications', 'sms_notifications', 'marketing_emails'
            ]);
        });
    }
};
