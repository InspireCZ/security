<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Crypt;

use Inspire\Security\InvalidArgumentException;

/**
 * Symetricke sifrovani textu pomoci klice.
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class OpenSSLSymetricEncoderDecoder implements ISymetricEncoderDecoder
{

    /** Pouzivana sifra */
    const CIPHER = 'AES-256-CTR';

    /** Pozadovana delka klice (256 bitu) */
    const REQUIRED_KEY_LENGTH = 32;

    /** @var string Klic pro zasifrovani */
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $this->prepareKey($key);
    }

    /**
     * Pripravi klic pro sifrovani.
     *
     * Pouzivame sifru AES-256-CTR, klic by melo mit 256 bitu
     *
     * @param string $key
     *
     * @return string
     */
    private function prepareKey(string $key): string
    {
        if (self::REQUIRED_KEY_LENGTH !== strlen($key)) {
            throw new InvalidArgumentException('Encryption key must be ' . self::REQUIRED_KEY_LENGTH . ' bytes long.');
        }

        return $key;
    }

    /**
     * Zasifruje data pomoci klice.
     *
     * @param string $plaintext
     *
     * @return string
     */
    public function encode(string $plaintext): string
    {
        $ivSize = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $ciphertext = openssl_encrypt($plaintext, self::CIPHER, $this->key, OPENSSL_RAW_DATA, $iv);
        $ciphertext = $iv.$ciphertext;
        $ciphertext = base64_encode($ciphertext);

        return $ciphertext;
    }

    /**
     * Rozsifruje data pomoci klice.
     *
     * @param string $ciphertext
     *
     * @return string
     */
    public function decode(string $ciphertext): string
    {
        $ciphertext = base64_decode($ciphertext);

        $ivSize = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($ciphertext, 0, $ivSize);

        if (strlen($iv) < $ivSize) {
            throw new InvalidArgumentException('Initialization vector must be ' . $ivSize . ' bytes long.');
        }

        $ciphertext = substr($ciphertext, $ivSize);

        $plaintext = openssl_decrypt($ciphertext, self::CIPHER, $this->key, OPENSSL_RAW_DATA, $iv);

        return $plaintext;
    }

}
