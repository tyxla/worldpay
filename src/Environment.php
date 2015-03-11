<?php

class Worldpay_Environment {
    /**
     * @var string
     */
    private $env;

    /**
     * Create a new Worldpay_Environment
     *
     * @param string $env
     * @return void
     */
    private function __construct($env) {
        Worldpay_Assertion::string($env);

        $this->env = $env;
    }

    /**
     * Set the Worldpay_Environment
     *
     * @param string $env
     * @return Worldpay_Environment
     */
    public static function set($env) {
        return new Worldpay_Environment($env);
    }

    /**
     * Return the environment as an integer
     *
     * @return int
     */
    public function asInt() {
        if($this->env === 'production') return 0;

        return 100;
    }

    /**
     * Return the Worldpay_Environment when cast to string
     *
     * @return string
     */
    public function __toString() {
        return $this->env;
    }
}
