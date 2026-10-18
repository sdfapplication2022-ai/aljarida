<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (! Schema::hasColumn('users', 'role'))  { $t->string('role')->default('user'); }
            if (! Schema::hasColumn('users', 'bio'))   { $t->text('bio')->nullable(); }
            if (! Schema::hasColumn('users', 'photo')) { $t->string('photo')->nullable(); }
        });
    }

    public function down(): void
    {
        // volontairement vide : les colonnes peuvent venir d'une autre migration
    }
};
