<?php

namespace App\Domain\Company;

use App\Exceptions\Company\CnpjMustHaveTwelveDigitsException;

class CompanyDomain implements CompanyDomainInterface
{
    const CNPJ_LENGTH = 14;
    private ?int $id;
    private int $responsibleId;
    private string $name;
    private string $description;
    private string $phone;
    private string $email;
    private int $cnpj;
    private ?string $fantasyName;
    private ?string $location;

    public function __construct(private CompanyRepositoryInterface $repository)
    {
    }

    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * @throws CnpjMustHaveTwelveDigitsException
     */
    public function save(): self
    {
        $createdData = $this->repository->save($this->toArray());

        return $this->fromArray($createdData);
    }

    /**
     * @throws CnpjMustHaveTwelveDigitsException
     */
    public function update(): self
    {
        $updatedData = $this->repository->update($this->toArray());

        return $this->fromArray($updatedData);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'responsible_id' => $this->getResponsibleId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'cnpj' => $this->getCnpj(),
            'fantasy_name' => $this->getFantasyName(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @throws CnpjMustHaveTwelveDigitsException
     */
    public function fromArray(array $data): self
    {
        $this->setId($data['id'] ?? null);
        $this->setResponsibleId($data['responsible_id']);
        $this->setName($data['name']);
        $this->setDescription($data['description']);
        $this->setPhone($data['phone']);
        $this->setEmail($data['email']);
        $this->setCnpj($data['cnpj']);
        $this->setFantasyName($data['fantasy_name'] ?? null);
        $this->setLocation($data['location'] ?? null);

        return $this;
    }


    /**
     * @throws CnpjMustHaveTwelveDigitsException
     */
    public function load(int $companyId): self
    {
        $data = $this->repository->load($companyId);

        return $this->fromArray($data);
    }

    public function list(): array
    {
        $companies = $this->repository->list();

        return array_map(/**
         * @throws CnpjMustHaveTwelveDigitsException
         */ function ($company) {
            return (new self($this->repository))->fromArray($company);
        }, $companies);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): CompanyDomain
    {
        $this->id = $id;
        return $this;
    }

    public function getResponsibleId(): int
    {
        return $this->responsibleId;
    }

    public function setResponsibleId(int $responsibleId): CompanyDomain
    {
        $this->responsibleId = $responsibleId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CompanyDomain
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): CompanyDomain
    {
        $this->description = $description;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): CompanyDomain
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CompanyDomain
    {
        $this->email = $email;
        return $this;
    }

    public function getCnpj(): int
    {
        return $this->cnpj;
    }

    /**
     * @throws CnpjMustHaveTwelveDigitsException
     */
    public function setCnpj(int|string $cnpj): CompanyDomain
    {
        if (strlen($cnpj) !== self::CNPJ_LENGTH) {
            throw new CnpjMustHaveTwelveDigitsException($cnpj);
        }

        $this->cnpj = $cnpj;

        return $this;
    }

    public function getFantasyName(): ?string
    {
        return $this->fantasyName;
    }

    public function setFantasyName(?string $fantasyName): CompanyDomain
    {
        $this->fantasyName = $fantasyName;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): CompanyDomain
    {
        $this->location = $location;
        return $this;
    }
}
