<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Galaxy\LaravelExchange1C\Interfaces;

interface ExportFieldsInterface
{
    /**
     * @param mixed|null $context
     *
     * @return array
     */
    public function getExportFields1c($context = null);
}
