<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helper\SlugPatternHelper;
use App\Jobs\CreateLog;
use App\Services\KafkaService;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Faker\Factory as Faker;

class KafkaConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume Kafka events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $i = 0;
        $kafkaService = new kafkaService();
        $timeout = Carbon::now()->addSeconds(10);
        $date = Carbon::now();

        while ($timeout > $date) {

            $message = $kafkaService->consumer(
                KafkaConsumer::class, // ID do grupo
                'EXPERIENCE', // Nome do tópico. Suporta vários tópicos consumidos simultaneamente.
                KafkaConsumer::class, // ID da instância do grupo. Use configurações diferentes para consumidores diferentes.
                $_SERVER['HOSTNAME'] // ID do cliente Kafka. Use configurações diferentes para consumidores diferentes.
            );

            if ($message) {
                $data = json_decode($message->getValue());

                $this->info(
                    ++$i. ') Consumer: ' . $data->name .
                    ' / gender: ' . $data->gender .
                    ' / doc: ' . $data->document .
                    ' / credit: ' . $data->credit_request
                );

                $kafkaService->ack($message);
            }

            sleep(1);
            $date = Carbon::now();
        }
    }
}
