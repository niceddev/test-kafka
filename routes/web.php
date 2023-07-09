<?php

use Illuminate\Support\Facades\Route;

Route::get('/producer', function () {
    $producer = new \RdKafka\Producer();

    if ($producer->addBrokers("kafka:9092") < 1) {
        echo "Failed adding brokers\n";
        exit;
    }

    $topic = $producer->newTopic("test");

    if (!$producer->getMetadata(false, $topic, 2000)) {
        echo "Failed to get metadata, is broker down?\n";
        exit;
    }

    $topic->produce(RD_KAFKA_PARTITION_UA, 0, 'teest');

    dd('Message published');
});

Route::get('/consumer', function () {
    $consumer = new \RdKafka\Consumer();
    $consumer->setLogLevel(LOG_DEBUG);
    $consumer->addBrokers("kafka:9092");

    $topic = $consumer->newTopic("test");

    $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

    $msg = $topic->consume(0, 1000);
    while (true) {
        if (isset($msg->payload)) {
            print_r($msg->payload);
        }
    }
});
