<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Galaxy\LaravelExchange1C\Events;

use Galaxy\LaravelExchange1C\Interfaces\ProductInterface;

class BeforeUpdateProduct extends AbstractEventInterface
{
    const NAME = 'before.update.product';

    /**
     * @var ProductInterface
     */
    public $product;

    /**
     * BeforeUpdateProduct constructor.
     *
     * @param ProductInterface $product
     */
    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }
}
