<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsServiceException;

class RocketSmsService extends BaseService
{
    private $response;

    public function send($recipient, $message)
    {
        return $this->runMethod('send', [
            'phone' => $recipient,
            'text' => $message
        ]);
    }

    public function getResponse()
    {
        return $this->response;
    }

    private function runMethod($method, $params = [])
    {
        return $this->httpRequest('http://api.rocketsms.by/simple/' . $method, array_merge($params, [
            'username' => $this->param('username'),
            'password' => $this->param('password'),
        ]));
    }

    private function httpRequest($url, $postData = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($curl);
        $this->response = $response;
        $result = @json_decode($response, true);

        curl_close($curl);

        if (isset($result['error'])) {
            if ($result['error'] == 'WRONG_AUTH') {
                throw new TagerSmsServiceException('Invalid Authentication Data');
            }

            throw new TagerSmsServiceException($result['error']);
        }

        return $result;
    }
}
