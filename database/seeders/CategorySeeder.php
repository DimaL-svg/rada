<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Category::updateOrCreate(
        ['slug' => 'uncategorized'], // унікальний шлях
        [
            'name' => 'Без категорії',
            'is_visible' => false, // вона буде прихована з меню завдяки першій задачі
            'pos' => 999          // нехай буде в самому кінці бази
        ]
    );
    }
}
