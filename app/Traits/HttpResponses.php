<?php

namespace App\Traits;

trait HttpResponses
{
    public function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($message = null, $code = 400, $data = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $this->hasil($data)
        ], $code);
    }

    public function hasil($data)
    {
        if (is_null($data)) {
            return null;
        }

        return collect($data->toArray())->flatten()->all();
    }
}