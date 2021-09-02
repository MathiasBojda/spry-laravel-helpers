<?php
namespace Spry\Laravel\Api;

use Illuminate\Support\Facades\Response as LaravelResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Response
{
    public static $static_response_map = [
        
        500 => [
            'status' => 'error',
            'data'   => [
                'message' => 'Internal Server Error',
                'error_code' => 'exception'
            ]
        ],

        200 => [
            'status' => 'success',
            'data' => null,
        ],

        400 => [
            'status' => 'error',
            'data'   => [
                'message' => 'Not specified',
                'error_code' => 'not_specified'
            ]
        ],

        403 => [
            'status' => 'error',
             'data'   => [
                 'message' => 'Forbidden',
                 'error_code' => 'forbidden'
             ]
        ],

        404 => [
            'status' => 'error',
            'data'   => [
                'message' => 'Not Found',
                'error_code' => 'not_found'
            ]
        ],

        401 => [
            'status' => 'error',
            'data'   => [
                'message' => 'Unauthorized',
                'error_code' => 'unauthorized'
            ]
        ]

    ];

    // 200: Ok
    /**
     * jsonOk
     *
     * @param null $data
     * @param int $status
     *
     * @return json
     */
    public static function jsonOk($data = null, $status = 200)
    {
        $d = self::$static_response_map[200];
        $d['data'] = $data;
        return self::jsonRenderData($d, $status);
    }


    /**
     * jsonInternalServerError
     *
     * @return void
     */
    public static function jsonInternalServerError()
    {
        $http_status = 500;
        $d = self::$static_response_map[$http_status];
        return self::jsonRenderData($d, $http_status);
    }
    


    /**
     * jsonBadRequest
     *
     * @return void
     */
    public static function jsonBadRequest()
    {
        $http_status = 400;
        $d = self::$static_response_map[$http_status];
        return self::jsonRenderData($d, $http_status);
    }

    /**
     * jsonInsufficientRights
     *
     * @return void
     */
    public static function jsonInsufficientRights()
    {
        $http_status = 403;
        $d = self::$static_response_map[$http_status];
        return self::jsonRenderData($d, $http_status);
    }


    
    /**
     * jsonNotFound
     *
     * @return void
     */
    public static function jsonNotFound()
    {
        $http_status = 404;
        $d = self::$static_response_map[$http_status];
        return self::jsonRenderData($d, $http_status);
    }

    
    /**
     * jsonUnauthorized
     *
     * @return void
     */
    public static function jsonUnauthorized()
    {
        $http_status = 401;
        $d = self::$static_response_map[$http_status];
        return self::jsonRenderData($d, $http_status);
    }
 
    /**
     * jsonRenderData
     *
     * @param null $data
     * @param null $status
     *
     * @return json
     */
    private static function jsonRenderData($data = null, $status = null)
    {
        if ($data) {
            $obj = $data['data'];
 
            // Add pagination meta data if present on data object
            if (is_object($obj) && property_exists($obj, 'resource') && $obj->resource instanceof LengthAwarePaginator) {
                $data['meta'] = array(
                     'current_page'       => $obj->currentPage(),
                     'total_page_count'   => $obj->lastPage(),
                     'page_record_count'  => $obj->count(),
                     'total_record_count' => $obj->total(),
                     'links' => array(
                         'next'  => $obj->nextPageUrl(),
                         'prev'  => $obj->previousPageUrl(),
                         'last'  => $obj->url($obj->lastPage()),
                         'first' => $obj->url(1),
                     ),
                 );
            }
 
            return response()->json($data, $status);
        } elseif ($status) {
            return LaravelResponse::make('', $status);
        } else {
            return LaravelResponse::make('', 204);
        }
    }
}
