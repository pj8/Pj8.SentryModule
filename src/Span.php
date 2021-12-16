<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use BEAR\Resource\ResourceObject;
use Sentry\Tracing\Span as TracingSpan;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\SpanStatus;

use function array_pop;
use function assert;
use function end;

final class Span implements SpanInterface
{
    /** @var array<TracingSpan|StartChildInterface> */
    private array $spans = [];
    private TransactionInterface $transaction;

    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    public function __destruct()
    {
        unset($this->transaction);
    }

    public function start(SpanContext $context): void
    {
        $span = $this->getSpan();
        $this->spans[] = $span->startChild($context);
    }

    /**
     * @param mixed $value
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function finish($value): void
    {
        $span = array_pop($this->spans);
        assert($span instanceof TracingSpan);
        $span->finish();
        if (! ($value instanceof ResourceObject)) {
            return;
        }

        $span->setStatus(SpanStatus::createFromHttpStatusCode($value->code));
    }

    /**
     * @return TracingSpan|StartChildInterface
     */
    private function getSpan()
    {
        if ($this->spans) {
            return end($this->spans);
        }

        $this->spans[] = $this->transaction;

        return $this->transaction;
    }
}
