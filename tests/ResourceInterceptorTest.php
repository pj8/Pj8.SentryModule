<?php

namespace Pj8\SentryModule;

use BEAR\Resource\Module\ResourceModule;
use BEAR\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;
use Ray\Aop\ReflectiveMethodInvocation;
use Ray\Di\Injector;

class ResourceInterceptorTest extends TestCase
{
    private ?Transaction $transaction;

    public function testInvokeReturnsAppResourceCaseAppResource(): void
    {
        $injector = new Injector(new ResourceModule('FakeApplication'), __DIR__ . '/tmp');

        $resource = $injector->getInstance(ResourceInterface::class);
        $fakeAppRo = $resource->get('app://self/index');

        $interceptor = $this->createResourceInterceptor();

        $invocation = new ReflectiveMethodInvocation($fakeAppRo, 'onGet', [$interceptor]);
        $fakeRoResult = $interceptor->invoke($invocation);

        $this->assertSame($fakeAppRo, $fakeRoResult);
    }

    private function createResourceInterceptor(): ResourceInterceptor
    {
        $dryRun = ['dsn' => null];
        $this->transaction = new Transaction($dryRun, 'test-dummy');

        return new ResourceInterceptor(new Span($this->transaction), new SpanContextFactory(new ResourceSpanFactory()));
    }
}
