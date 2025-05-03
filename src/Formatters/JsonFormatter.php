<?php

namespace Laraflow\Crud\Formatters;

use Laraflow\Crud\Contracts\ResponseFormatter;

class JsonFormatter implements ResponseFormatter
{
    public function __invoke(mixed $content = '', int $code = 200): string
    {
        if (is_string($content)) {
            $content = json_validate($content)
                ? json_decode($content, true)
                : ['message' => $content];

        }

        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
