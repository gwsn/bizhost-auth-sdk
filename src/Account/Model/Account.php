<?php

namespace Bizhost\Authentication\Adapter\Account\Model;


class Account {
    public function __construct(
        private readonly string $uuid,
        private readonly string $email,
        private readonly string $firstName,
        private readonly ?string $insertion,
        private readonly string $lastName,
        private readonly array $roles,
        private readonly bool $active,
        private readonly bool $verified,
        /**
         * @var object|array|null Custom fields that store info about a user that influences the user's access, such as support plan, security roles, or access control groups. Is `empty` by default and should contain valid json string
         */
        private readonly object|array|null $appMetaData,
        /**
         * @var object|array|null Custom fields that store info about a user that does not impact what they can or cannot access, such as work address, home address, or user preferences. Should contain valid json string
         */
        private readonly object|array|null $userMetaData,
        private readonly \DateTimeImmutable $createdOn,
        private readonly ?\DateTimeImmutable $updatedOn,
        private readonly ?\DateTimeImmutable $deletedOn
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getInsertion(): ?string
    {
        return $this->insertion;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getAppMetaData(): object|array|null
    {
        return $this->appMetaData;
    }

    public function getUserMetaData(): object|array|null
    {
        return $this->userMetaData;
    }

    public function getCreatedOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function getUpdatedOn(): ?\DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function getDeletedOn(): ?\DateTimeImmutable
    {
        return $this->deletedOn;
    }
}
