<?php
	
	namespace Bigcommerce;
	
	use \Guzzle\Service\Client;

	class Webhook{
		CONST BC_API = 'https://api.bigcommerce.com';

		public $path;
		public $client;
		public $account;

		protected function __construct($path){
			$this->account = json_decode(file_get_contents($path));

			// Send a request to register a web hook
			$this->client = new Client(BC_API, array(
			    'request.options' => array(
			        'exceptions' => false,
			        'headers' => array(
			            'X-Auth-Client' => $account->client_id,
			            'X-Auth-Token'  => $account->token,
			            'Content-Type'  => 'application/json',
			            'Accept'        => 'application/json',
			        )
			    )
			));
		}

		/**
		 * Instantiate an object
		 * @param  string $path path of the JSON file of account example format {"client_id":"", "client_secret":"", "toke":""}
		 * @return object       object of type OAuth
		 */

		public static function init($path){
			$obj = new static($path);
			return $obj;
		}

		public function create($config){
		    // 'scope'			=> 'store/customer/*',
		    // 'is_active' 		=> true,
		    // 'destination'	=> 'https://blog.naturalchemist.com.au/bc-webhooks/event/customers-created.php',

			$request = $https->post('/stores/' . $account->context .'/v2/hooks', null, json_encode($config));
			$response = $request->send();

			return $response;
		}

		public function destroy($id){
			$request = $https->delete('/stores/' . $account->context .'/v2/hooks/' . $id, null);
			$response = $request->send();

			return $response;
		}

		public function list(){
			$request = $https->get('/stores/' . $account->context .'/v2/hooks', null);

			return $response;
		}

		public function get($id){
			$request = $https->get('/stores/' . $account->context .'/v2/hooks/' . $id, null);

			return $response;
		}
	}