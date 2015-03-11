<?php
/**
 * Skinny, lightweight version of Symfony\Component\HttpFoundation\RedirectResponse
 */
class Worldpay_RedirectResponse {

	/**
	 * @var int
	 */
	protected $version = 1.0;

	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * @var int
	 */
	protected $status = 302;

	/**
	 * @var array
	 */
	protected $headers = array();

	/**
	 * @var string
	 */
	protected $content = '';

	/**
	 * @var string
	 */
	protected $response = '';

	/**
	 * Status codes translation table.
	 *
	 * The list of codes is complete according to the
	 * {@link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry}
	 * (last updated 2012-02-13).
	 *
	 * Unless otherwise noted, the status code is defined in RFC2616.
	 *
	 * @var array
	 */
	public static $status_texts = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',            // RFC2518
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',          // RFC4918
		208 => 'Already Reported',      // RFC5842
		226 => 'IM Used',               // RFC3229
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',    // RFC7238
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',                                               // RFC2324
		422 => 'Unprocessable Entity',                                        // RFC4918
		423 => 'Locked',                                                      // RFC4918
		424 => 'Failed Dependency',                                           // RFC4918
		425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
		426 => 'Upgrade Required',                                            // RFC2817
		428 => 'Precondition Required',                                       // RFC6585
		429 => 'Too Many Requests',                                           // RFC6585
		431 => 'Request Header Fields Too Large',                             // RFC6585
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
		507 => 'Insufficient Storage',                                        // RFC4918
		508 => 'Loop Detected',                                               // RFC5842
		510 => 'Not Extended',                                                // RFC2774
		511 => 'Network Authentication Required',                             // RFC6585
	);

	/**
	 * Creates a redirect response so that it conforms to the rules defined for a redirect status code.
	 * @param string  $url     
	 * @param integer $status  
	 * @param array   $headers 
	 */
	public function __construct($url, $status = 302, $headers = array(), $content = '') {
		$this->url = $url;
		$this->status = $status;
		$this->headers = $headers;
		$this->content = $content;
	}

	/**
	 * Factory method for chainability.
	 * @param  string  $url     
	 * @param  integer $status  
	 * @param  array   $headers 
	 * @return Worldpay_RedirectResponse       
	 */
	public static function create($url, $status = 302, $headers = array(), $content = '') {
		$redirect_response = new self($url, $status, $headers, $content);
		return $redirect_response;
	}

	/**
	 * Sends HTTP headers and content.
	 * @return Worldpay_RedirectResponse
	 */
	public static function send() {
		// headers have already been sent by the developer
		if (headers_sent()) {
			return $this;
		}

		// status
		header(sprintf('HTTP/%s %s %s', $this->version, $this->status, $status_texts[$this->status]), true, $this->status);

		// headers
		foreach ($this->headers as $name => $values) {
			foreach ($values as $value) {
				header($name . ': ' . $value, false, $this->status);
			}
		}

		// send content
		echo $this->content;

		return $this;
	}

}