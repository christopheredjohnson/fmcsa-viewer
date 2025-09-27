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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('dot_number')->unique();
            $table->string('legal_name');
            $table->string('dba_name')->nullable();
            $table->string('business_org_desc')->nullable();
            $table->string('status_code')->nullable();
            $table->date('add_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('email_address')->nullable();

            // Safety & compliance
            $table->string('safety_rating')->nullable();
            $table->date('safety_rating_date')->nullable();
            $table->string('review_type')->nullable();
            $table->date('review_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
