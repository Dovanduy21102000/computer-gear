<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create archives directories if they don't exist
        Storage::disk('public')->makeDirectory('archives/products');
        Storage::disk('public')->makeDirectory('archives/variants');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally remove the directories
        Storage::disk('public')->deleteDirectory('archives');
    }
};
