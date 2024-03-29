<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '2199dd7247a09fe42aedfe59b59ee305eb02b95c30b2e7f4c5b129a3d58f0c64'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [

            # LOCALHOST - DEFAULT
            /*
            'host' => 'localhost',
             #'port' => 'non_standard_port_number',
            'username' => 'postgres',
            'password' => '123',
            'database' => 'dmi',
            */

            # 41 - NEW PHASE II DB
            /*
            'host' => '10.158.81.41',
             #'port' => 'non_standard_port_number',
            'username' => 'postgres',
            'password' => '123',
            'database' => 'newphaseIIdb',
            */
            
            # 41 - Test Migration
            
         /*   'host' => '10.158.81.41',
             #'port' => 'non_standard_port_number',
            'username' => 'postgres',
            'password' => '123',
         //   'database' => 'aqcmsp2',
			'database' => 'p2complt1',*/
            

            # LocalHost - Test Migration
                        
            'host' => 'localhost',
            #'port' => 'non_standard_port_number',
            'username' => 'postgres',
            'password' => '123',
            'database' => 'testmigration',


            'url' => env('DATABASE_URL', null),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
