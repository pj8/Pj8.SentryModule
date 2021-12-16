<?php

namespace Pj8\SentryModule;

use BEAR\Resource\Module\ResourceModule;
use BEAR\Sunday\Extension\Router\RouterMatch;
use BEAR\Sunday\Extension\Transfer\NullTransfer;
use BEAR\Sunday\Provide\Error\VndError;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use RuntimeException;

use function Sentry\init;

class SentryErrorHandlerTest extends TestCase
{
    public function testHandleDispatchOriginal(): void
    {
        $dryRun = ['dsn' => null];
        init($dryRun);

        $transfer = new NullTransfer();
        $original = new VndError($transfer);
        $handler = new SentryErrorHandler($original);
        $exception = new RuntimeException();
        $injector = new Injector(new ResourceModule('FakeApplication'), __DIR__ . '/tmp');
        $request = $injector->getInstance(RouterMatch::class);
        $result = $handler->handle($exception, $request);

        $this->assertInstanceOf(VndError::class, $result);
        $handler->transfer();
    }
}
