<?php

namespace Bizhost\Authentication\Adapter\Account\Service;

use Bizhost\Authentication\Adapter\Account\Service\Exception\AccountApiClientServerException;
use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;
use Bizhost\Authentication\Adapter\Client\AbstractAuthClient;
use Bizhost\Authentication\Adapter\Client\AuthClientInterface;

use GuzzleHttp\Exception\GuzzleException;

class AccountApiClient extends AbstractAuthClient implements AuthClientInterface
{

    private ?string $accessToken = null;


    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @throws AuthClientServerException
     */
    public function send(string $method = 'GET', string $urlPath = '', array $data = null, array $headers = []): mixed
    {
        try {

            if ($this->accessToken === null) {
                throw new \RuntimeException('No valid AccessToken has been provided');
            }

            $headers = [
                ...[
                    'Authorization' => sprintf('Bearer %s', $this->accessToken),
                ],
                ...$headers,
            ];

            $options = [
                'headers' => $headers,
                'http_errors' => false,
            ];

            if ($data !== null) {
                $options['json'] = $data;
            }

            $requestResult = $this->getClient()->request($method, $urlPath, $options);

            $body = $requestResult->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            // Validate the apiResponse
            $statusCode = $requestResult->getStatusCode();

            // $statusCode >= 400 AND $statusCode < 500 | Issues with the data in the response.
            if ($statusCode >= 400 && $statusCode < 500) {
                throw new AccountApiClientServerException($statusCode, $content);
            }

            // $statusCode >= 500 | Error in backend.
            if ($statusCode >= 500) {
                throw new AccountApiClientServerException($statusCode, $content);
            }

            // $statusCode < 400 | Valid Response.
            if ($statusCode > 100 && $statusCode < 400) {
                return $result;
            }

            // Valid response code but there is no valid response: $statusCode < 400 and $result is invalid
            throw new AccountApiClientServerException($statusCode, $content);
        } catch (AccountApiClientServerException $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new AccountApiClientServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new AccountApiClientServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        }
    }
}
