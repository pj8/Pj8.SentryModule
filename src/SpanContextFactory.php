<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use BEAR\Resource\ResourceObject;
use Ray\Aop\MethodInvocation;
use Sentry\Tracing\SpanContext;

use function in_array;
use function json_encode;
use function sprintf;

final class SpanContextFactory implements SpanContextFactoryInterface
{
    private ResourceSpanFactory $factory;

    public function __construct(ResourceSpanFactory $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(MethodInvocation $invocation): SpanContext
    {
        if ($this->isResourfceRequest($invocation)) {
            return ($this->factory)($invocation);
        }

        return $this->getGenericContext($invocation);
    }

    private function isResourfceRequest(MethodInvocation $invocation): bool
    {
        if (! $invocation->getThis() instanceof ResourceObject) {
            return false;
        }

        $method = $invocation->getMethod()->getName();

        return in_array($method, ['onGet', 'onPost', 'onUpdate', 'onDelete']);
    }

    private function getGenericContext(MethodInvocation $invocation): SpanContext
    {
        $spanContext = new SpanContext();
        $spanContext->setOp(sprintf(
            '%s::%s (%s)',
            $invocation->getMethod()->getDeclaringClass()->getName(),
            $invocation->getMethod()->getName(),
            (string) json_encode((array) $invocation->getNamedArguments())
        ));

        return $spanContext;
    }
}
