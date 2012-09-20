<?php
	class BingTranslate
	{
		public $headers = null;

        private $clientID = '';
        private $clientSecret = '';
        private $requestStr = '';
        private $accessToken = '';
        private $headerValue = '';
        private $datamarketAccessUri = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13";

		public function __construct ($clientID,$clientSecret)
		{
            $this->clientID = $clientID;
            $this->clientSecret = $clientSecret;
            $post_data = array(
                "grant_type" => "client_credentials",
                "client_id" => $this->clientID,
                "client_secret" => $this->clientSecret,
                "scope" => "http://api.microsofttranslator.com"
            );

            //$headers = array("Content-Type: application/x-www-form-urlencoded");

            $token = $this->post($this->datamarketAccessUri,$post_data);
            $token = json_decode($token,true);

            $this->accessToken = $token["access_token"];
            $this->headerValue = "Bearer " . $this->accessToken;
		}

        public function getTranslation($text,$from,$to,$maxTranslations=1)
        {
            $url = sprintf("http://api.microsofttranslator.com/V2/Ajax.svc/GetTranslations?oncomplete=&text=%s&from=%s&to=%s&maxTranslations=%d",urlencode($text),$from,$to,$maxTranslations);

            $header = array("Authorization:".$this->headerValue,"Content-Type: application/json");
            $data = $this->get($url,$header);
            $data = ltrim($data);
            $result = "{" . str_replace('\u000d\u000a', '<br />', rtrim(mb_substr($data,16), '"'));

            $data = json_decode($result,true);


            if($data["Translations"][0]["TranslatedText"])
                $trans = $data;
            else
                $trans = false;

            return $trans;
        }


		public function get($url,$headers = array())
		{
			$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_REFERER,  "http://www.abril.com.br" );
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            if(count($headers)>0)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    		$output = curl_exec($ch);
    		$this->headers = curl_getinfo($ch);
    		curl_close($ch);

    		return $output;
		}

		public function post($url,$fields,$headers = array())
		{
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }

			$fields_string = rtrim($fields_string,'&');

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_REFERER, "http://www.abril.com.br");
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            if(count($headers)>0)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$output = curl_exec($ch);
			$this->headers = curl_getinfo($ch);
			curl_close($ch);

			return $output;
		}
	}