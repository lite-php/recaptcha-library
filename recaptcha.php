<?php
/**
 * LightPHP Framework
 * LitePHP is a framework that has been designed to be lite waight, extensible and fast.
 * 
 * @author Robert Pitt <robertpitt1988@gmail.com>
 * @category core
 * @copyright 2013 Robert Pitt
 * @license GPL v3 - GNU Public License v3
 * @version 1.0.0
 */

/**
 * Recaptcha class
 */
class ReCAPTCHA_Library
{
	/**
	 * API Server endpoint
	 */
	const API_SERVER = "//www.google.com/recaptcha/api";

	/**
	 * Varifiy Server
	 */
	const VARIFIY_SERVER = "www.google.com";

	/**
	 * Public Key used for authentication to reCAPTCHA.
	 * @see https://www.google.com/recaptcha/admin/create
	 * @var string
	 */
	protected $public_key = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Try and load the config
		 */
		try
		{
			$this->public_key = Registry::get("ConfigLoader")->recaptcha->public_key;
		}catch(Exception $e)
		{}
	}

	/**
	 * Set the public key
	 * @param  string $key Key used for authentication
	 */
	public function setPublicKey($key)
	{
		$this->public_key = $key;
	}

	/**
	 * Generate a html fragment for displaying the reCAPTCHA widget
	 */
	public function generate($error = null)
	{
		/**
		 * Create the params
		 */
		$params = array('k' => $this->public_key);

		/**
		 * Check to see if we have an error
		 */
		if($error)
		{
			$params['error'] = $error;
		}
		/**
		 * If we have an error, we need to append that to our url
		 */
		$curl = self::API_SERVER . '/challange?' . http_build_query($params, '', '&amp;');

		/**
		 * Create the noscript
		 */
		$nurl = self::API_SERVER . '/noscript?' . http_build_query($params, '', '&amp;');

		/**
		 * Start generating the output
		 */
		$output = '<script type="text/javascript" src="' . $curl . '"></script>';

		/**
		 * Extend the output with a noscript
		 */
		$output .= '<noscript>';
		$output .= '<iframe src="' . $nurl . '" height="300" width="500" frameborder="0"></iframe>';
		$output .= '<br/>'; //< not even sure why google do this.
  		$output .= '<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>';
  		$output .= '<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>';
  		$output .= '</noscript>';

  		/**
  		 * Return the response to be outputed
  		 */
  		return $output;
	}
}