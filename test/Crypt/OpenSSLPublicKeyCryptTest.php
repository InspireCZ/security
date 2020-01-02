<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */

namespace Inspire\Security\Test\Crypt;

use Inspire\Security\Crypt\OpenSSLPrivateKeyCrypt;
use Inspire\Security\Crypt\OpenSSLPublicKeyCrypt;
use PHPUnit\Framework\TestCase;

/**
 * OpenSSLPublicKeyCryptTest
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class OpenSSLPublicKeyCryptTest extends TestCase
{

    const NON_SENSE_KEY_PATH = __DIR__ . '/non-sense.pem';

    const VALID_PUBLIC_KEY_PATH = __DIR__ . '/public_key_valid.pem';
    const INVALID_PUBLIC_KEY_PATH = __DIR__ . '/public_key_invalid.pem';

    const VALID_PRIVATE_KEY_PATH = __DIR__ . '/private_key_valid.pem';

    const VALID_PUBLIC_KEY_PASSWORD_PATH = __DIR__ . '/public_key_password.pem';
    const VALID_PRIVATE_KEY_PASSWORD_PATH = __DIR__ . '/private_key_password.pem';

    public function testConstructInvalidPath()
    {
        self::expectException(\Inspire\Security\InvalidArgumentException::class);
        OpenSSLPublicKeyCrypt::fromFile(self::NON_SENSE_KEY_PATH);
    }

    public function testConstructInvalidPublicPem()
    {
        self::expectException(\Inspire\Security\InvalidKeyFileException::class);
        OpenSSLPublicKeyCrypt::fromFile(self::INVALID_PUBLIC_KEY_PATH);
    }

    public function testConstructString()
    {
        self::expectException(\Inspire\Security\InvalidKeyFileException::class);
        new OpenSSLPublicKeyCrypt('key in string');
    }

    public function testEncryptPublicByPublic()
    {
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PATH);
        $plaintext = 'foo';
        self::expectException(\Inspire\Security\InvalidKeyFileException::class);
        $cryptedtext = $cryptPublic->encrypt($plaintext);
        $cryptPublic->decrypt($cryptedtext);
    }

    public function testEncrypt()
    {
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PATH);
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPublic->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPrivate->decrypt($cryptedtext));
    }

    public function testEncryptPassword()
    {
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PASSWORD_PATH);
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'inspire');

        $plaintext = 'foo';

        $cryptedtext = $cryptPublic->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPrivate->decrypt($cryptedtext));
    }

    public function testDecrypt()
    {
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PATH);
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPrivate->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPublic->decrypt($cryptedtext));
    }

    public function testDecryptPassword()
    {
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PASSWORD_PATH);
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'inspire');

        $plaintext = 'foo';

        $cryptedtext = $cryptPrivate->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPublic->decrypt($cryptedtext));
    }
}
