<?php
/* 
 * Class for API interaction
 *	 
 */
if(!defined('ABSPATH')) { wp_die('No direct access allowed!'); }

class WpeAPINotices extends WpeAPI
{
	public $notices;
	function __construct($args = array()) {
		parent::__construct($args);
		$this->notices = array();
	}
	function get() {
		$mine = $this->go(WPE_APIKEY);
		$this->add($mine);
		$cluster = $this->go('C'.WPE_CLUSTER_ID);
		$this->add($cluster);
		$g = $this->go('ALL');
		$this->add($g);
		return $this->notices;
	}
	function go($file) {
		$this->request_uri = 'https://messages.wpengine.s3.amazonaws.com/'.$file;
		$response = @file_get_contents($this->request_uri);
		if ( ! $response )
			return false;
		$response = json_decode( trim($response), true );
		if ( NULL === $response )
			return false;
		return $response;
	}
	function add($b) {
		if ( is_array($b) )
			$this->notices = array_merge($this->notices,$b);
	}
}

class WpeAPI extends Wp_Http {
	
	public $request_uri = 'https://api.wpengine.com/1.2/index.php';
	public $args = array();	
	public $resp = '';
	public $is_error;
	public $timeout = 10;

	function __construct($args = array()) {
		
		
		//set some defaults
		$defaults = array(
			'account_name'=> PWP_NAME,
			'wpe_apikey'=> WPE_APIKEY
		);
		
		$this->args = $defaults;

		//merge args passed to class 
		if(!empty($args)) {
			$this->args = array_merge($this->args,$args);
		}

		add_filter('http_request_timeout',array($this,'get_timeout'));

	}
	
	function get_timeout() {
                return $this->timeout;
        }
	
	function setup_request() {
		if(empty($this->args['method'])) {
			return new WP_Error('error',"Please specify a method for this request.");
		} else {
			if(count($this->args) > 0) {
				foreach($this->args as $k=>$v) {
					$this->request_uri = add_query_arg(array($k=>$v),$this->request_uri);
				}
			}
		}
		return null;
	}
	
	function get($args = false) {
		$this->setup_request();
		$this->resp = parent::get($this->request_uri,$args);
		return $this;
	}
	
	function post($args = false) {
		$this->setup_request();
		$this->resp = parent::post($this->request_uri,$args);
		return $this;
	}
	
	function set_arg($arg,$value) {
		$this->args[$arg] = $value;
		return null;
	}
	
	function get_arg($arg) {
		if(!empty($this->args[$arg])) {
			return $this->args[$arg];
		} else {
			return false;
		}
	}
	
	function message() {
		$array = json_decode($this->resp['body']); 
		return $array->error_msg;
	}
	
	function set_notice($notice = null) {
		if(!empty($notice)) { $this->resp = new WP_Error('error',$notice); }
		if(is_network_admin()) {
			add_action('network_admin_notices',array($this,'render_notice'));
		} else {
			add_action('admin_notices',array($this,'render_notice'));
		}
	}
		
	function render_notice() {
		if(!is_wp_error($this->resp)) {		
			$notice = json_decode($this->resp['body']); 
			if($this->is_error OR $this->is_error() ) {
				$notice = array('code'=> $notice->error_code,'message'=>$notice->error_msg);
			} else {
				$notice = array('code'=>'updated','message'=>$notice->message);
			}?>
			<div id="message" class="<?php echo $notice['code']; ?>"><p><?php echo $notice['message']; ?></p></div>
			<?php
		} else {
			?><div id="message" class="error"><p><?php echo $this->resp->get_error_message(); ?></p></div>
			<?php
		}
	}
	
	function is_error() {
		if(!is_wp_error($this->resp)) {
			$error = $this->resp['body'];
			$error = json_decode($error);
			if(@$error->error_code == 'error') {
				return $error->error_msg;
				$this->is_error = 1;
			} else {
				return false;
			}
		} else {
			return $this->resp->get_error_message();
		}
	}
	
	function __destruct() {
		
	}
	
} 
