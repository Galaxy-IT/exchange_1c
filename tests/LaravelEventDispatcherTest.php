<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Tests;

use Galaxy\LaravelExchange1C\Interfaces\EventDispatcherInterface;
use Galaxy\LaravelExchange1C\Interfaces\EventInterface;
use Galaxy\LaravelExchange1C\LaravelEventDispatcher;

class LaravelEventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $dispatcher = $this->makeDispatcher();
        $event = $this->createMock(EventInterface::class);
        $this->assertNull($dispatcher->dispatch($event));
    }

    /**
     * @return LaravelEventDispatcher
     */
    private function makeDispatcher(): LaravelEventDispatcher
    {
        return $this->app->make(EventDispatcherInterface::class);
    }
}
