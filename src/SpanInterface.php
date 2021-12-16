<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use Sentry\Tracing\SpanContext;

interface SpanInterface
{
    public function start(SpanContext $context): void;

    /**
     * @param mixed $value
     */
    public function finish($value): void;
}
