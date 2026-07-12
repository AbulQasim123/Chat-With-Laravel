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
            // Optional Profile Fields
            $table->string('phone')->nullable()->after('email');
            $table->string('photo')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('photo');
            $table->date('dob')->nullable()->after('gender');
            $table->string('address')->nullable()->after('dob');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('zip_code')->nullable()->after('country');

            // Status
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('email_verified_at');


            // Security
            $table->string('two_factor_code')->nullable()->after('remember_token');
            $table->dateTime('two_factor_expires_at')->nullable()->after('two_factor_code');

            // Multi-Tenancy
            $table->unsignedBigInteger('tenant_id')->nullable()->after('two_factor_expires_at');

            // Audit & Login Tracking
            $table->ipAddress('last_login_ip')->nullable()->after('tenant_id');
            $table->timestamp('last_login_at')->nullable()->after('last_login_ip');

            // API Token
            // $table->rememberToken();
            $table->text('api_token')->nullable()->after('last_login_at');
            $table->json('settings')->nullable();
            $table->softDeletes()->after('settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'photo',
                'gender',
                'dob',
                'address',
                'city',
                'state',
                'country',
                'zip_code',
                'status',
                'two_factor_code',
                'two_factor_expires_at',
                'tenant_id',
                'last_login_ip',
                'last_login_at',
                'api_token',
                'settings',

            ]);
            $table->dropSoftDeletes();
            // $table->dropRememberToken();
        });
    }
};
