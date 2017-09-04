<?php

namespace Swis\Graylog2;

use Gelf\Message;
use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\TransportInterface;
use Gelf\Transport\UdpTransport;
use Illuminate\Http\Request;
use Psr\Log\AbstractLogger;

class Graylog2 extends AbstractLogger
{
    /** @var Logger */
    protected $logger;

    /** @var Publisher */
    protected $publisher;

    protected $context = [];

    public function __construct()
    {
        // Setup context for later use
        $this->context = config('graylog2.context');

        // Setup transport
        if (config('graylog2.connection.type') === 'UDP') {
            $transport = new UdpTransport(
                config('graylog2.connection.host'),
                config('graylog2.connection.port'),
                config('graylog2.connection.chunk_size')
            );
        } else {
            $transport = new TcpTransport(
                config('graylog2.connection.host'),
                config('graylog2.connection.port')
            );
        }

        // Setup publisher and logger
        $publisher = new Publisher();
        $publisher->addTransport($transport);
        $this->logger = new Logger($publisher, $this->context['facility']);
    }

    /**
     * Allows for additional transports to be added to the publisher.
     *
     * @param TransportInterface $transport
     */
    public function addTransportToPublisher(TransportInterface $transport)
    {
        $this->publisher->addTransport($transport);
    }

    /**
     * Send a message to Graylog.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = [])
    {
        $message = $this->logger->prepareLog($level, $message, $context);

        if (!empty($this->context['host'])) {
            $message->setHost($this->context['host']);
        }

        if (!empty($this->context['version'])) {
            $message->setHost($this->context['version']);
        }

        if (!empty($context['request']) && $context['request'] instanceof Request) {
            $message
                ->setAdditional('request_url', $context['request']->url())
                ->setAdditional('request_method', $context['request']->method())
                ->setAdditional('request_ip', $context['request']->ip());
        }

        // Add additionals from config
        if (!empty(config('graylog2.additional-fields'))) {
            foreach (config('graylog2.additional-fields') as $key => $value) {
                $message->setAdditional($key, $value);
            }
        }

        $this->logger->publishMessage($message);
    }

    /**
     * Log an already constructed GELF message.
     *
     * @param Message $message
     */
    public function logMessage(Message $message)
    {
        $this->logger->publishMessage($message);
    }
}
