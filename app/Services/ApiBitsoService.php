<?php

namespace App\Services;

class ApiBitsoService
{
	protected $bitsoKey;
	protected $bitsoSecret;
	protected $ticker = null;

	public function __construct()
	{
		$this->bitsoKey    = config('services.bitso.key');
		$this->bitsoSecret = config('services.bitso.secret');
	}

    protected function getBitsoRequest($url, $json = null, $method = "GET")
	{
		$nonce = (integer)round(microtime(true) * 10000 * 100);
		$message = $nonce.$method.$url.$json;
		$signature = hash_hmac('sha256', $message, $this->bitsoSecret);

		$format = 'Bitso %s:%s:%s';
		$authHeader =  sprintf($format, $this->bitsoKey, $nonce, $signature);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.bitso.com". $url);

		if ( !is_null($json) ){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, "true");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '. $authHeader,'Content-Type: application/json'));
		$response = curl_exec($ch);

		$json = json_decode($response);

		if(isset($json->error)){
			throw new \Exception("Error Processing Request: ". $json->error->message);
		}

		return $json;
	}

    public function getBalance()
	{
        $object = $this->getBitsoRequest("/v3/balance/");

        $results = array();
        foreach ($object->payload->balances as $key => $value) {
            if ($value->total > 0.0002){
                $results[] = $value;
            }
        }

        return $results;
	}

    public function getTicker()
	{
		if (is_null($this->ticker)){
			$this->ticker = $this->getBitsoRequest("/v3/ticker/")->payload;
			return $this->ticker;
		}

		return $this->ticker;
	}
    
    public function userTrades()
    {
        return $this->getBitsoRequest('/v3/user_trades/')->payload;
    }

	public function getBookPrice(string $book)
	{
		foreach ($this->getTicker() as $tickerBook){
			if ($tickerBook->book == $book){
				return $tickerBook;
			}
		}

		return null;
	}

    public function placeOrder(Request $data)
    {
		try {
			$response = $this->getBitsoRequest('/v3/orders/', $data);
		}
		
		catch(\Exception $err){
			return response()->json([
				"success" => false,
				"message" => $err->getMessage()
			]);
		}
        
        return response()->json([
			"data"	   => $data->all(),
			"response" => $response
		]);
    }
}