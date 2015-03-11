<?php

class Worldpay_Currency {
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private static $currencies;

    /**
     * Create a new Worldpay_Currency
     *
     * @param string $name
     */
    private function __construct($name) {
        if ( ! isset(self::$currencies)) {
            self::$currencies = Worldpay_Currencies::get();
        }

        Worldpay_Assertion::keyExists(self::$currencies, $name);

        $this->name = $name;
    }

    /**
     * Set the Worldpay_Currency
     *
     * @param string $name
     * @return Worldpay_Currency
     */
    public static function set($name) {
        return new Worldpay_Currency($name);
    }

    /**
     * Return the Worldpay_Currency when cast to string
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
