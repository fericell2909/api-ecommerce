<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Shop\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        $this->print = new \Symfony\Component\Console\Output\ConsoleOutput();

        // Primero creamos 5 categorías padre
        $parents = [];
        for ($i = 0; $i < 5; $i++) {
            $parent = Category::create([
                'created_by' => 1,  // o pon el ID del user que haga sentido
                'parent_id' => null,
                'order' => $i + 1,
                'icon' => 'fa-icon-' . $faker->word,
                'name' => $faker->unique()->words(2, true),
                'slug' => Str::slug($faker->unique()->words(2, true))
            ]);
            $this->print->writeln("Categoria ". $i . " creada: " . $parent->name);
            $parents[] = $parent;
        }

        // Ahora, para cada padre, creamos 3 hijos (5 x 3 = 15)
        foreach ($parents as $parent) {
            for ($j = 0; $j < 3; $j++) {
                Category::create([
                    'created_by' => 1,
                    'parent_id' => $parent->id,
                    'order' => $j + 1,
                    'icon' => 'fa-icon-' . $faker->word,
                    'name' => $faker->unique()->words(2, true),
                    'slug' => Str::slug($faker->unique()->words(2, true))
                ]);
            }
        }

        $this->command->info('✅ 20 categorías fake creadas (5 padres + 15 hijos)');
    }
}
