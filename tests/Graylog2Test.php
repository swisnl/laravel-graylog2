<?php

use Swis\Graylog2\Graylog2;

include __DIR__.'/TestGraylog2Transport.php';

class Graylog2Test extends AbstractTest
{
    /**
     * Tests adding additional transports.
     */
    public function testTransport()
    {
        $graylog2 = new Graylog2();

        // Mock the null transport and add it to the transport stack in the publisher
        $transportStub = $this->getMockBuilder(\Gelf\Transport\TcpTransport::class)
            ->setMethods(['send'])
            ->getMock();
        $graylog2->addTransportToPublisher($transportStub);

        // Expect the stub to be called
        $transportStub->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(\Gelf\Message::class));

        $graylog2->log('emergency', 'test', []);
    }

    /**
     * Tests the generation of a GELF message.
     */
    public function testMessageGeneration()
    {
        $graylog2 = new Graylog2();

        $self = $this;
        $testTransport = new TestGraylog2Transport(function (\Gelf\MessageInterface $message) use ($self) {
            $self->assertEquals('test', $message->getShortMessage());
            $self->assertEquals('error', $message->getLevel());
        });

        $graylog2->addTransportToPublisher($testTransport);

        $graylog2->log('error', 'test', []);
    }

    /**
     * Tests the generation of a GELF message.
     */
    public function testException()
    {
        // Set additional fields
        $graylog2 = new Graylog2();
        $graylog2->registerProcessor(new \Swis\Graylog2\Processor\ExceptionProcessor());

        $e = new \Exception('test Exception', 300);
        $l = __LINE__;

        $self = $this;
        $testTransport = new TestGraylog2Transport(function (\Gelf\MessageInterface $message) use ($self, $l) {
            $self->assertEquals($l, $message->getLine());
        });

        $graylog2->addTransportToPublisher($testTransport);
        $graylog2->logException($e);
    }

    /**
     * Tests the generation of a message with a request.
     */
    public function testRequest()
    {
        // Set additional fields
        $graylog2 = new Graylog2();
        $graylog2->registerProcessor(new \Swis\Graylog2\Processor\RequestProcessor());

        $self = $this;
        $testTransport = new TestGraylog2Transport(function (\Gelf\MessageInterface $message) use ($self) {
            $self->assertEquals('http://localhost', $message->getAdditional('request_url'));
            $self->assertEquals('GET', $message->getAdditional('request_method'));
            $self->assertEquals('127.0.0.1', $message->getAdditional('request_ip'));
        });

        $graylog2->addTransportToPublisher($testTransport);
        $graylog2->log('error', 'test', [
            'request' => request(),
        ]);
    }

    /**
     * Tests the logging of a raw test message.
     */
    public function testRawGelfMessage()
    {
        // Set additional fields
        $graylog2 = new Graylog2();

        $self = $this;
        $testTransport = new TestGraylog2Transport(function (\Gelf\MessageInterface $message) use ($self) {
            $self->assertEquals('Test Message', $message->getShortMessage());
        });

        $graylog2->addTransportToPublisher($testTransport);

        $message = new \Gelf\Message();
        $message->setShortMessage('Test Message');

        $graylog2->logGelfMessage($message);
    }
}
