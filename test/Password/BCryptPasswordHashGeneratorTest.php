<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */


namespace Inspire\NW7\Test\Security\Password;

use Inspire\Security\Password\BCryptPasswordHashGenerator;
use PHPUnit\Framework\TestCase;

/**
 * BCryptPasswordHashGeneratorTest
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class BCryptPasswordHashGeneratorTest extends TestCase
{

    public function testGenerate()
    {
        $generator = new BCryptPasswordHashGenerator();
        $this->assertSame(60, strlen($generator->generate('foobar')));
    }

    public function testVerify()
    {
        $generator = new BCryptPasswordHashGenerator();

        $plaintext = 'foobar';
        $hash = $generator->generate($plaintext);
        $this->assertTrue($generator->verify($plaintext, $hash));
        $this->assertFalse($generator->verify('baz', $hash));
    }
}
