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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('matricule')->nullable();
            $table->string('province')->nullable();
            $table->string('categorie')->nullable();
            $table->string('ville')->nullable();
            $table->string('division')->nullable();
            $table->string('version')->nullable();
            $table->string('casier_no')->nullable();
            $table->string('bp')->nullable();
            $table->string('couleurs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'matricule',
                'province',
                'categorie',
                'ville',
                'division',
                'version',
                'casier_no',
                'bp',
                'couleurs',
            ]);
        });
    }
};
