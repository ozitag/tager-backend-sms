<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsServiceException;

class MessaggioService extends BaseService
{
    private ?string $response;

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function send(string $recipient, string $message, array $options = [])
    {
        $this->httpRequest('/send', array_merge([
            'recipients' => [['phone' => $recipient]],
            'channels' => ['sms'],
            'sms' => [
                'from' => $this->param('from'),
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $message
                    ]
                ]
            ],
            'text' => $message
        ], $options));
    }

    private function httpRequest(string $endpoint, array $postData = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://msg.messaggio.com/api/v1' . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Messaggio-Login: ' . $this->param('username'),
        ]);

        $response = curl_exec($curl);
        $this->response = $response;
        $result = json_decode($response, true);
        $info = curl_getinfo($curl);
        curl_close($curl);

        if ($info['http_code'] >= 400) {
            throw new TagerSmsServiceException($result['detail']);
        }

        $response = $result['messages'][0] ?? null;
        if (isset($response['error'])) {
            throw new TagerSmsServiceException($response['error']['title']);
        }

        return $result;
    }
}
