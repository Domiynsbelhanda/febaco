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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained('athletes')->cascadeOnDelete();
            $table->foreignId('from_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('to_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('initiated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('transfer_date')->default(now());
            $table->string('type'); // prêt, définitif
            $table->enum('status', ['en_attente', 'accepté_par_recepteur', 'validé', 'refusé'])->default('en_attente');

            $table->boolean('confirmation_by_destination')->default(false);
            $table->boolean('confirmation_by_federation')->default(false);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
