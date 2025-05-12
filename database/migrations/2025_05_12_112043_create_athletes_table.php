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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('last_name');     // Nom
            $table->string('middle_name');   // Postnom
            $table->string('first_name');    // Prénom
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('gender', ['Masculin', 'Féminin']);
            $table->string('matricule')->unique();
            $table->string('photo')->nullable();
            $table->integer('height')->nullable(); // en cm
            $table->integer('weight')->nullable(); // en kg
            $table->string('position')->nullable(); // poste joué
            $table->integer('jersey_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
