<?php

namespace Pj8\SentryModule;

use BEAR\Resource\Module\ResourceModule;
use BEAR\Sunday\Extension\Router\RouterMatch;
use BEAR\Sunday\Extension\Transfer\NullTransfer;
use BEAR\Sunday\Provide\Error\ThrowableHandler;
use BEAR\Sunday\Provide\Error\VndError;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use RuntimeException;

use function Sentry\init;

class SentryThrowableHandlerTest extends TestCase
{
    public function testHandleDispatchOriginal(): void
    {
        $dryRun = ['dsn' => null];
        init($dryRun);

        $transfer = new NullTransfer();
        $original = new ThrowableHandler(new VndError($transfer));
        $handler = new SentryThrowableHandler($original);
        $handler->transfer();
        $exception = new RuntimeException();
        $injector = new Injector(new ResourceModule('FakeApplication'), __DIR__ . '/tmp');
        $request = $injector->getInstance(RouterMatch::class);
        $result = $handler->handle($exception, $request);

        $this->assertInstanceOf(ThrowableHandler::class, $result);
    }
}
