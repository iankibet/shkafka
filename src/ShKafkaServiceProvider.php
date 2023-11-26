<?php
namespace Iankibet\Shkafka;

use Iankibet\Shkafka\Commands\KafkaSubscribe;
use Illuminate\Support\ServiceProvider;

class ShKafkaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        if($this->app->runningInConsole()){
            $this->commands([
                KafkaSubscribe::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/shkafka.php' => config_path('shkafka.php'),
        ]);
    }
}
