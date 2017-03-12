<?php declare(strict_types = 1);

/**
 * This file is part of the Netwings (https://www.netwings.cz/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */

namespace Inspire\Security\Password;

use Nette\Utils\Random;


/**
 * Generator nahodnych tokenu/retezcu. Obalka nad Nette Random
 *
 * @see \Nette\Utils\Random
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 */
class RandomTokenGenerator implements ITokenGenerator
{

    /**
     * Vygeneruje nahodny retezec/token o delce $lenght skladajici se ze znaky $charlist
     *
     * @param int $length delka tokenu
     * @param string $charlist znaky ze kterych se token muze skladat
     *
     * @return string
     */
    public function generate(int $length = 32, string $charlist = '0-9a-zA-Z'): string
    {
        return Random::generate($length, $charlist);
    }

}
