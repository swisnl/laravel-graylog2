<?php

namespace Swis\Graylog2;

class TestGraylog2Transport implements \Gelf\Transport\TransportInterface {

    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Sends a Message over this transport.
     *
     * @param \Gelf\MessageInterface $message
     * @return int the number of bytes sent
     */
    public function send(\Gelf\MessageInterface $message)
    {
        $this->callback->__invoke($message);
        return 1;
    }
}