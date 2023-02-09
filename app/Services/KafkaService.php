<?php

declare(strict_types=1);

namespace App\Services;

use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;

use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

class KafkaService
{
    public function producer(array $data, string $topic, string $key): void
    {
        $config = $this->setConfigProducer();
        $producer = new Producer($config);
        $producer->send($topic, json_encode($data), $key);
    }

    public function consumer(
        string $groupId,
        string $topic,
        string $groupInstanceId,
        string $clientId = null
    ): ?ConsumeMessage
    {
        $config = $this->setConfigConsumer(
            $groupId,
            $topic,
            $groupInstanceId,
            $clientId
        );

        $consumer = new Consumer($config);
        $message = $consumer->consume();

        if ($message) {
            $consumer->ack($message);
            return $message;
        }

        return null;
    }

    private function setConfigProducer(): ProducerConfig
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(config('kafka.broker'));
        $config->setUpdateBrokers(true);
        $config->setAcks(-1);
        return $config;
    }

    private function setConfigConsumer(
        string $groupId,
        string $topic,
        string $groupInstanceId,
        string $clientId = null,
    ): ConsumerConfig
    {
        $config = new ConsumerConfig();
        $config->setBroker(config('kafka.broker'));
        $config->setGroupId($groupId); // group ID
        $config->setTopic($topic);
        $config->setGroupInstanceId($groupInstanceId);
        $config->setGroupRetrySleep(2);
        $config->setGroupRetry(10);
        $config->setClientId($clientId); // ID do Cliente. Use configurações diferentes para consumidores diferentes.
        return $config;
    }
}
