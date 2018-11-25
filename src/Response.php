<?php
/**
 * HttpResponse
 * @author Diccon Towns <diccon.towns@gmail.com>
 * @version 1.0
 */

namespace DMCTowns\HTTP;

/**
 * Class to output HTTP Response to user's browser
 * @package ReapitWeb_HTTP
 */
class Response{

	/**
	 * Array of status codes/labels
	 * @var array
	 */
	protected $statusText = array(
		"100" => "Continue",
		"101" => "Switching Protocols",
		"200" => "OK",
		"201" => "Created",
		"202" => "Accepted",
		"203" => "Non-Authoritative Information",
		"204" => "No Content",
		"205" => "Reset Content",
		"206" => "Partial Content",
		"301" => "Moved Permanently",
		"304" => "Not Modified",
		"305" => "Use Proxy",
		"307" => "Temporary Redirect",
		"400" => "Bad Request",
		"401" => "Unauthorized",
		"403" => "Forbidden",
		"404" => "Not Found",
		"405" => "Method Not Allowed",
		"406" => "Not Acceptable",
		"407" => "Proxy Authentication Required",
		"408" => "Request Timeout",
		"409" => "Conflict",
		"410" => "Gone",
		"411" => "Length Required",
		"412" => "Precondition Failed",
		"413" => "Request Entity Too Large",
		"414" => "Request-URI Too Long",
		"415" => "Unsupported Media Type",
		"416" => "Requested Range Not Satisfiable",
		"417" => "Expectation Failed",
		"500" =>  "Internal Server Error",
		"501" => "Not Implemented",
		"502" => "Bad Gateway",
		"503" => "Service Unavailable",
		"505" => "HTTP Version Not Supported"
	);

	/**
	 * HTTP Version
	 * @var string $version
	 */
	protected $version = "1.1";

	/**
	 * Status code
	 * @var integer $statusCode
	 */
	protected $statusCode = 200;

	/**
	 * Content
	 * @var string $content
	 */
	protected $content = null;

	/**
	 * Redirect URL
	 * @var string $redirect
	 */
	protected $redirect = null;

	/**
	 * Headers
	 * @var array $headers
	 */
	protected $headers = array();


	/**
	 * Constructor
	 * @param string status_code
	 * @param string content;
	 */
	public function __construct($statusCode=200,$content=null){
		$this->statusCode = $statusCode;
		$this->content = $content;
	}


	/**
	 * Sets redirect URL
	 *
	 * @param string url
	 */
	public function setRedirect($url){
		$this->redirect = $url;
	}

	/**
	 * Returns redirect URL
	 *
	 * @return string
	 */
	public function getRedirect(){
		return $this->redirect;
	}

	/**
	 * Sets status code
	 *
	 * @param integer code
	 */
	public function setStatusCode($code){
		$code = (integer) $code;

		if ($code < 100 || $code > 599) {
			throw new Exception("Status code $code not recognized.");
		}

		$this->statusCode = $code;
	}

	/**
	 * Gets status code
	 *
	 * @return integer
	 */
	public function getStatusCode(){
		return $this->statusCode;
	}

	/**
	 * Sets content
	 *
	 * @param string content
	 */
	public function setContent($content){
		$this->content = $content;
	}

	/**
	 * Adds header
	 *
	 * @param string header
	 */
	public function addHeader($header){
		$this->headers[] = $header;
	}

	/**
	 * Appends content for output
	 *
	 * @param string content
	 */
	public function appendContent($content){
		$this->content .= $content;
	}
	/**
	 * Returns content
	 *
	 * @return string
	 */
	public function getContent(){
		return $this->content;
	}

	/**
	 * Sends response to browser
	 *
	 * @return void
	 */
	public function send(){
		$this->sendHeaders();
		$this->sendContent();
	}

	/**
	 * Sends headers to the browser
	 *
	 * @return void
	 */
	public function sendHeaders(){

		header("HTTP/".$this->version." ".$this->statusCode." ".$this->statusText[(string)$this->statusCode]);

		foreach($this->headers as $header){
			header($header);
		}

		if($this->redirect){
			header("Location: ".$this->redirect);
			exit;
		}
	}

	/**
	 * Sends content to the browser
	 *
	 * @return void
	 */
	public function sendContent(){
		if($content = $this->getContent()){
		    if (is_resource($content)) {
	            while (! feof($content)) {
	                echo fread($content, 8192);
	            }
	            fclose($content);
	        } else {
	            echo $content;
	        }
		}
	}
}
