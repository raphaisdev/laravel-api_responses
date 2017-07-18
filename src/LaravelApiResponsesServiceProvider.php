<?php

/**
 * Author: Raphael <raphael.galera@ftd.com.br>
 *
 * Decription:
 * This package was created to extend the Laravel Framework response system, and elevate him to the standard
 * described on the {json:api} website.
 * The answers besides creating a more friendly and readable formatting also contemplate the control of the
 * Headers according to the last code.
 *
 * More information:
 * https://github.com/ftd-educacao/default-api-response
 */

namespace Uglymanfirst\LaravelApiResponses;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class LaravelApiResponsesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('success', function ($data, $status = 200) use ($factory) {
            $successFormat = ['data' => $data,];
            return $factory->make(json_encode($successFormat), $status, ['Content-Type' => 'application/json']);
        });

        $factory->macro('paginate', function ($data, $status = 200) use ($factory) {
            if (!is_array($data)) {
                $data = $data->toArray();
            }

            $removedData = $data['data'];

            unset($data['data']);

            $paginateFormat = [
                'meta' => ['pagination' => $data],
                'data' => $removedData,
            ];

            return $factory->make(json_encode($paginateFormat), $status, ['Content-Type' => 'application/json']);
        });

        $factory->macro('error', function ($data = [], $status = 400, $extras = []) use ($factory) {
            $errorFormat = ['errors' => $data];

            if (isset($extras['Content-Type'])) {
                unset($extras['Content-Type']);
            }

            $headers = array_merge($extras, ['Content-Type' => 'application/json']);

            return $factory->make(json_encode($errorFormat), $status, $headers);
        });

        $factory->macro('custom', function ($content = null, $status = 200, $headers = [], $headerContentType = 'application/json') use ($factory) {
            if (!is_null($content)) {
                $customFormat = $content;
            }

            if (array_key_exists('Content-Type', $headers)) {
                $headerContentType = $headers['Content-Type'];
            }

            $headers = array_merge($headers, ['Content-Type' => $headerContentType]);

            return $factory->make($customFormat, $status, $headers);
        });

        $factory->macro('defaultStatusCode', function ($status = 200, $extras = []) use ($factory) {
            $statusList = [
                // 1×× => 'Informational',
                102 => 'Processing',
                // 2×× => 'Success',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-authoritative Information',
                204 => '',//No Content
                206 => 'Partial Content',
                207 => 'Multi-Status',
                // 3×× => 'Redirection',
                302 => 'Found',
                304 => 'Not Modified',
                // 4×× => 'Client Error',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                409 => 'Conflict',
                413 => 'Payload Too Large',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                // => '5×× Server Error',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                503 => 'Service Unavailable'
            ];

            if (! isset($statusList[$status])) {
                $statusList[$status] = 'Response code not found.';
            }

            if (isset($extras['Content-Type'])) {
                unset($extras['Content-Type']);
            }

            $statusFormat   = '';
            $headers        = [];

            if ($status >= 400) {
                $statusFormat = [
                    'errors' => [$statusList[$status]]
                ];
                $headers = ['Content-Type' => 'application/json'];
            }
            $headers = array_merge($extras, $headers);

            return $factory->make($statusFormat, $status, $headers);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
