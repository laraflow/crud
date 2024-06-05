<?php

if (!function_exists('response_format')) {
    /**
     * @return mixed|string[]
     */
    function response_format($data, $statusCode = null): mixed
    {
        if (is_string($data)) {
            $data = ['message' => $data];
            //            if ($statusCode != null && config('api-crud.append_status_code')) {
            //                $data['code'] = $statusCode;
            //            }
        }

        //        if (is_array($data) && !isset($data['code'])) {
        //            if ($statusCode != null && config('api-crud.append_status_code')) {
        //                $data['code'] = $statusCode;
        //            }
        //        }

        return $data;
    }
}
