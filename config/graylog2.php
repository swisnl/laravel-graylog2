<?php

return [

    'enabled' => true,

    // Log HTTP requests with exceptions
    'log-requests' => true,

    'connection' => [
        'host' => '127.0.0.1',
        'port' => '12201',
        'type' => 'UDP',

        // Set to UdpTransport::CHUNK_SIZE_WAN as a default
        'chunk_size' => '1420',
    ],

    /*
     * Add additional context to the GELF message
     */
    'context' => [
        'host' => 'localhost',
        'version' => 'version',
        'facility' => 'default-facility',
    ],

    // Allows you to set additional fields in the GELF message, use key => value
    'additional-fields' => [

    ]
];