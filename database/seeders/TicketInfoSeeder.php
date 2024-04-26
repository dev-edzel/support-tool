<?php

namespace Database\Seeders;

use App\Models\TicketInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketInfo::factory()->count(10)->create();
    }
}
