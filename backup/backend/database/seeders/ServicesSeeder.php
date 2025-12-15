<?php

namespace Database\Seeders;

use App\Models\ServicesRequestedForCitizen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $services = [
            ['name' => 'Electricity', 'slug' => 'electricity'],
            ['name' => 'Water', 'slug' => 'water'],
            ['name' => 'Passport Renewal', 'slug' => 'passport_renewal'],
            ['name' => 'ID Renewal', 'slug' => 'id_renewal'],
            ['name' => 'General Inquiry', 'slug' => 'general_inquiry'],
            ['name' => 'Technical Issue', 'slug' => 'technical_issue'],
            ['name' => 'Billing Question', 'slug' => 'billing_question'],
        ];
       
        
        foreach ($services as $service) {
            ServicesRequestedForCitizen::create($service);
        }
    }
}
