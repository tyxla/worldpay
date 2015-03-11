<?php
/*
 * A skinny, lightweight version of the Assert library (https://github.com/beberlei/assert/).
 */
class Worldpay_Assertion {
	const INVALID_STRING 			= 16;
	const INVALID_ARRAY             = 24;
	const INVALID_KEY_EXISTS        = 26;
	const INVALID_URL 				= 203;

	/**
	 * Exception to throw when an assertion failed.
	 *
	 * @var string
	 */
	static protected $exceptionClass = 'Worldpay_InvalidRequestException';

	/**
	 * Helper method that handles building the assertion failure exceptions.
	 * They are returned from this method so that the stack trace still shows
	 * the assertions method.
	 */
	protected static function createException($value, $message, $code, $propertyPath, array $constraints = array()) {
		$exceptionClass = self::$exceptionClass;
		return new $exceptionClass($message, $code, $propertyPath, $value, $constraints);
	}

	/**
	 * Assert that value is an URL.
	 *
	 * This code snipped was taken from the Symfony project and modified to the special demands of this method.
	 *
	 * @param mixed $value
	 * @param string|null $message
	 * @param string|null $propertyPath
	 * @return void
	 * @throws Worldpay_InvalidRequestException
	 *
	 *
	 * @link https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php
	 * @link https://github.com/symfony/Validator/blob/master/Constraints/Url.php
	 */
	public static function url($value, $message = null, $propertyPath = null) {
		self::string($value, $message, $propertyPath);

		$protocols = array('http', 'https');

		$pattern = '~^
			(%s)://                                 # protocol
			(
				([\pL\pN\pS-]+\.)+[\pL]+                   # a domain name
					|                                     #  or
				\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}      # a IP address
					|                                     #  or
				\[
					(?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
				\]  # a IPv6 address
			)
			(:[0-9]+)?                              # a port (optional)
			(/?|/\S+)                               # a /, nothing or a / with something
		$~ixu';

		$pattern = sprintf($pattern, implode('|', $protocols));

		if (!preg_match($pattern, $value)) {
			$message = sprintf(
				$message ?: 'Value "%s" was expected to be a valid URL starting with http or https',
				self::stringify($value)
			);

			throw self::createException($value, $message, self::INVALID_URL, $propertyPath);
		}

	}

    /**
     * Assert that value is an array.
     *
     * @param mixed $value
     * @param string|null $message
     * @param string|null $propertyPath
     * @return void
     * @throws Worldpay_InvalidRequestException
     */
    public static function isArray($value, $message = null, $propertyPath = null) {
        if ( ! is_array($value)) {
            $message = sprintf(
                $message ?: 'Value "%s" is not an array.',
                self::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_ARRAY, $propertyPath);
        }
    }

    /**
     * Assert that key exists in an array
     *
     * @param mixed $value
     * @param string|integer $key
     * @param string|null $message
     * @param string|null $propertyPath
     * @return void
     * @throws Worldpay_InvalidRequestException
     */
    public static function keyExists($value, $key, $message = null, $propertyPath = null) {
        static::isArray($value, $message, $propertyPath);

        if ( ! array_key_exists($key, $value)) {
            $message = sprintf(
                $message ?: 'Array does not contain an element with key "%s"',
                self::stringify($key)
            );

            throw static::createException($value, $message, static::INVALID_KEY_EXISTS, $propertyPath, array('key' => $key));
        }
    }

    /**
     * Assert that value is a string
     *
     * @param mixed $value
     * @param string|null $message
     * @param string|null $propertyPath
     * @return void
     * @throws Worldpay_InvalidRequestException
     */
    public static function string($value, $message = null, $propertyPath = null) {
        if ( ! is_string($value)) {
            $message = sprintf(
                $message ?: 'Value "%s" expected to be string, type %s given.',
                self::stringify($value),
                gettype($value)
            );

            throw self::createException($value, $message, self::INVALID_STRING, $propertyPath);
        }
    }

	/**
	 * Make a string version of a value.
	 *
	 * @param mixed $value
	 * @return string
	 */
	private static function stringify($value) {
		if (is_bool($value)) {
			return $value ? '<TRUE>' : '<FALSE>';
		}

		if (is_scalar($value)) {
			$val = (string)$value;

			if (strlen($val) > 100) {
				$val = substr($val, 0, 97) . '...';
			}

			return $val;
		}

		if (is_array($value)) {
			return '<ARRAY>';
		}

		if (is_object($value)) {
			return get_class($value);
		}

		if (is_resource($value)) {
			return '<RESOURCE>';
		}

		if ($value === NULL) {
			return '<NULL>';
		}

		return 'unknown';
	}

}