<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ClientDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        DB::transaction(function () use ($faker) {

            foreach(range(1,100) as $index){
                $client = Client::create([
                    'uuid' => Str::uuid(),
                    'name' => $faker->name(),
                    'is_active' => $faker->randomElement([1,2]),
                    'is_archived' => $faker->boolean()
                ]);
    
                $client->clientContact()->create([
                    'email' => $faker->email(),
                    'mobile' => '+91'.$faker->numberBetween(1000000000, 9999999999)
                ]);
            }

        });
    }
}
