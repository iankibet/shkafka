# ShKafka

A simple Kafka client for laravel.

One can listen to a topic and consume messages from it.

One can also produce messages to a topic.

To install the package, run the following command in your terminal

```bash
composer require iankibet/shkafka
```

## Publish Config

```bash
php artisan vendor:publish --provider="Iankibet\Shkafka\ShKafkaServiceProvider"
```

## Usage

Once installed and config published, you can use the package by adding the following to your .env file

You can get the values from your kafka cluster

If you need a free kafka cluster, you can get one from [upstash.com](https://upstash.com/)

```bash
KAFKA_BROKERS=
KAFKA_SASL_PLAIN_USERNAME=
KAFKA_SASL_PLAIN_PASSWORD=
```

To listen to a topic, start by creating a job for the topic you want to listen to.

For example, if you want to listen to a topic called "test", create a job called TestJob

Then go to shkafka.php config file and add the following to the topics array

You can add as many topics as you want in the array

```php
'test' => [
            \App\Jobs\TestJob::class,
        ],
```

Then run the following command in your terminal

```bash
php artisan shkafka:listen
```

When a new message is published to the topic, the job will be dispatched.

To produce a message to a topic, use the following code

```php
KafkaRepository::produce('test', [
            'message' => 'test message',
]);
```
