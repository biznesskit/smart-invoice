<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'linode' => [
            'driver' => 's3',
            'key' => env('LINODE_KEY'),
            'secret' => env('LINODE_SECRET'),
            'endpoint' => env('LINODE_ENDPOINT'),
            'region' => env('LINODE_REGION'),
            'bucket' => env('LINODE_BUCKET'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'wasabi' => [
            'driver' => 's3',
            'key' => env('WAS_ACCESS_KEY_ID','GAIFBE2E0OF4WPUANFM3'),
            'secret' => env('WAS_SECRET_ACCESS_KEY','cgLtGVzC7EL8JtQDOc9gObEtq1etYPsrnAMmbVEm'),
            'region' => env('WAS_DEFAULT_REGION','eu-central-1'),
            'bucket' => env('WAS_BUCKET','bizkit'),
            'endpoint' => env('WAS_URL','https://s3.eu-central-1.wasabisys.com'),
        ],
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'key_file' => [
                    "type"=> "service_account",
                    "project_id"=> "poetic-airport-442816-d9",
                    "private_key_id" => "47361cb966b831616232f689296d4f014be33c7e",
                    "private_key"=>"-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCvJPs95GvIn1BH\n/vRh5MH6GYGA2afef9tVD5nnc+U/gjwJwQKgNaniKd0EYlyTM/1JW/IkPRSalMLJ\nKN/MIZruySwOTYB0kEDQ1D79900W5vG4wT+/qymPkz3YOmXJ5pb1kuwhB344zySF\npLqW9Jvtpqs/4uVjhdakKWlt42vjLcSoa4i/3++G8BcwKNOWr6xvXKcIF08umM8Q\niXpNRuhBhVQGsyXG/ffykmK1GSMx8vI+WQGUiCmA6LnmiLDTDS5+I3oUh0nyqB77\nGN9w5WMiPA0vnDFJVtR4WZPTUt6tm9deY+6dUDGPgejClMbnepmCS+aas29t1Zoj\nD3oCpoEHAgMBAAECggEADVxlXC3aORZKMgvVichBMfqTIgi4oSUgzsbfZ0Q0l1kw\nik/yitU9LfcimKyGQmaDan68pJ4SPc6eY2keVXy0zIWFOlwYMgw1HZkrawVcGKSq\niP0ZB16fz4ecfqvJyxKvrnE0WTqwmtXWg+lyoIRthr4hr0B4XlS98I0GMW9Zch2R\nh8KoOjXRE534fl4hN7CGCp6lgI6pQeeT8yztDnIAf3e7Ze5lyFFBMGjO2ezP/fYr\nll0U+fof98DTfHRFUfWNPeH6hnhdrZo9eHlJBLDraAiB9JVRaPHKkRRqKVHrGecj\n33eAjXEQn5Goo8otxGlchRN3wqFR7uBTw1781ZvdQQKBgQD2Na/tXYeSds8yEDBY\nz6PjU+2nUt/EvTG9ibbWx0b+g1+ANDbBNIZx3m/x7uT0hrMVZP1Epmw7bYExDopo\nj5FO0pbzz+FXUnYRLp2GFBdomyCShjYoW97JxYJbWPSep6YlJE0ouwoJFVJeCVpj\nyRJB6mIFpl/4NIjSuFFuXFiiQQKBgQC2G+Edh109KmTw4n4YUb+UnVTTqToCFJN/\nESjoUviETpXsHFWK9wgm+3q6++9rBpdRizXuZChF8zT7mwvJUHiPiZaxg3T2ZBW7\n1YGl0B8oy0QIwFMQUDUq1h86b/gtPXq2hc1YBrNS+H6Ka0CA1ra9n3D95+eZUP6b\ncnyyfx9BRwKBgQDZ5f/SMjF25WFQLFWtc+7WlQPCTy0tQEfSOBwyD2F8hKYzn4JK\nEYNfKX6nfkbxRnkd2Sfw5tzo52wsD3NdQJpYSTZBh1wmXRuS70+IRyXEdW4pX1B0\n/YG5zuwkjiDIfRUkz/DduFMUYONpyBtQLrbS0W15FJrg3xWscGXZJy9mQQKBgCF1\nY9nyCe54AI3Ff1dmhaV/dptg5ziEoUjiVTrCK8jbS25TEmBy3LuUzsrWCPoH/vtL\ncYA4RLH9akmkBflZ4Jy2scoxlebDIr8dDjHx2Be9qOVWuKwxoGMbbidldRqSxh4M\n3VqD+KSQ5Wb3J6XETVTvm2n7FrEzkWPsF/8PniV3AoGBAOSL3mZLZjN8vqKGq7xc\nNwP4BMoMgR4uA5oRqwHG19bMA36/fkDVJKnTo0SoN+9E7kTyt2i6AbWc8BKVtoWt\nSbja05OaCXNk/fXBtr/ghDe2AN+vvHa1cF6MJpWhLk952LV09mgVU26LiFLPpFDs\nys1riFMPBats7PmhPa+joVwW\n-----END PRIVATE KEY-----\n",
                    "client_email"=> "test-storage@poetic-airport-442816-d9.iam.gserviceaccount.com",
                    "client_id"=> "117161948439731503814",
                    "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
                    "token_uri"=> "https://oauth2.googleapis.com/token",
                    "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
                    "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/test-storage%40poetic-airport-442816-d9.iam.gserviceaccount.com",
                    // "universe_domain"=> "googleapis.com",
                ], // optional: /path/to/service-account.json
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
            'path_prefix' => '', // Optional: Add a prefix to all paths
            'storage_api_uri' => null, // Optional: Use a custom API endpoint
            // 'visibility' => 'public', // Default visibility for files
            'uniform_bucket_level_access' => true,
            // 'throw' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
