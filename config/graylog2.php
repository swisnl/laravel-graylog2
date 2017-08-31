<?php

return [

    'connection' => [
        'host' => '',
        'port' => '',
        'type' => 'UDP',

        // Check UdpTransport::CHUNK_SIZE_WAN
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
    'additional_fields' => [

    ]
];