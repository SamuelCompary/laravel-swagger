<?php

namespace Mtrajano\LaravelSwagger\Responses;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Mtrajano\LaravelSwagger\DataObjects\Route;
use ReflectionException;

class ErrorResponseGenerator
{
    /**
     * @var Route
     */
    private $route;

    /**
     * @var array
     */
    private $errorsDefinitions;

    public function __construct(Route $route, array $errorsDefinitions)
    {
        $this->route = $route;
        $this->errorsDefinitions = $errorsDefinitions;
    }

    /**
     * Mount error response from http and description.
     *
     * @param int $httpCode
     * @param string $description
     * @return array
     */
    public function mountErrorResponse(int $httpCode, string $description)
    {
        return [
            (string) $httpCode => [
                'description' => $description,
                'schema' => [
                    '$ref' => '#/definitions/'.$this->getDefinitionNameByHttpCode($httpCode),
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function generate()
    {
        $response = [];

        $exceptions = $this->route->getExceptions();

        $exceptionsResponse = [
            ValidationException::class => $this->mountErrorResponse(422, 'Validation errors'),
            AuthenticationException::class => $this->mountErrorResponse(401, 'Unauthenticated'),
            ModelNotFoundException::class => $this->mountErrorResponse(404, 'Model not found'),
            AuthorizationException::class => $this->mountErrorResponse(403, 'Forbidden'),
        ];

        foreach ($exceptions as $exception) {
            $responseDefinition = $exceptionsResponse[$exception] ?? null;
            if ($responseDefinition) {
                $response += $responseDefinition;
            }
        }

        return $response;
    }

    /**
     * @param int $httpCode
     * @return string|null
     */
    private function getDefinitionNameByHttpCode(int $httpCode): ?string
    {
        foreach ($this->errorsDefinitions as $definitionName => $errorDefinition) {
            if ($errorDefinition['http_code'] == $httpCode) {
                return $definitionName;
            }
        }

        // TODO: Throw exception ???
        return null;
    }
}