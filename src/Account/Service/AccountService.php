<?php

namespace Bizhost\Authentication\Adapter\Account\Service;

use Bizhost\Authentication\Adapter\Account\Model\Account;
use Bizhost\Authentication\Adapter\Authenticate\Model\Metadata;
use Bizhost\Authentication\Adapter\Client\AuthClientConfig;
use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;
use Bizhost\Authentication\Adapter\Client\MetadataTrait;


class AccountService
{
    use MetadataTrait;

    protected AccountApiClient $client;

    public function __construct(
        protected readonly AuthClientConfig $config
    )
    {
        $this->client = new AccountApiClient($this->config);
        $this->fetchMetadata($this->client, $this->config);
    }

    public function getMetadata(): ?Metadata
    {
        if($this->config->getMetadata() === null) {
            $this->fetchMetadata($this->client, $this->config);
        }

        return $this->config->getMetadata();
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->client->setAccessToken($accessToken);
    }

    public function transformApiAccountToAccount(object $apiResponse): Account
    {
        return new Account(
            uuid: $apiResponse->data->uuid,
            email: $apiResponse->data->email,
            firstName: $apiResponse->data->firstName,
            insertion: $apiResponse->data->insertion,
            lastName: $apiResponse->data->lastName,
            roles: $apiResponse->data->roles,
            active: $apiResponse->data->active,
            verified: $apiResponse->data->verified ?? true,
            appMetaData: $apiResponse->data->appMetaData ?? null,
            userMetaData: $apiResponse->data->userMetaData ?? null,
            createdOn: (new \DateTimeImmutable)->setTimestamp($apiResponse->data->createdOn),
            updatedOn: $apiResponse->data->updatedOn !== null ? (new \DateTimeImmutable)->setTimestamp($apiResponse->data->updatedOn) : null,
            deletedOn: $apiResponse->data->deletedOn !== null ? (new \DateTimeImmutable)->setTimestamp($apiResponse->data->deletedOn) : null,
        );
    }

    public function transformAccountToApiAccount(Account $account): array
    {
        return [
            'firstName' => $account->getFirstName(),
            'insertion' => $account->getInsertion(),
            'lastName' => $account->getLastName(),
            'appMetaData' => $account->getAppMetaData(),
            'userMetaData' => $account->getUserMetaData(),
        ];
    }

    public function getCurrentAccount(): Account
    {
        try {
            $result = $this->client->send('GET', $this->getMetadata()->getUserInfoEndpoint() );

            return $this->transformApiAccountToAccount($result);
        } catch (AuthClientServerException $e) {
            throw new \Exception('Error while fetching account: ' . $e->getMessage());
        }
    }

    public function getAccountByUuid(string $uuid): Account
    {

        try {
            $result = $this->client->send('GET', 'account/' . $uuid);

            return $this->transformApiAccountToAccount($result);
        } catch (AuthClientServerException $e) {
            throw new \Exception('Error while fetching account: ' . $e->getMessage());
        }
    }

    public function updateAccount(Account $account): Account
    {
        try {
            $result = $this->client->send('PUT', 'account/' . $account->getUuId(), $this->transformAccountToApiAccount($account));

            return $this->transformApiAccountToAccount($result);
        } catch (AuthClientServerException $e) {
            throw new \Exception('Error while updating account: ' . $e->getMessage());
        }
    }
}
