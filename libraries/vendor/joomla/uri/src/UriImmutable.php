<?php

/**
 * Part of the Joomla Framework Uri Package
 *
 * @copyright  Copyright (C) 2005 - 2022 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Uri;

/**
 * UriImmutable Class
 *
 * This is an immutable version of the AbstractUri class.
 *
 * @since  1.0
 */
final class UriImmutable extends AbstractUri
{
    /**
     * Flag if the class been instantiated
     *
     * @var    boolean
     * @since  1.0
     */
    private $constructed = false;

    /**
     * Prevent setting undeclared properties.
     *
     * @param   string  $name   This is an immutable object, setting $name is not allowed.
     * @param   mixed   $value  This is an immutable object, setting $value is not allowed.
     *
     * @return  void  This method always throws an exception.
     *
     * @since   1.0
     * @throws  \BadMethodCallException
     */
    public function __set($name, $value)
    {
        throw new \BadMethodCallException('This is an immutable object');
    }

    /**
     * This is a special constructor that prevents calling the __construct method again.
     *
     * @param   string  $uri  The optional URI string
     *
     * @since   1.0
     * @throws  \BadMethodCallException
     */
    public function __construct($uri = null)
    {
        if ($this->constructed === true) {
            throw new \BadMethodCallException('This is an immutable object');
        }

        $this->constructed = true;

        parent::__construct($uri);
    }
}
