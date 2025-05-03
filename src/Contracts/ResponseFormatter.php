<?php

namespace Laraflow\Crud\Contracts;

interface ResponseFormatter
{
    public function __invoke(mixed $content = '', int $code = 200): string;
}
