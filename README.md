# ShKafka

A simple Kafka client for laravel.

One can listen to a topic and consume messages from it.

One can also produce messages to a topic.

To install the package, run the following command in your terminal

```bash
composer require shweshi/shkafka
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


