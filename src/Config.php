<?php
/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Galaxy\LaravelExchange1C;

/**
 * Class Config.
 */
class Config
{
    /**
     * @var string
     */
    private $importDir = 'import_dir';

    /**
     * @var string
     */
    private $login = 'admin';

    /**
     * @var string
     */
    private $password = 'admin';

    /**
     * @var bool
     */
    private $useZip = false;

    /**
     * @var int
     */
    private $filePart = 0;

    /**
     * @var null
     */
    private $auth = [];

    /**
     * @var array
     */
    private $models = [
        \Galaxy\LaravelExchange1C\Interfaces\GroupInterface::class   => null,
        \Galaxy\LaravelExchange1C\Interfaces\ProductInterface::class => null,
        \Galaxy\LaravelExchange1C\Interfaces\OfferInterface::class   => null,
    ];

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /**
     * Overrides default configuration settings.
     *
     * @param array $config
     */
    private function configure(array $config = []): void
    {
        foreach ($config as $param => $value) {
            $property = $this->toCamelCase($param);
            if (property_exists(self::class, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getImportDir(): string
    {
        return $this->importDir;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isUseZip(): bool
    {
        return $this->useZip;
    }

    /**
     * @return int
     */
    public function getFilePart(): int
    {
        return $this->filePart;
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * @param string $modelName
     *
     * @return null|string
     */
    public function getModelClass(string $modelName): ?string
    {
        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }

        return null;
    }

    /**
     * @return null|array
     */
    public function getAuth(): ?array
    {
        return $this->auth;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFullPath(string $filename): string
    {
        return $this->getImportDir().DIRECTORY_SEPARATOR.$filename;
    }

    /**
     * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName).
     *
     * @param string $str String in underscore format
     *
     * @return string $str translated into camel caps
     */
    private function toCamelCase($str): string
    {
        $func = function ($c) {
            return strtoupper($c[1]);
        };

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
