<?php

namespace {
    /**
     * @param string $singular
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __($singular, $args = null) {
        if (!$singular) {
            return null;
        }

        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 1));
    }

    /**
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __n($singular, $plural, $count, $args = null) {
        if (!$singular) {
            return null;
        }

        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, $plural, null, I18n::LC_MESSAGES, $count);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 3));
    }

    /**
     * @param string $domain
     * @param string $msg
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __d($domain, $msg, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, $domain);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 2));
    }

    /**
     * @param string $domain
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dn($domain, $singular, $plural, $count, $args = null) {
		if (!$singular) {
            return null;
        }
		App::uses('I18n', 'I18n');
		$translated = I18n::translate($singular, $plural, $domain, I18n::LC_MESSAGES, $count);
		$arguments = func_get_args();
		return I18n::insertArgs($translated, array_slice($arguments, 4));
	}

    /**
     * @param string $domain
     * @param string $msg
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dc($domain, $msg, $category, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, $domain, $category);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 3));
    }

    /**
     * @param string $domain
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dcn($domain, $singular, $plural, $count, $category, $args = null) {
        if (!$singular) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, $plural, $domain, $category, $count);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 5));
    }

    /**
     * @param string $msg
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __c($msg, $category, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, null, $category);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 2));
    }

    /**
     * @param string $context
     * @param string $singular
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __x($context, $singular, $args = null) {
        if (!$singular) {
            return null;
        }

        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, null, null, null, null, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 2));
    }

    /**
     * @param string $context
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __xn($context, $singular, $plural, $count, $args = null) {
        if (!$singular) {
            return null;
        }

        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, $plural, null, I18n::LC_MESSAGES, $count, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 4));
    }

    /**
     * @param string $domain
     * @param string $context
     * @param string $msg
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dx($domain, $context, $msg, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, $domain, null, null, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 3));
    }

    /**
     * @param string $domain
     * @param string $context
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dxn($domain, $context, $singular, $plural, $count, $args = null) {
        if (!$singular) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, $plural, $domain, I18n::LC_MESSAGES, $count, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 5));
    }

    /**
     * @param string $domain
     * @param string $context
     * @param string $msg
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dxc($domain, $context, $msg, $category, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, $domain, $category, null, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 4));
    }

    /**
     * @param string $domain
     * @param string $context
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __dxcn($domain, $context, $singular, $plural, $count, $category, $args = null) {
        if (!$singular) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($singular, $plural, $domain, $category, $count, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 6));
    }

    /**
     * @param string $context
     * @param string $msg
     * @param int $category
     * @param string|int|float|null ...$args
     *
     * @return string|null
     */
    function __xc($context, $msg, $category, $args = null) {
        if (!$msg) {
            return null;
        }
        App::uses('I18n', 'I18n');
        $translated = I18n::translate($msg, null, null, $category, null, null, $context);
        $arguments = func_get_args();
        return I18n::insertArgs($translated, array_slice($arguments, 3));
    }

    /**
     * @param mixed $var
     */
    function pr($var)
    {
    }

    /**
     * @param string $key
     *
     * @return string|bool|null
     */
    function env($key)
    {
    }
}
