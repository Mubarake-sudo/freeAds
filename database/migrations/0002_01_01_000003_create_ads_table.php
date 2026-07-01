<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crée la table ads (annonces) avec tous les champs requis par le cahier des charges :
     * - title, category, description, photo, price, location
     * - condition (état du produit : new, good, used)
     * - user_id (clé étrangère vers users)
     */
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('location');
            $table->enum('condition', ['new', 'good', 'used'])->default('good');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
