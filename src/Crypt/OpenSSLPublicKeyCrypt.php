<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */


namespace Inspire\Security\Crypt;

use Inspire\Security\InvalidArgumentException;
use Inspire\Security\InvalidKeyFileException;

/**
 * Sifrovani pomoci verejneho klice.
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class OpenSSLPublicKeyCrypt extends AbstractKeyCrypt implements IKeyCrypt
{

    /**
     * OpenSSLPublicKeyCrypt constructor.
     *
     * @param string $publicKey
     */
    public function __construct(string $publicKey)
    {
        $this->key = $publicKey;
        $this->validateKey();
    }

    /**
     * Vytvori novou instanci sifrovani z klice ulozeneho v souboru.
     *
     * @param string $keyFilePath
     *
     * @return OpenSSLPublicKeyCrypt
     */
    public static function fromFile(string $keyFilePath): IKeyCrypt
    {
        if (!is_readable($keyFilePath)) {
            throw new InvalidArgumentException(sprintf('Key file  "%s" is not readable', $keyFilePath));
        }

        $key = file_get_contents($keyFilePath);

        return new static($key);
    }

    /**
     * Zasifruje data pomoci verejneho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt(string $data): string
    {
        $keyResource = openssl_get_publickey($this->key);
        openssl_public_encrypt($data, $result, $keyResource);
        $result = base64_encode($result);

        return $result;
    }

    /**
     * Rozsifruje data pomoci verejneho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function decrypt(string $data): string
    {
        $data = base64_decode($data);
        $keyResource = openssl_get_publickey($this->key);
        openssl_public_decrypt($data, $result, $keyResource);

        if (null === $result) {
            throw new InvalidKeyFileException('Can not decrypt data by this public key.');
        }

        return $result;
    }
}
