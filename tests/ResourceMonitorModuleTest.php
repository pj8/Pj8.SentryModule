<?php

namespace Pj8\SentryModule;

use PHPUnit\Framework\TestCase;
use Pj8\SentryModule\Fake\FakeBar;
use Pj8\SentryModule\Fake\FakeBaz;
use Pj8\SentryModule\Fake\FakeRo;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class ResourceMonitorModuleTest extends TestCase
{
    public function testResourceMonitorModule(): void
    {
        $module = new ResourceMonitorModule(new SentryModule(['dsn' => null]));
        $module->install(new class extends AbstractModule{
            protected function configure()
            {
                $this->bind(FakeBaz::class);
                $this->bind(FakeBar::class);
            }
        });
        $injector = new Injector($module);
        $ro = $injector->getInstance(FakeRo::class);
        $fakeRo = $ro->onGet();
        $this->assertInstanceOf(FakeRo::class, $fakeRo);
        $transaction = $injector->getInstance(TransactionInterface::class);
        unset($transaction);
    }
}
