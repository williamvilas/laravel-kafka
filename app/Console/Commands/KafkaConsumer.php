<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helper\SlugPatternHelper;
use App\Jobs\CreateLog;
use App\Services\KafkaService;
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

        while (true) {

            $message = $kafkaService->consumer('testGroup', 'EXPERIENCE', 'test_custom', 'test_custom');

            if ($message) {
                $data = json_decode($message->getValue());

                $this->info(
                    ++$i . ') consumer: ' . $data->name .
                    ' / gender: ' . $data->gender .
                    ' / doc: ' . $data->document .
                    ' / credit: ' . $data->credit_request
                );
            }

            sleep(1);
        }
    }
}
