<?php

return [

    'enabled' => true,

    // Log HTTP requests with exceptions
    'log_requests' => true,

    // Log HTTP Request GET data
    'log_request_get_data' => true,

    // Log HTTP Request POST data
    'log_request_post_data' => true,

    // Filter out some sensitive post parameters
    'disallowed_post_parameters' => ['password', 'username'],

    /*
     * Also add exception data in the full message.
     * This increases the size of the message by a lot. The
     * exception information is also included in the 'exception'
     * field.
     */
    'stack_trace_in_full_message' => false,

    'connection' => [
        'host' => '127.0.0.1',
        'port' => '12201',
        'type' => 'UDP',

        // Set to UdpTransport::CHUNK_SIZE_LAN as a default
        'chunk_size' => '8154',
    ]
];