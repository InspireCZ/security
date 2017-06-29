<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */


namespace Inspire\Security\Password;


use Inspire\Security\InvalidArgumentException;
use Nette\Security\Passwords;

/**
 * Hash generator vyuzivajici Nettacky Passwords
 * @see Nette\Security\Passwords
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class BCryptPasswordHashGenerator implements IPasswordHashGenerator
{

    /**
     * Vygeneruje hash hesla za pouziti Nette Password
     *
     * @param string $password
     *
     * @return string hash hesla
     */
    public function generate(string $password): string
    {
        return Passwords::hash($password);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool zdali heslo odpovida hashi
     */
    public function verify(string $password, string $hash): bool
    {
        return Passwords::verify($password, $hash);
    }

}
