<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use BEAR\Resource\ResourceObject;
use Ray\Aop\MethodInvocation;
use Sentry\Tracing\SpanContext;

use function assert;
use function sprintf;

final class ResourceSpanFactory implements SpanContextFactoryInterface
{
    public function __invoke(MethodInvocation $invocation): SpanContext
    {
        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);
        $spanContext = new SpanContext();
        $spanContext->setOp('bear.resource');
        $spanContext->setDescription(sprintf('%s - %s:/%s', $ro->uri->method, $ro->uri->scheme, $ro->uri->path));
        $spanContext->setTags(['class' => $invocation->getMethod()->class, 'method' => $invocation->getMethod()->name]);

        return $spanContext;
    }
}
