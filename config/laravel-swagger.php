<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Mtrajano\LaravelSwagger\Definitions\Handlers\DefaultErrorDefinitionHandler;
use Mtrajano\LaravelSwagger\Definitions\Handlers\ValidationErrorDefinitionHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default info
    |--------------------------------------------------------------------------
    |
    | Define the swagger docs default version. This version will be used to
    | generate the docs when run the command: "laravel-swagger:generate".
    |
    */
    'defaultVersion' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Basic Info
    |--------------------------------------------------------------------------
    |
    | The basic info for the application such as the title description,
    | description.
    |
    */
    'title' => env('APP_NAME'),

    'description' => '',

    /*
    |--------------------------------------------------------------------------
    | Docs Route
    |--------------------------------------------------------------------------
    |
    | The route definitions that will be used to show the docs.
    |
    */
    'route' => [
        'path' => '/docs/{version?}',
        'name' => 'laravel-swagger.docs',
        'middleware' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Docs Versions Config
    |--------------------------------------------------------------------------
    |
    | The versions arrays must be incremented whenever you want to create a new
    | API version.  You can define the specific configuration for each version
    | of you API.
    |
    */
    'versions' => [
        [
            'appVersion' => '1.0.0',

            'host' => env('APP_URL'),

            'basePath' => '/v1',

            'schemes' => [
                // 'http',
                // 'https',
            ],

            'consumes' => [
                // 'application/json',
            ],

            'produces' => [
                // 'application/json',
            ],

            /*
            |--------------------------------------------------------------------------
            | Ignore methods
            |--------------------------------------------------------------------------
            |
            | Methods in the following array will be ignored in the paths array
            |
            */

            'ignoredMethods' => [
                'head',
            ],

            /*
            |--------------------------------------------------------------------------
            | Parse summary and descriptions
            |--------------------------------------------------------------------------
            |
            | Define the routes that should be ignored on docs.
            */

            'ignoredRoutes' => [
                'laravel-swagger.docs',
                'laravel-swagger.asset'
            ],

            /*
            |--------------------------------------------------------------------------
            | Parse summary and descriptions
            |--------------------------------------------------------------------------
            |
            | If true will parse the action method docBlock and make it's best guess
            | for what is the summary and description. Usually the first line will be
            | used as the route's summary and any paragraphs below (other than
            | annotations) will be used as the description. It will also parse any
            | appropriate annotations, such as @deprecated.
            |
            */

            'parseDocBlock' => true,

            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            |
            | If your application uses Laravel's Passport package with the recommended
            | settings, Laravel Swagger will attempt to parse your settings and
            | automatically generate the securityDefinitions along with the operation
            | object's security parameter, you may turn off this behavior with parseSecurity.
            |
            | Possible values for flow: ["implicit", "password", "application", "accessCode"]
            | See https://medium.com/@darutk/diagrams-and-movies-of-all-the-oauth-2-0-flows-194f3c3ade85
            | for more information.
            |
            */

            'parseSecurity' => true,

            'authFlow' => 'accessCode',

            /*
            |------------------------------------------------------------------
            | File path
            |------------------------------------------------------------------
            |
            | The config file path should be accessible from public URL. The
            | default value expects the "swagger-1-0-0.json" file exists on
            | "public" folder from Laravel project. The "file_path" config must
            | be on format "swagger-{replacing dots (.) to hyphens (-)}.(json|yaml)".
            | The "format" must be exactly the same from docs generation. E.g.:
            |
            | If you used the following command to generate:
            | `php artisan laravel-swagger:generate --format=yaml`
            | the format must be "yaml".
            |
            */
            'file_path' => env('SWAGGER_FILE_PATH', 'swagger-1-0-0.json'),

            'errors_definitions' => [
                'UnprocessableEntity' => [
                    'http_code' => 422,
                    'exception' => ValidationException::class,
                    'handler' => ValidationErrorDefinitionHandler::class
                ],
                'Forbidden' => [
                    'http_code' => 403,
                    'exception' => AuthorizationException::class,
                    'handler' => DefaultErrorDefinitionHandler::class
                ],
                'NotFound' => [
                    'http_code' => 404,
                    'exception' => ModelNotFoundException::class,
                    'handler' => DefaultErrorDefinitionHandler::class
                ],
                'Unauthenticated' => [
                    'http_code' => 401,
                    'exception' => AuthenticationException::class,
                    'handler' => DefaultErrorDefinitionHandler::class
                ],
            ],
        ],
    ],
];
