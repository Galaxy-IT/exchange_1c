<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Galaxy\LaravelExchange1C\Interfaces;

/**
 * Interface EventInterface.
 */
interface EventInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
