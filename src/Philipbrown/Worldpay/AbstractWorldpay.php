<?php namespace PhilipBrown\WorldPay;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractWorldPay {

  /**
   * @var Symfony\Component\HttpFoundation\ParameterBag;
   */
  protected $parameters;

  /**
   * Create object with new instance of ParameterBag
   */
  public function __construct()
  {
    $this->parameters = new ParameterBag;
  }

  /**
   * Is Custom Environment
   *
   * @return bool
   */
  protected function isCustomEnv()
  {
    if($this->config['env'] !== 'development' && $this->config['env'] !== 'production')
    {
      return true;
    }

    return false;
  }

  /**
   * Initialise the object with parameters
   *
   * Set the default parameters first and then set the
   * user given parameters second to allow for overrides
   *
   * @param $properties array
   */
  public function initialise($parameters)
  {
    foreach($this->getDefaultParameters() as $param)
    {
      $this->setParameter($param, null);
    }

    foreach($parameters as $key => $value)
    {
      $this->setParameter($key, $value);
    }
  }

  /**
   * Determines if this key has a setter or getter method
   *
   * @param string $type ('get'|'set')
   * @param string $key
   * @return bool
   */
  protected function hasMethod($type = 'get', $key)
  {
    return method_exists($this, Helper::convertParamMethodName($type, $key));
  }

  /**
   * Is Custom Param?
   *
   * Determines if this is a custom parameter
   *
   * Custom parameters begin with 'MC_' or 'C_'
   *
   * @param string $key
   * @return bool
   */
  protected function isCustomParam($key)
  {
    if(substr($key, 0, 3) == 'MC_' || substr($key, 0, 3) == 'CM_')
    {
      return true;
    }
    return false;
  }

  /**
   * Set Parameter
   *
   * Set the parameter if it has a setter method
   * or if it is a Custom parameter.
   *
   * @param string $key
   * @param mixed $value
   * @return void
   */
  protected function setParameter($key, $value)
  {
    if($this->hasMethod('set', $key))
    {
      $method = Helper::convertParamMethodName('set', $key);
      $this->{$method}($value);
    }
    if($this->isCustomParam($key))
    {
      $this->setCustomParam($key, $value);
    }
  }

  /**
   * Get Parameter
   *
   * Get the parameter if it has a getter method
   * or if it is a Custom parameter
   *
   * @param string $key
   * @return string
   */
  protected function getParameter($key)
  {
    if($this->hasMethod('get', $key))
    {
      $method = Helper::convertParamMethodName('get', $key);
      return $this->{$method}();
    }
    if($this->isCustomParam($key))
    {
      return $this->getCustomParam($key);
    }
  }

  /**
   * Set Custom Param
   *
   * Called when an MC_ or C_ parameter is set
   *
   * @param string $key
   * @param string $value
   * @return void
   */
  protected function setCustomParam($key, $value)
  {
    $this->parameters->set($key, $value);
  }

  /**
   * Get Custom Param
   *
   * Called when a MC_ or C_ parameter is requested
   *
   * @param string $key
   * @return string
   */
  protected function getCustomParam($key)
  {
    return $this->parameters->get($key);
  }

  /**
   * Set Inst Id Parameter
   *
   * Your WorldPay Inst Id
   *
   * @param string $value
   * @return void
   */
  protected function setInstIdParameter($value)
  {
    $this->parameters->set('instId', $value);
  }

  /**
   * Set Cart Id Parameter
   *
   * Merchant reference
   *
   * @param string $value
   * @return void
   */
  protected function setCartIdParameter($value)
  {
    $this->parameters->set('cartId', $value);
  }

  /**
   * Set Amount Parameter
   *
   * Should be set to 0 unless there is an immediate payment
   *
   * @param decimal $value
   * @return void
   */
  protected function setAmountParameter($value)
  {
    $this->parameters->set('amount', $value);
  }

  /**
   * Set Currency Parameter
   *
   * Currency for the amounts specified in the agreement
   * and immediate payment if present
   *
   * @param string $value
   * @return void
   */
  protected function setCurrencyParameter($value)
  {
    $this->parameters->set('currency', $value);
  }

  /**
   * Set Name Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setNameParameter($value)
  {
    $this->parameters->set('name', $value);
  }

  /**
   * Set Town Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setTownParameter($value)
  {
    $this->parameters->set('town', $value);
  }

  /**
   * Set Postcode Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setPostcodeParameter($value)
  {
    $this->parameters->set('postcode', $value);
  }

  /**
   * Set Country Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setCountryParameter($value)
  {
    $this->parameters->set('country', $value);
  }

  /**
   * Set Email Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setEmailParameter($value)
  {
    $this->parameters->set('email', $value);
  }

  /**
   * Set Fax Parameter
   *
   * @param string $value
   * @return void
   */
  protected function setFaxParameter($value)
  {
    $this->parameters->set('fax', $value);
  }

  /**
   * Dynamically set attributes on the object
   *
   * @param string $key
   * @param string $value
   * @return void
   */
  public function __set($key, $value)
  {
    $this->setParameter($key, $value);
  }

  /**
   * Dynamically get attributes on the object
   *
   * @param string $key
   * @return string
   */
  public function __get($key)
  {
    return $this->getParameter($key);
  }

}
