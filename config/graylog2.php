<?php

return [

    'enabled' => true,

    // Log HTTP requests with exceptions
    'log-requests' => true,

    /*
     * Also add exception data in the full message.
     * This increases the size of the message by a lot. The
     * exception information is also included in the 'exception'
     * field.
     */
    'exception-in-full-message' => false,

    'connection' => [
        'host' => '127.0.0.1',
        'port' => '12201',
        'type' => 'UDP',

        // Set to UdpTransport::CHUNK_SIZE_LAN as a default
        'chunk_size' => '8154',
    ],

    /*
     * Add additional context to the GELF message
     */
    'context' => [
        'host' => 'localhost',
        'version' => 'v1',
        'facility' => 'default-facility',
    ],

    // Allows you to set additional fields in the GELF message, use key => value
    'additional-fields' => [

    ]
];