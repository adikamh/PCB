<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Microcontrollers
            ['name' => 'Arduino Uno R3', 'slug' => 'arduino-uno-r3', 'price' => 185000, 'stock' => 15, 'image' => 'arduino.jpg', 'description' => 'Papan mikrokontroler paling populer untuk pemula'],
            ['name' => 'NodeMCU ESP8266', 'slug' => 'nodemcu-esp8266', 'price' => 45000, 'stock' => 25, 'image' => 'nodemcu.jpg', 'description' => 'Board IoT dengan WiFi built-in'],
            ['name' => 'ESP32 DevKit V1', 'slug' => 'esp32-devkit-v1', 'price' => 75000, 'stock' => 20, 'image' => 'esp32.jpg', 'description' => 'Board dengan WiFi dan Bluetooth dual-mode'],
            
            // Single Board Computer
            ['name' => 'Raspberry Pi 4 Model B', 'slug' => 'raspberry-pi-4', 'price' => 1250000, 'stock' => 10, 'image' => 'raspberry_pi.jpg', 'description' => 'Komputer mini dengan performa tinggi'],
            
            // PCB & Components
            ['name' => 'PCB Polos FR4', 'slug' => 'pcb-polos-fr4', 'price' => 15000, 'stock' => 50, 'image' => 'pcb_polos.jpg', 'description' => 'PCB polos berkualitas tinggi'],
        ];

        foreach ($products as $product) {
            // Tentukan category_id berdasarkan nama produk
            if (in_array($product['name'], ['Arduino Uno R3', 'NodeMCU ESP8266', 'ESP32 DevKit V1'])) {
                $categoryId = Category::where('slug', 'microcontrollers')->first()->id;
            } elseif ($product['name'] == 'Raspberry Pi 4 Model B') {
                $categoryId = Category::where('slug', 'single-board-computer')->first()->id;
            } else {
                $categoryId = Category::where('slug', 'pcb-components')->first()->id;
            }

            Product::create(array_merge($product, ['category_id' => $categoryId]));
        }
    }
}