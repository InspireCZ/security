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

use Inspire\Security\InvalidArgumentException;
use Inspire\Security\InvalidKeyFileException;

/**
 * Sifrovani pomoci privatniho klice.
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class OpenSSLPrivateKeyCrypt extends AbstractKeyCrypt implements IKeyCrypt
{

    /**
     * OpenSSLPrivateKeyCrypt constructor.
     *
     * @param string $privateKey
     * @param string $password
     */
    public function __construct(string $privateKey, string $password = '')
    {
        $this->key = $privateKey;
        $this->password = $password;
        $this->validateKey();
    }

    /**
     * Vytvori novou instanci sifrovani z klice ulozeneho v souboru.
     *
     * @param string $keyFilePath
     * @param string $password
     *
     * @return OpenSSLPrivateKeyCrypt
     */
    public static function fromFile(string $keyFilePath, string $password = ''): IKeyCrypt
    {
        if (!is_readable($keyFilePath)) {
            throw new InvalidArgumentException(sprintf('Key file  "%s" is not readable', $keyFilePath));
        }

        $key = file_get_contents($keyFilePath);

        return new static($key, $password);
    }

    /**
     * Zasifruje data pomoci privatniho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt(string $data): string
    {
        $keyResource = openssl_get_privatekey($this->key, $this->password);
        openssl_private_encrypt($data, $result, $keyResource);
        $result = base64_encode($result);

        return $result;
    }

    /**
     * Rozsifruje data pomoci privatniho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function decrypt(string $data): string
    {
        $data = base64_decode($data);
        $keyResource = openssl_get_privatekey($this->key, $this->password);
        openssl_private_decrypt($data, $result, $keyResource);

        if (null === $result) {
            throw new InvalidKeyFileException('Can not decrypt data by this private key.');
        }

        return $result;
    }
}
