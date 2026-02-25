<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Belleza',
                'icon' => 'sparkles',
                'color' => '#df8be8',
                'children' => [
                    'Peluquería',
                    'Barbería',
                    'Uñas',
                    'Depilación',
                    'Maquillaje',
                    'Cama Solar',
                    'Tatuaje',
                    'Peluquería Canina',
                    'Micropigmentación',
                    'Extensiones de cabello',
                    'Lifting de pestañas',
                ],
            ],
            [
                'name' => 'Bienestar',
                'icon' => 'heart',
                'color' => '#32ccbc',
                'children' => [
                    'Spa',
                    'Masajes',
                    'Clínicas Estéticas',
                    'Meditación',
                    'Aromaterapia',
                    'Terapia de flotación',
                    'Reflexología',
                ],
            ],
            [
                'name' => 'Cuidados',
                'icon' => 'shield-check',
                'color' => '#5a31d7',
                'children' => [
                    'Acupuntura',
                    'Quiropráctico',
                    'Nutricionista',
                    'Coaching',
                    'Fisioterapia',
                    'Psicología',
                    'Odontología',
                    'Kinesiología',
                    'Fonoaudiología',
                    'Podología',
                    'Osteopatía',
                ],
            ],
            [
                'name' => 'Fitness',
                'icon' => 'bolt',
                'color' => '#ffa8d7',
                'children' => [
                    'Yoga',
                    'Gimnasio',
                    'Entrenador Personal',
                    'Pilates',
                    'Ciclismo Indoor',
                    'Baile',
                    'CrossFit',
                    'Natación',
                    'Artes Marciales',
                    'Zumba',
                ],
            ],
            [
                'name' => 'Deportes',
                'icon' => 'trophy',
                'color' => '#90f7ec',
                'children' => [
                    'Cancha de Pádel',
                    'Cancha de Fútbol 5',
                    'Cancha de Tenis',
                    'Cancha de Pickleball',
                    'Cancha de Squash',
                    'Cancha de Básquet',
                    'Cancha de Vóley',
                    'Pista de Atletismo',
                    'Campo de Golf',
                    'Piscina',
                ],
            ],
            [
                'name' => 'Educación',
                'icon' => 'academic-cap',
                'color' => '#5a31d7',
                'children' => [
                    'Clases de Idiomas',
                    'Clases de Música',
                    'Clases de Dibujo',
                    'Tutorías',
                    'Fotografía',
                    'Cocina',
                ],
            ],
            [
                'name' => 'Hogar',
                'icon' => 'home',
                'color' => '#32ccbc',
                'children' => [
                    'Limpieza',
                    'Plomería',
                    'Electricista',
                    'Carpintería',
                    'Mudanzas',
                    'Jardinería',
                ],
            ],
            [
                'name' => 'Mascotas',
                'icon' => 'paw',
                'color' => '#df8be8',
                'children' => [
                    'Veterinaria',
                    'Guardería canina',
                    'Paseo de perros',
                    'Peluquería canina',
                    'Adiestramiento',
                ],
            ],
        ];

        foreach ($categories as $order => $cat) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                    'sort_order' => $order + 1,
                    'is_active' => true,
                ]
            );

            foreach ($cat['children'] as $childOrder => $childName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name' => $childName,
                        'parent_id' => $parent->id,
                        'sort_order' => $childOrder + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
