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
use Inspire\Security\InvalidArgumentException;
use Inspire\Security\InvalidKeyFileException;
use PHPUnit\Framework\TestCase;

/**
 * OpenSSLPrivateKeyCryptTest
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class OpenSSLPrivateKeyCryptTest extends TestCase
{

    const NON_SENSE_KEY_PATH = __DIR__ . '/non-sense.pem';

    const VALID_PUBLIC_KEY_PATH = __DIR__ . '/public_key_valid.pem';

    const VALID_PRIVATE_KEY_PATH = __DIR__ . '/private_key_valid.pem';
    const INVALID_PRIVATE_KEY_PATH = __DIR__ . '/private_key_invalid.pem';

    const VALID_PUBLIC_KEY_PASSWORD_PATH = __DIR__ . '/public_key_password.pem';
    const VALID_PRIVATE_KEY_PASSWORD_PATH = __DIR__ . '/private_key_password.pem';

    public function testConstructInvalidPath()
    {
        self::expectException(\Inspire\Security\InvalidArgumentException::class);
        OpenSSLPrivateKeyCrypt::fromFile(self::NON_SENSE_KEY_PATH);
    }

    public function testConstructInvalidPrivatePem()
    {
        self::expectException(\Inspire\Security\InvalidKeyFileException::class);
        OpenSSLPrivateKeyCrypt::fromFile(self::INVALID_PRIVATE_KEY_PATH);
    }

    public function testConstructInvalidPassword()
    {
        $this->expectException(InvalidKeyFileException::class);
        OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'invalidPassword');
    }

    public function testConstructValidPassword()
    {
        $key = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'inspire');

        self::assertInstanceOf(OpenSSLPrivateKeyCrypt::class, $key);
    }

    public function testConstructString()
    {
        self::expectException(\Inspire\Security\InvalidKeyFileException::class);

        new OpenSSLPrivateKeyCrypt('key in string');
    }

    public function testEncrypt()
    {
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PATH);
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPrivate->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPublic->decrypt($cryptedtext));
    }

    public function testEncryptPassword()
    {
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'inspire');
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PASSWORD_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPrivate->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPublic->decrypt($cryptedtext));
    }

    public function testEncryptPrivatePrivate()
    {
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PATH);
        $plaintext = 'foo';

        self::expectException(\Inspire\Security\InvalidKeyFileException::class);

        $cryptedtext = $cryptPrivate->encrypt($plaintext);
        $cryptPrivate->decrypt($cryptedtext);
    }

    public function testDecrypt()
    {
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PATH);
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPublic->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPrivate->decrypt($cryptedtext));
    }

    public function testDecryptPassword()
    {
        $cryptPrivate = OpenSSLPrivateKeyCrypt::fromFile(self::VALID_PRIVATE_KEY_PASSWORD_PATH, 'inspire');
        $cryptPublic = OpenSSLPublicKeyCrypt::fromFile(self::VALID_PUBLIC_KEY_PASSWORD_PATH);

        $plaintext = 'foo';

        $cryptedtext = $cryptPublic->encrypt($plaintext);

        self::assertEquals($plaintext, $cryptPrivate->decrypt($cryptedtext));
    }
}
