<?php

namespace App\Console\Commands;

use App\Models\Data;
use App\Models\Devices;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Consumer;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumer extends Command
{
    protected $signature = 'rabbitmq:consume';

    protected $description = 'Runs a AMQP consumer that consumes messages and adds the data to the database';

    public function handle(Amqp $consumer): bool
    {
        Log::info('Listening for messages...');

        $consumer->consume(
            'DataCollection',
            function (AMQPMessage $message, Consumer $resolver): void {
                Log::info('Consuming message...');

                try {
                    $payload = json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);
                    Log::info('Message received', $payload);
                    $device = Devices::where('uuid', trim($payload['device_uuid']))->first();
                    Data::create([
                        'people' => $payload['people'],
                        'products_pr_person' => $payload['products_pr_person'],
                        'total_value' => $payload['total_value'],
                        'product_categories' => $payload['product_categories'],
                        'packages_received' => $payload['packages_received'],
                        'packages_delivered' => $payload['packages_delivered'],
                        'device_id' => $device->id,
                        'data_recorded_at' => Carbon::createFromTimestamp($payload['data_recorded_at']),
                    ]);

                    $resolver->acknowledge($message);
                } catch (Exception $exception) {
                    Log::error('Couldn\'t handle message.');
                    $resolver->reject($message);
                    report($exception);
                }
            },
            [
                'persistent' => true,
            ]
        );

        Log::info('Consumer exited.');

        return true;
    }
}
