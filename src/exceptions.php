<?php declare(strict_types = 1);

/**
 * This file is part of the Netwings (https://www.netwings.cz/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Inspire\Security;

/**
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
interface IException
{

}

/**
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class InvalidArgumentException extends \InvalidArgumentException implements IException
{

}

/**
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
class InvalidKeyFileException extends \InvalidArgumentException implements IException
{

}