<?php

declare(strict_types=1);

namespace Webauthn\MetadataService;

use function array_key_exists;
use Assert\Assertion;
use JsonSerializable;

class ExtensionDescriptor implements JsonSerializable
{
    private ?int $tag;

    public function __construct(
        private string $id,
        ?int $tag,
        private ?string $data,
        private bool $fail_if_unknown
    ) {
        if ($tag !== null) {
            Assertion::greaterOrEqualThan(
                $tag,
                0,
                Utils::logicException('Invalid data. The parameter "tag" shall be a positive integer')
            );
        }
        $this->tag = $tag;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTag(): ?int
    {
        return $this->tag;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function isFailIfUnknown(): bool
    {
        return $this->fail_if_unknown;
    }

    public static function createFromArray(array $data): self
    {
        $data = Utils::filterNullValues($data);
        Assertion::keyExists($data, 'id', Utils::logicException('Invalid data. The parameter "id" is missing'));
        Assertion::string($data['id'], Utils::logicException('Invalid data. The parameter "id" shall be a string'));
        Assertion::keyExists(
            $data,
            'fail_if_unknown',
            Utils::logicException('Invalid data. The parameter "fail_if_unknown" is missing')
        );
        Assertion::boolean(
            $data['fail_if_unknown'],
            Utils::logicException('Invalid data. The parameter "fail_if_unknown" shall be a boolean')
        );
        if (array_key_exists('tag', $data)) {
            Assertion::integer(
                $data['tag'],
                Utils::logicException('Invalid data. The parameter "tag" shall be a positive integer')
            );
        }
        if (array_key_exists('data', $data)) {
            Assertion::string(
                $data['data'],
                Utils::logicException('Invalid data. The parameter "data" shall be a string')
            );
        }

        return new self($data['id'], $data['tag'] ?? null, $data['data'] ?? null, $data['fail_if_unknown']);
    }

    public function jsonSerialize(): array
    {
        $result = [
            'id' => $this->id,
            'tag' => $this->tag,
            'data' => $this->data,
            'fail_if_unknown' => $this->fail_if_unknown,
        ];

        return Utils::filterNullValues($result);
    }
}
