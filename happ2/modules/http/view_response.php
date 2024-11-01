<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Http_View_Response_HC_MVC extends _HC_MVC
{
	protected $view = NULL;
	protected $redirect = NULL;
	protected $params = array();
	protected $status_code = NULL;

	public function set_view( $view )
	{
		$this->view = $view;
		return $this;
	}

	public function set_redirect( $redirect )
	{
		$this->redirect = $redirect;
		return $this;
	}

	public function redirect()
	{
		return $this->redirect;
	}

	public function view()
	{
		return $this->view;
	}

	public function set_param( $k, $v )
	{
		$this->params[$k] = $v;
		return $this;
	}
	public function param( $k )
	{
		$return = isset($this->params[$k]) ? $this->params[$k] : NULL;
		return $return;
	}

	public function set_status_code( $status_code )
	{
		$this->status_code = $status_code;
		return $this;
	}

	public function status_code()
	{
		return $this->status_code;
	}

	public function render()
	{
		$code = $this->status_code();
		if( $code ){
			$text = '';

			$stati = array(
				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported'
			);

			if ($code == '' OR ! is_numeric($code)){
				show_error('Status codes must be numeric', 500);
			}

			if (isset($stati[$code]) AND $text == ''){
				$text = $stati[$code];
			}

			if ($text == ''){
				echo 'No status text available.  Please check your status code number or supply your own message text.';
			}

			$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

			if (substr(php_sapi_name(), 0, 3) == 'cgi'){
				header("Status: {$code} {$text}", TRUE);
			}
			elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0'){
				header($server_protocol." {$code} {$text}", TRUE, $code);
			}
			else {
				header("HTTP/1.1 {$code} {$text}", TRUE, $code);
			}
		}

		$view = $this->view();
		$redirect = $this->redirect();
		if( $redirect ){
			if( ! HC_Lib2::is_full_url($redirect) ){
				$uri = $this->make('/http/lib/uri');
				$redirect = $uri->url($redirect);
			}
			if( 1 OR (! headers_sent()) ){
				header('Location: ' . $redirect);
			}
			else {
				$html = "<META http-equiv=\"refresh\" content=\"0;URL=$redirect\">";
				echo $html;
				exit;
			}
		}
		elseif( $view ){
			return $view;
		}
	}
}