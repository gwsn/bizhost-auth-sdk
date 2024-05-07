<?php

namespace Bizhost\Authentication\Adapter\Client;

use Bizhost\Authentication\Adapter\Client\Exception\AuthClientGenericServerException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


abstract class AbstractAuthClient implements AuthClientInterface
{
    private GuzzleClient|null $client;
    private ?string $accessToken = null;

    public function __construct(
        private readonly AuthClientConfig $config
    )
    {
        $this->setClient();
    }

    public function setClient(GuzzleClient $client = null): void
    {
        if ($client instanceof GuzzleClient) {
            $this->client = $client;

            return;
        }

        $this->client = new GuzzleClient([
            'base_uri' => $this->config->getApiUrl(),
            'timeout' =>  $this->config->getTimeout(),
        ]);
    }

    public function getClient(): GuzzleClient
    {
        return $this->client;
    }

    public function sendUnAuthenticated(string $method = 'GET', string $urlPath = '', array $data = null, array $headers = []): mixed {
        try {

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
                throw new AuthClientGenericServerException($statusCode, $content);
            }

            // $statusCode >= 500 | Error in backend.
            if ($statusCode >= 500) {
                throw new AuthClientGenericServerException($statusCode, $content);
            }

            // $statusCode < 400 | Valid Response.
            if ($statusCode > 100 && $statusCode < 400) {
                return $result;
            }

            // Valid response code but there is no valid response: $statusCode < 400 and $result is invalid
            throw new AuthClientGenericServerException($statusCode, $content);
        } catch (AuthClientGenericServerException $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new AuthClientGenericServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new AuthClientGenericServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws AuthClientGenericServerException
     */
    public function send(string $method = 'GET', string $urlPath = '', array $data = null, array $headers = []): mixed
    {
        try {

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
                throw new AuthClientGenericServerException($statusCode, $content);
            }

            // $statusCode >= 500 | Error in backend.
            if ($statusCode >= 500) {
                throw new AuthClientGenericServerException($statusCode, $content);
            }

            // $statusCode < 400 | Valid Response.
            if ($statusCode > 100 && $statusCode < 400) {
                return $result;
            }

            // Valid response code but there is no valid response: $statusCode < 400 and $result is invalid
            throw new AuthClientGenericServerException($statusCode, $content);
        } catch (AuthClientGenericServerException $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new AuthClientGenericServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new AuthClientGenericServerException($statusCode ?? 500, $e->getMessage(), $e->getCode(), $e);
        }
    }
}
