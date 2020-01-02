<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Crypt;

use Inspire\Security\InvalidKeyFileException;

/**
 * Zaklad pro sifrovani pomoci klice.
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
abstract class AbstractKeyCrypt implements IKeyCrypt
{

    /** @var  string */
    protected $key;

    /** @var  string */
    protected $password;

    /**
     * Overi, jestli je klic validni.
     *
     * @return bool
     */
    protected function validateKey(): bool
    {
        $resource = false;

        if (false !== strpos($this->key, 'PUBLIC')) {
            $resource = openssl_pkey_get_public($this->key);
        } elseif (false !== strpos($this->key, 'PRIVATE')) {
            $resource = openssl_pkey_get_private($this->key, $this->password);
        }

        if (false === $resource) {
            throw new InvalidKeyFileException('Invalid key or password is not valid.');
        }

        openssl_free_key($resource);

        return true;
    }
}
