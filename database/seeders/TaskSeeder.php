<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Task::factory()->count(50)->create();
    }
}


//  shoued by  use the code ===>  use HasFactory; in the model Task 
//  or i can't  write the commemd ==> php artisan db:seed 