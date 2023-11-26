<?php

namespace Iankibet\Shkafka\Commands;

use Iankibet\Shkafka\Helpers\KafkaRepository;
use Illuminate\Console\Command;

class KafkaSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shkafka:subscribe {topic?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume kafka messages and dispatch events/jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eventsConfig = config('shkafka.topics');
        if(!$this->argument('topic')){
            $topics = array_keys($eventsConfig);
        } else {
            $topics = explode(',',$this->argument('topic'));
        }
        $consumer = KafkaRepository::getConsumer();
        $queue = $consumer->newQueue();
        foreach ($topics as $topic){
            if(!isset($eventsConfig[$topic])){
                $keys = array_keys($eventsConfig);
                $this->error("Topic not found in events list, [".implode(',',$keys)."]");
                return Command::FAILURE;
            }
            $topicConf = new \RdKafka\TopicConf();
            $topicConf->set("auto.commit.interval.ms", 1e3);
            $kafkaTopic = $consumer->newTopic($topic, $topicConf);
            $kafkaTopic->consumeQueueStart(0, RD_KAFKA_OFFSET_STORED, $queue);
        }



        while (true) {
            // The first argument is the partition (again).
            // The second argument is the timeout.
            $msg = $queue->consume(1000);
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                // Constant check required by librdkafka 0.11.6. Newer librdkafka versions will return NULL instead.
                continue;
            } elseif ($msg->err) {
                $this->error("Error occurred while consuming message");
                $this->error($msg->errstr());
                break;
            } else {
                $this->handleMessage($msg);
            }
        }

        return Command::SUCCESS;
    }

    protected function handleMessage($msg){
        $listeningTopic = $msg->topic_name;
        $events = config('shkafka.topics')[$listeningTopic];
        $this->warn(now()." Message received on topic $listeningTopic");
        $payload = json_decode($msg->payload);
        if(!is_array($events)){
            $events = [$events];
        }
        if(is_array($events)) {
            foreach ($events as $event) {
                if ($this->isJob($event)) {
                    $this->dispatchJob($event, $payload);
                } else if ($this->isEvent($event)) {
                    $this->dispatchEvent($event, $payload);
                } else {
                    $this->error("Invalid event type $event");
                }
            }
        }
        $this->info(now()." Message dispatched");
    }

    protected function isJob($event){
        return str_contains($event,'App\Jobs');
    }

    protected function isEvent($event){
        return str_contains($event,'App\Events');
    }

    protected function dispatchJob($job,$payload){
        $this->line(now()." Dispatching job $job");
        $newJob = $job::dispatch($payload);
        $this->line(now()." Job dispatched");
    }

    protected function dispatchEvent($event,$payload){
        $this->line(now()." Dispatching event $event");
        event(new $event($payload));
        $this->line(now()." Event dispatched");
    }
}
