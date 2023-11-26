<?php

namespace Iankibet\Shkafka\Helpers;

use phpDocumentor\Reflection\Types\Self_;

class KafkaRepository
{
    protected static function getConfig(){
        $conf = new \RdKafka\Conf();
        $conf->set('bootstrap.servers', config('shkafka.kafka.brokers'));
        $conf->set('sasl.username', config('shkafka.kafka.username'));
        $conf->set('sasl.password', config('shkafka.kafka.password'));
        $conf->set('security.protocol', config('shkafka.kafka.protocol'));
        $conf->set('sasl.mechanism', config('shkafka.kafka.mechanism'));
        if(config('shkafka.kafka.debug') != null)
            $conf->set('debug', config('shkafka.kafka.debug'));
        return $conf;
    }
    public static function getProducer(){
        $conf = self::getConfig();
        $producer = new \RdKafka\Producer($conf);
        return $producer;
    }
    public static function getConsumer(){
        $conf = self::getConfig();
       if(config('shkafka.kafka.group_id'))
           $conf->set('group.id', config('shkafka.kafka.group_id'));
        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers(config('shkafka.kafka.brokers'));
        return $rk;
    }

    public static function produce($topic,$payload){
        $producer = self::getProducer();
        $ktopic = $producer->newTopic($topic);
        $message = json_encode($payload);
        $res = $ktopic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $producer->flush(config('microservice.kafka.flush_timeout',10000));
    }
}
