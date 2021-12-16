<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use Ray\Di\Di\Named;
use Sentry\Tracing\Span as TracingSpan;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\Transaction as SentryTransaction;
use Sentry\Tracing\TransactionContext;

use function http_response_code;
use function Sentry\init;
use function Sentry\startTransaction;

final class Transaction implements TransactionInterface
{
    /** @var array<string, string|mixed> */
    private array $options;
    private string $transactionName;
    private SentryTransaction $transaction;
    private static string $operation = 'backend';

    /**
     * @param array<string,mixed> $options
     *
     * @Named("options=sentry-options,name=sentry-tr-name")
     */
    #[Named('options=sentry-options,name=sentry-tr-name')]
    public function __construct(array $options, string $name)
    {
        $this->options = $options;
        $this->transactionName = $name;
        $this->startTransaction();
    }

    public function __destruct()
    {
        $this->finishTransaction();
    }

    private function startTransaction(): void
    {
        init($this->options);

        $transactionContext = new TransactionContext();
        $transactionContext->setName($this->transactionName);
        $transactionContext->setOp(self::$operation);
        $this->transaction = startTransaction($transactionContext);
    }

    private function finishTransaction(): void
    {
        $this->transaction->setHttpStatus((int) http_response_code());
    }

    public function startChild(SpanContext $context): TracingSpan
    {
        return $this->transaction->startChild($context);
    }
}
