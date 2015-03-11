<?php

class Worldpay_Currencies {

	/**
	 * Get a list of all available currencies.
	 *
	 * @return array List of the available currencies.
	 */
	public static function get() {
		return array(
			'ARS' => 'Nuevo Argentine Peso',
			'AUD' => 'Australian Dollar',
			'BRL' => 'Brazilian Real',
			'CAD' => 'Canadian Dollar',
			'CHF' => 'Swiss Franc',
			'CLP' => 'Chilean Peso',
			'CNY' => 'Yuan Renminbi',
			'COP' => 'Colombian Peso',
			'CZK' => 'Czech Koruna',
			'DKK' => 'Danish Krone',
			'EUR' => 'Euro',
			'GBP' => 'Pound Sterling',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungarian Forint',
			'IDR' => 'Indonesian Rupiah',
			'JPY' => 'Japanese Yen',
			'KES' => 'Kenyan Shilling',
			'KRW' => 'South-Korean Won',
			'MXP' => 'Mexican Peso',
			'MYR' => 'Malaysian Ringgit',
			'NOK' => 'Norwegian Krone',
			'NZD' => 'New Zealand Dollar',
			'PHP' => 'Philippine Peso',
			'PLN' => 'New Polish Zloty',
			'PTE' => 'Portugese Escudo',
			'SEK' => 'Swedish Krone',
			'SGD' => 'Singapore Dollar',
			'SKK' => 'Slovak Koruna',
			'THB' => 'Thai Baht',
			'TWD' => 'New Taiwan Dollar',
			'USD' => 'US Dollars',
			'VND' => 'Vietnamese New Dong',
			'ZAR' => 'South African Rand'
		);
	}

}