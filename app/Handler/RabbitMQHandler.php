<?php


namespace App\Handler;


use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler extends AbstractProcessingHandler
{
    protected $channel ;
    
    public function __construct($channel)
    {
        $this->channel = $channel;
        parent::__construct();
    
    }
    
    protected function write(array $record)
    {
        $data = $record["formatted"];
    
        $msg = new AMQPMessage((string) $data);
    
        $this->channel->basic_publish($msg, '', 'log');
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false);
    }
}