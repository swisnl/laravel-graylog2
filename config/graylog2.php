<?php

return [

    'enabled' => env('GRAYLOG_ENABLED', true),

    'log_level' => env('GRAYLOG_LOG_LEVEL', 'debug'),

    // Log HTTP requests with exceptions
    'log_requests' => env('GRAYLOG_LOG_REQUESTS', true),

    // Log HTTP Request GET data
    'log_request_get_data' => env('GRAYLOG_LOG_REQUESTS_GET_DATA', false),

    // Log HTTP Request POST data
    'log_request_post_data' => env('GRAYLOG_LOG_REQUESTS_POST_DATA', false),

    // Filter out some sensitive post parameters
    'disallowed_post_parameters' => ['password', 'username'],

    /*
     * Also add exception data in the full message.
     * This increases the size of the message by a lot. The
     * exception information is also included in the 'exception'
     * field.
     */
    'stack_trace_in_full_message' => ENV('GRAYLOG_STACK_TRACE', false),

    'connection' => [
        'host' => env('GRAYLOG_HOST', '127.0.0.1'),
        'port' => env('GRAYLOG_PORT', '12201'),

        /*
         * Choose between UDP and TCP transport.
         * UDP transports won't throw exceptions on transport errors,
         * but message reception by the Graylog server is not guaranteed.
         */
        'type' => env('GRAYLOG_TRANSPORT_TYPE', 'UDP'),

        // Set to UdpTransport::CHUNK_SIZE_LAN as a default
        'chunk_size' => env('GRAYLOG_CHUNK_SIZE', '8154'),
    ]
];
