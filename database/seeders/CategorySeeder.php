<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Microcontrollers', 'slug' => 'microcontrollers', 'description' => 'Board mikrokontroler untuk berbagai keperluan IoT'],
            ['name' => 'Single Board Computer', 'slug' => 'single-board-computer', 'description' => 'Komputer mini untuk proyek canggih'],
            ['name' => 'PCB & Components', 'slug' => 'pcb-components', 'description' => 'PCB polos dan komponen elektronik'],
            ['name' => 'IoT Kits', 'slug' => 'iot-kits', 'description' => 'Paket lengkap untuk proyek IoT'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}