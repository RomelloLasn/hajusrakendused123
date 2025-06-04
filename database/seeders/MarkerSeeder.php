<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marker;

class MarkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Marker::create([
            'name' => 'Kuressaare Castle',
            'latitude' => 58.2507,
            'longitude' => 22.4805,
            'description' => 'A well-preserved medieval castle in Kuressaare, Saaremaa, Estonia.'
        ]);

        Marker::create([
            'name' => 'Tallinn Old Town',
            'latitude' => 59.4372,
            'longitude' => 24.7453,
            'description' => 'The historic heart of Tallinn featuring medieval architecture.'
        ]);

        Marker::create([
            'name' => 'Pärnu Beach',
            'latitude' => 58.3753,
            'longitude' => 24.5039,
            'description' => 'A beautiful sandy beach in the summer capital of Estonia.'
        ]);
    }
}
