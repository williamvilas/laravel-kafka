<?php

namespace App\Console\Commands;

use App\Services\KafkaService;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class KafkaProducer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:producer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Producer Kafka events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        for ($i = 0; $i < 30; $i++) {
            $kafkaService = new kafkaService();
            $faker = Faker::create('pt_BR');

            $gender = $faker->randomElement(['male', 'female']);

            $new = [
                'document' => $faker->cpf(false),
                'name' => $faker->name($gender),
                'gender' => $gender,
                'phone' => $faker->phoneNumber(),
                'credit_request' => $faker->randomFloat(2, 1000, 9000)
            ];

            $kafkaService->producer($new, 'EXPERIENCE', 'CREDIT_REQUEST');

            $this->info(
                ($i + 1) . ') producer: ' .
                $new['name'] . ' / ' .
                $new['gender'] . ' / ' .
                $new['document'] . ' / ' .
                $new['credit_request']);
        }
    }
}
