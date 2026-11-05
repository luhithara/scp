```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kodekelas', 50)->nullable()->unique();
            $table->unsignedBigInteger('kelasid')->nullable();

            $table->foreign('kelasid')
                ->references('id')
                ->on('kelas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelasid']);
            $table->dropUnique(['kodekelas']);
            $table->dropColumn(['kodekelas', 'kelasid']);
        });
    }
};