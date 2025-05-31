<?php

namespace Database\Seeders;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use App\Modules\File\Repositories\FileRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Modules\Shop\Models\Product;
use App\Modules\Shop\Models\ProductGallery;
use App\Modules\Shop\Models\ProductRelated;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $this->print = new \Symfony\Component\Console\Output\ConsoleOutput();
        $faker = \Faker\Factory::create();

        $productIds = [];
        $fileIds = [];

        $fileRepository = App::make(FileRepository::class);

        // Crear archivos (imagenes)
        for ($i = 0; $i < 12; $i++) {

            $imageUrl = 'https://placehold.co/400';

            // Descargar la imagen
            $response = Http::get($imageUrl);

            $tempPath = sys_get_temp_dir() . '/' . 'downloaded_' . Str::random(5) . '.jpg';
            file_put_contents($tempPath, $response->body());

            // Crear UploadedFile desde el archivo descargado para poder usarlo como archivo subido
            $uploadedFile = new UploadedFile(
                $tempPath,
                basename($tempPath),
                'image/jpeg',
                null,
                true
            );

            $file = $fileRepository->create($uploadedFile);

            if ($file) {
                $this->print->writeln("Archivo: " . $file->nombre_original . " creado con ID: " . $file->id);
                $fileIds[] = $file->id;
            } else {
                $this->print->writeln("⚠️ Falló la creación del archivo fake.");
            }
        }

        // Resto del seeder...
        // Crear productos
        for ($i = 0; $i < 20; $i++) {
            $product = Product::create([
                'created_by' => 1,
                'sku' => strtoupper(Str::random(10)),
                'name' => $faker->unique()->words(3, true),
                'slug' => Str::slug($faker->unique()->words(3, true)),
                'description' => $faker->paragraphs(3, true),
                'short_description' => $faker->sentence,
                'total_sales' => $faker->numberBetween(0, 1000),
                'unit' => $faker->randomElement(['piece', 'box', 'kg', 'liter']),
                'price' => $faker->randomFloat(2, 10, 500),
                'discount' => $faker->numberBetween(0, 50),
                'quantity' => $faker->numberBetween(0, 500),
                'active' => $faker->boolean(80),
            ]);

            $this->print->writeln("Producto: " . $product->name . " creado con ID: " . $product->id);
            $productIds[] = $product->id;

            // Asignar imágenes al producto (3 a 5 imágenes)
            $assignedFiles = collect($fileIds)->random(rand(3, 5));
            $imgOrder = 1;

            foreach ($assignedFiles as $fileId) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'file_id' => $fileId,
                    'order' => $imgOrder,
                    'main' => $imgOrder === 1,
                ]);
                $this->print->writeln("Imagen ID: " . $fileId . " asignada al producto ID: " . $product->id);
                $imgOrder++;
            }
        }

        // Crear relaciones de productos (igual que antes)
        foreach ($productIds as $productId) {
            $relatedIds = collect($productIds)
                ->reject(fn($id) => $id === $productId)
                ->random(3);

            $order = 1;
            foreach ($relatedIds as $relatedId) {
                ProductRelated::create([
                    'created_by' => 1,
                    'product_id' => $productId,
                    'products_related_id' => $relatedId,
                    'oreder' => $order++,
                    'type' => $faker->randomElement(['upsell', 'crosssell', 'recommended']),
                ]);
                $this->print->writeln("Producto relacionado ID: " . $relatedId . " asignado al producto ID: " . $productId);
            }
        }

        $this->print->writeln('✅ 20 productos, imágenes y relaciones fake creados.');
    }
}
