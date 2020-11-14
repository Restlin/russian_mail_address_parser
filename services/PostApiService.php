<?php

namespace app\services;

use app\models\Row;
use Yii;
use yii\base\InvalidArgumentException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;

class PostApiService {

    /**
     * Токен доступа
     * @var string
     */
    private string $token = '';

    /**
     * Конструктор
     * @param array $params конфигуратор
     */
    public function __construct(array $params = []) {
        if (!key_exists('token', Yii::$app->params)) {
            throw new InvalidArgumentException();
        }
        $this->token = Yii::$app->params['token'];
    }

    /**
     * Создание клиента для DaData
     * @return Client
     */
    private function getPochtaClient() {
        $client = new Client([
            'baseUrl' => 'https://address.pochta.ru/validate/api',
            'transport' => [
                'class' => CurlTransport::class,
            ],
            'requestConfig' => [
                'format' => Client::FORMAT_JSON,
                'options' => [
                    CURLOPT_FAILONERROR => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 3,
                    CURLOPT_CONNECTTIMEOUT => 3,
                    CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
                ],
                'headers' => [
                    'AuthCode' => $this->token,
                ]
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        return $client;
    }

    /**
     * Обработка ответа
     * @param Request $request
     * @return array
     */
    protected function requestSend(Request $request): array {
        try {
            $response = $request->send();
        } catch (Exception $exception) {
            Yii::error($exception);
            return [];
        }
        return $this->checkResult($response);
    }

    /**
     * Проверка наличия результата
     * @param Response $response - ответ от запрашиваемного сервиса
     * @return array
     */
    protected function checkResult(Response $response): array {
        if ($response->isOk) {
            return $response->data;
        } else {
            Yii::error($response);
        }
        return [];
    }

    public function checkAddress(Row $row) {
        $client = $this->getPochtaClient();
        $request = $client->post('/v7_1', [
            'addr' => [
                ['val' => $row->address_base],
            ],
            'version' => 'IT animals',
            'reqId' => $row->id
        ]);
        return $this->requestSend($request);
    }

}
