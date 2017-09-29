<?php

namespace Swis\Graylog2;

use Gelf\Message;
use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\TransportInterface;
use Gelf\Transport\UdpTransport;
use Psr\Log\AbstractLogger;
use Swis\Graylog2\Processor\ProcessorInterface;

class Graylog2 extends AbstractLogger
{
    /** @var Logger */
    protected $logger;

    /** @var Publisher */
    protected $publisher;

    /** @var ProcessorInterface[] */
    protected $processors = [];

    public function __construct()
    {
        $this->setupGraylogTransport();
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = [])
    {
        $gelfMessage = $this->logger->prepareLog($level, $message, $context);
        $exception = null;
        if (array_key_exists('exception', $context)) {
            $exception = $context['exception'];
        }

        $gelfMessage = $this->invokeProcessors($gelfMessage, $exception, $context);
        $this->logger->publishMessage($gelfMessage);
    }

    /**
     * @param \Exception $exception
     */
    public function logException(\Exception $exception)
    {
        // Set short-message as it is a requirement
        $message = new Message();
        $message->setShortMessage(substr($exception->getMessage(), 0, 100));

        $message = $this->invokeProcessors($message, $exception);
        $this->logger->publishMessage($message);
    }

    /**
     * @param Message $message
     */
    public function logGelfMessage(Message $message)
    {
        $message = $this->invokeProcessors($message);
        $this->logger->publishMessage($message);
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
     * Set's the default facility on the logger/transport.
     *
     * @param $facility
     */
    public function setFacility($facility)
    {
        $this->logger->setFacility($facility);
    }

    /**
     * Allows you to refine the message before sending it to Graylog.
     * You could add a callback to add the current user or other
     * runtime info to the message.
     *
     * @param callable           $callback
     * @param ProcessorInterface $processor
     */
    public function registerProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @param Message $message
     * @param null    $exception
     * @param mixed   $context
     *
     * @return Message
     */
    private function invokeProcessors(Message $message, $exception = null, $context = [])
    {
        foreach ($this->processors as $processor) {
            $message = $processor->process($message, $exception, $context);
        }

        return $message;
    }

    /**
     * Setup Graylog transport.
     */
    private function setupGraylogTransport()
    {
        // Setup the transport
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
        $this->publisher = new Publisher();
        $this->publisher->addTransport($transport);
        $this->logger = new Logger($this->publisher);
    }
}
