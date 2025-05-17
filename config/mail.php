<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => 30,
            'auth_mode' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'localhost'), PHP_URL_HOST)),
            'verify_peer' => false,

            // Enhanced SSL/TLS configuration
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'verify_depth' => 0,
                    'cafile' => env('MAIL_SSL_CAFILE'),
                    'capath' => env('MAIL_SSL_CAPATH'),
                ],
            ],

            // Additional SMTP options
            'source_ip' => env('MAIL_SOURCE_IP'),
            'persistent' => false,
            'debug' => env('MAIL_DEBUG', false),
        ],

        // For local development with Mailpit
        'mailpit' => [
            'transport' => 'smtp',
            'host' => env('MAILPIT_HOST', '127.0.0.1'),
            'port' => env('MAILPIT_PORT', 1025),
            'encryption' => null,
            'username' => null,
            'password' => null,
            'timeout' => 5,
        ],

        'ses' => [
            'transport' => 'ses',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'options' => [
                'ConfigurationSetName' => env('AWS_SES_CONFIGURATION_SET'),
                'Tags' => [
                    [
                        'Name' => 'Env',
                        'Value' => env('APP_ENV', 'production'),
                    ],
                ],
            ],
        ],

        'postmark' => [
            'transport' => 'postmark',
            'token' => env('POSTMARK_TOKEN'),
            'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            'client' => [
                'timeout' => 30,
            ],
        ],

        'mailgun' => [
            'transport' => 'mailgun',
            'domain' => env('MAILGUN_DOMAIN'),
            'secret' => env('MAILGUN_SECRET'),
            'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
            'scheme' => 'https',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -t -i'),
            'args' => [],
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL', 'stack'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => explode(',', env('MAIL_FAILOVER_MAILERS', 'smtp,log')),
            'max_retries' => env('MAIL_FAILOVER_MAX_RETRIES', 3),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "Reply-To" Address
    |--------------------------------------------------------------------------
    */

    'reply_to' => [
        'address' => env('MAIL_REPLY_TO_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'name' => env('MAIL_REPLY_TO_NAME', env('MAIL_FROM_NAME')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Queue Configuration
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'enable' => env('MAIL_QUEUE_ENABLE', true),
        'queue' => env('MAIL_QUEUE', 'emails'),
        'connection' => env('MAIL_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'sync')),
    ],

];
