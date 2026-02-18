<?php
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    foreach (Category::all() as $category) {
        $category->update([
            'slug' => Str::slug($category->name) 
        ]);
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
