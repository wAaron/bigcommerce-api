<?php
	
	namespace Bigcommerce;
	
	use \Guzzle\Service\Client;

	class OAuth{
		CONST BC_LOGIN = 'https://login.bigcommerce.com';

		public $path;
		public $client_id;
		public $client_secret;
		public $access_token;

		protected function __construct($path){
			$this->path = $path;
			$account = json_decode(file_get_contents($path));

			$this->client_id = $account->client_id;
	        $this->client_secret = $account->client_secret;
	        $this->access_token = $account->token;
		}

		/**
		 * Instantiate an object
		 * @param  string $path path of the JSON file of account example format {"client_id":"", "client_secret":"", "token":"", "context":""}
		 * @return object       object of type OAuth
		 */
		
		public static function create($path){
			$obj = new static($path);
			return $obj;
		}

		public function oauth($config){
			$account = json_decode(file_get_contents($this->path));

 			$payload = array(
				"client_id" => $account->client_id,
		        "client_secret" => $account->client_secret,
		        "redirect_uri" => $config['redirect_uri'],
		        "grant_type" => "authorization_code",
		        "code" => $config['code'],
			    "scope" => $config['scope'],
			    "context" => $config['context']
			);

		 	$client = new Client(BC_LOGIN);

			$request = $client->post('/oauth2/token', array(), $payload, array(
				'exceptions' => false,
			));

			$response = $request->send();

			if ($response->getStatusCode() == 200) {
				$data = $response->json();
				$token = $data['access_token'];
				$account->context = explode('/', $data["context"])[1];
		    	$account->token = $token;

		    	file_put_contents($path, json_encode($account));
			} else {
				echo 'Something went wrong... [' . $response->getStatusCode() . '] ' . $response->getBody();
			}
		}

		public function load($config){
			$data = $this->verifySignedRequest($config['signed_payload']);
			$this->redirect('../index.php');
		}

		private function verifySignedRequest($signedRequest){
			list($encodedData, $encodedSignature) = explode('.', $signedRequest, 2);
			// decode the data
			$signature = base64_decode($encodedSignature);
			$jsonStr = base64_decode($encodedData);
			$data = json_decode($jsonStr, true);

			return $data;
		}

		private function redirect($uri){
			header('Location: ' . $uri);
		}
	}