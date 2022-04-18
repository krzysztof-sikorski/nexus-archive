<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use App\Contract\Doctrine\Entity\DatedEntityInterface;
use App\Contract\Doctrine\Entity\UuidPrimaryKeyInterface;
use App\Doctrine\Repository\PageViewRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[
    ORM\Entity(repositoryClass: PageViewRepository::class),
    ORM\Table(name: 'page_view'),
    ORM\Index(columns: ['created_at', 'request_started_at', 'id'], name: 'page_view_sorting_idx'),
    ORM\Index(columns: ['owner_id'], name: 'page_view_owner_idx'),
]
class PageView implements UuidPrimaryKeyInterface, DatedEntityInterface, JsonSerializable
{
    use UuidPrimaryKeyTrait;
    use DatedEntityTrait;

    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?User $owner = null;

    #[ORM\Column(name: 'request_started_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $requestStartedAt = null;

    #[ORM\Column(name: 'response_completed_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $responseCompletedAt = null;

    #[ORM\Column(name: 'method', type: Types::TEXT, nullable: false)]
    private ?string $method = null;

    #[ORM\Column(name: 'url', type: Types::TEXT, nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'form_data', type: Types::JSON, nullable: true)]
    private mixed $formData = null;

    #[ORM\Column(name: 'response_body', type: Types::TEXT, nullable: false)]
    private ?string $responseBody = null;

    #[ORM\Column(name: 'parsed_at', type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $parsedAt = null;

    #[ORM\Column(name: 'parser_errors', type: 'json', nullable: true)]
    private mixed $parserErrors = null;

    public function __construct()
    {
        $this->generateId();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'createdAt' => $this->getCreatedAt()?->format(DateTimeInterface::ISO8601),
            'ownerId' => $this->getOwner()?->getId(),
            'requestStartedAt' => $this->getRequestStartedAt()?->format(DateTimeInterface::ISO8601),
            'responseCompletedAt' => $this->getResponseCompletedAt()?->format(DateTimeInterface::ISO8601),
            'method' => $this->getMethod(),
            'url' => $this->getUrl(),
            'formData' => $this->getFormData(),
            'responseBody' => $this->getResponseBody(),
        ];
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getRequestStartedAt(): ?DateTimeImmutable
    {
        return $this->requestStartedAt;
    }

    public function setRequestStartedAt(?DateTimeImmutable $requestStartedAt): void
    {
        $this->requestStartedAt = $requestStartedAt;
    }

    public function getResponseCompletedAt(): ?DateTimeImmutable
    {
        return $this->responseCompletedAt;
    }

    public function setResponseCompletedAt(?DateTimeImmutable $responseCompletedAt): void
    {
        $this->responseCompletedAt = $responseCompletedAt;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getFormData(): mixed
    {
        return $this->formData;
    }

    public function setFormData(mixed $formData): void
    {
        $this->formData = $formData;
    }

    /**
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @param string|null $responseBody
     */
    public function setResponseBody(?string $responseBody): void
    {
        $this->responseBody = $responseBody;
    }

    public function getParsedAt(): ?DateTimeImmutable
    {
        return $this->parsedAt;
    }

    public function setParsedAt(?DateTimeImmutable $parsedAt): void
    {
        $this->parsedAt = $parsedAt;
    }

    public function getParserErrors(): ?array
    {
        return $this->parserErrors;
    }

    public function setParserErrors(?array $parserErrors): void
    {
        $this->parserErrors = $parserErrors;
    }
}
