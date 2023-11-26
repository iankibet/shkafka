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

```bash
KAFKA_BROKERS=
KAFKA_SASL_PLAIN_USERNAME=
KAFKA_SASL_PLAIN_PASSWORD=
```

To listen to a topic, start by creating a job for the topic you want to listen to.

For example, if you want to listen to a topic called "test", create a job called TestJob

Then go to shkafka.php config file and add the following to the topics array

```php
'test' => [
            \App\Jobs\TestJob::class,
        ],
```

Then run the following command in your terminal

```bash
php artisan shkafka:listen
```

