<?php
return [

    'topics'=>[
//        'logs' => [
//            \App\Events\NewLog::class,
//        ],
    ],
    'kafka'=>[
        //Brokers to connect to
        'brokers'=>env('KAFKA_BROKERS', 'localhost:9092'),

        // topic to publish events to,
        'topic'=>env('KAFKA_TOPIC'),

        // group id for the consumer
        'group_id'=>env('APP_NAME', 'hmservice'),

        // security protocol to use, default is PLAINTEXT
        'protocol'=>env('KAFKA_SECURITY_PROTOCOL', 'SASL_SSL'), //PLAINTEXT, SSL, SASL_PLAINTEXT, SASL_SSL

        // SASL mechanism to use, default is PLAIN
        'mechanism'=>env('KAFKA_SASL_MECHANISM', 'SCRAM-SHA-256'), //PLAIN, GSSAPI, OAUTHBEARER, SCRAM-SHA-256, SCRAM-SHA-512
        'username'=>env('KAFKA_SASL_PLAIN_USERNAME'),
        'password'=>env('KAFKA_SASL_PLAIN_PASSWORD'),
        'flush_timeout'=>env('KAFKA_FLUSH_TIMEOUT', 10000), //milliseconds

        // debug level, default is all
        'debug'=>env('KAFKA_DEBUG', null), //all, broker, topic, metadata, feature, queue, msg, protocol, cgrp, security, fetch, interceptor, plugin, eos, mock, consumer, admin, broker-transport, all
    ]
];
