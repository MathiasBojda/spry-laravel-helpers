<?php
namespace Spry\Laravel\Api;

use Illuminate\Support\Facades\Response as LaravelResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Response
{
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
        $d = array(
             'status' => 'success',
             'data'   => $data,
         );
        return self::jsonRenderData($d, $status);
    }
 
    // 201: Created
    /**
     * jsonCreated
     *
     * @param null $data
     *
     * @return json
     */
    public static function jsonCreated($data = null)
    {
        $d = null;
        if ($data) {
            $d = array(
                 'status' => 'success',
                 'data'   => $data,
             );
        }
        return self::jsonRenderData($d, 201);
    }
 
    // 202: Accepted
    /**
     * jsonAccepcted
     *
     * @return json
     */
    public static function jsonAccepcted()
    {
        return self::jsonRenderData(null, 202);
    }
 
    // 204: No content
    /**
     * jsonNoContent
     *
     * @return json
     */
    public static function jsonNoContent()
    {
        return self::sonRenderData(null, 204);
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
