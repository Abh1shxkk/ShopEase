<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add referral fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 10)->unique()->nullable()->after('role');
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            $table->decimal('reward_points', 10, 2)->default(0)->after('referred_by');
            $table->decimal('total_earned_points', 10, 2)->default(0)->after('reward_points');
            $table->decimal('total_redeemed_points', 10, 2)->default(0)->after('total_earned_points');
        });

        // Referrals tracking table
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->decimal('referrer_reward', 10, 2)->default(0);
            $table->decimal('referred_reward', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Reward points transactions
        Schema::create('reward_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted']);
            $table->decimal('points', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('source')->nullable(); // referral, order, signup, admin, redemption
            $table->morphs('sourceable'); // polymorphic relation
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Referral settings
        Schema::create('referral_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_settings');
        Schema::dropIfExists('reward_transactions');
        Schema::dropIfExists('referrals');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'reward_points', 'total_earned_points', 'total_redeemed_points']);
        });
    }
};
