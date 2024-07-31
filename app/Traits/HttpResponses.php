<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($message = null, $code = 400, $data = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $this->hasilError($data)
        ], $code);
    }

    private function hasilError($data)
    {
        if (is_null($data)) {
            return null;
        }

        return collect($data->toArray())->flatten()->all();
    }
}