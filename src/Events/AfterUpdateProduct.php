<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace GalaxyIT\LaravelExchange1C\Events;

use GalaxyIT\LaravelExchange1C\Interfaces\ProductInterface;

class AfterUpdateProduct extends AbstractEventInterface
{
    const NAME = 'after.update.product';

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
