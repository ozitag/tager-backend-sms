<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsServiceException;

class SmsByService extends BaseService
{
    private ?string $response;

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function send(string $recipient, string $message, array $options = [])
    {
        $params = array_merge([
            'message' => $message,
            'phone' => $recipient,
        ], $options);

        $alphanameId = $this->param('alphaname_id');
        if (!empty($alphanameId) && !isset($params['alphaname_id'])) {
            $params['alphaname_id'] = $alphanameId;
        }

        return $this->httpRequest('sendQuickSms', $params);
    }

    private function httpRequest(string $command, array $params = [])
    {
        $query = array_merge(['token' => $this->param('token')], $params);
        $url = 'https://app.sms.by/api/v1/' . $command . '?' . http_build_query($query);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $this->response = $response;
        $result = @json_decode($response, true);
        curl_close($curl);

        if (isset($result['error'])) {
            $error = $result['error'];
            $message = is_array($error) ? ($error['message'] ?? json_encode($error)) : $error;
            throw new TagerSmsServiceException($message);
        }

        return $result;
    }
}
