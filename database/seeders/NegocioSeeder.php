<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Negocio;

class NegocioSeeder extends Seeder
{
    public function run(): void
    {
        Negocio::create([
            'neg_nombre' => 'Barbería Central',
            'neg_categoria' => 'Barbería',
            'neg_direccion' => 'Cra 10 # 20-30',
            'neg_telefono' => '3001234567',
            'neg_descripcion' => 'Ofrecemos cortes modernos y clásicos. ¡Reserva tu cita ya!',
        ]);
    }
}
