<?php declare(strict_types = 1);

/**
 * This file is part of the Netwings (https://www.netwings.cz/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */

namespace Inspire\Security\Test\Password;


use Inspire\Security\Password\RandomTokenGenerator;
use PHPUnit\Framework\TestCase;

/**
 * RandomTokenGeneratorTest
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 */
class RandomTokenGeneratorTest extends TestCase
{

    public function testGenerate()
    {
        $generator = new RandomTokenGenerator();
        $this->assertSame(32, strlen($generator->generate()));
        $this->assertSame(1, strlen($generator->generate(1)));
        $this->assertSame(20, strlen($generator->generate(20)));

        $pwd = $generator->generate(10, '0-9');
        $this->assertTrue(is_numeric($pwd));
        $this->assertSame(10, strlen($pwd));

        $pwd = $generator->generate(10, 'a-z');
        $this->assertSame(1, preg_match('~^[a-z]{10}$~', $pwd));
    }

    /**
     * @expectedException \Nette\InvalidArgumentException
     */
    public function testGenerateZero()
    {
        $generator = new RandomTokenGenerator();
        $this->assertSame(0, strlen($generator->generate(0)));
    }

}
