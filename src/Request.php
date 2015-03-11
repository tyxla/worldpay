<?php

class Worldpay_Request {
    /**
     * @var Worldpay_Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $instId;

    /**
     * @var string
     */
    private $cartId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var Worldpay_Currency
     */
    private $currency;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
    */
    private $defaultSignatureFields = array(
        'instId', 
        'cartId', 
        'currency', 
        'amount'
    );

    /**
     * Create a new Worldpay_Request
     *
     * @param Worldpay_Environment $environment
     * @param string $instId
     * @param string $cartId
     * @param string $secret
     * @param float $amount
     * @param Worldpay_Currency $currency
     * @param string $route
     * @param array $parameters
     * @return void
     */
    public function __construct(Worldpay_Environment $environment, $instId, $cartId, $secret, $amount, Worldpay_Currency $currency, $route, array $parameters = array()) {
        $this->environment = $environment;
        $this->instId      = $instId;
        $this->cartId      = $cartId;
        $this->secret      = $secret;
        $this->amount      = $amount;
        $this->currency    = $currency;
        $this->parameters  = $parameters;
        $this->route       = $route;
    }

    /**
     * Set the signature fields to use in the signature hash
     *
     * @param array $fields
     * @return Worldpay_Request
     */
    public function setSignatureFields(array $fields) {
        $this->defaultSignatureFields = array_merge($this->defaultSignatureFields, $fields);

        return $this;
    }

    /**
     * Send the request to WorldPay
     *
     * @return Worldpay_RedirectResponse
     */
    public function send() {
        $request = $this->prepare();

        $url = $request->route . '?signature=' . $request->signature . '&' . http_build_query($request->data);

        return Worldpay_RedirectResponse::create($url)->send();
    }

    /**
     * Return an object containing the request
     *
     * @return Worldpay_Body
     */
    public function prepare() {
        return new Worldpay_Body(
            (string) $this->route,
            $this->generateSignature(),
            $this->getTheRequestParameters()
        );
    }

    /**
     * Generate the signature
     *
     * @return string
     */
    private function generateSignature() {
        $defaults = array(
            'instId'    => $this->instId,
            'cartId'    => $this->cartId,
            'currency'  => $this->currency,
            'amount'    => $this->amount
        );

        $parameters = array_intersect_key($this->parameters, array_flip($this->defaultSignatureFields));

        return md5((string) $this->secret.':'.implode(':', array_merge($defaults, $parameters)));
    }

    /**
     * Get the request parameters
     *
     * @return array
     */
    private function getTheRequestParameters() {
        return array_merge(array(
            'instId'    => $this->instId,
            'cartId'    => $this->cartId,
            'currency'  => (string) $this->currency,
            'amount'    => $this->amount,
            'testMode'  => $this->environment->asInt()
        ), $this->parameters);
    }
}
