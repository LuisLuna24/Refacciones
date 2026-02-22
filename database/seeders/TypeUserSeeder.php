<?php

namespace Database\Seeders;

use App\Models\TypeUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = [
            'Admin',
            'Employer',
            'Customer',
        ];
        foreach ($type as $type) {
            TypeUser::create(['name' => $type]);
        }
    }
}
