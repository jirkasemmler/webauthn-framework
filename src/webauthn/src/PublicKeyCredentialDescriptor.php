<?php

declare(strict_types=1);

namespace Webauthn;

use Assert\Assertion;
use function count;
use const JSON_THROW_ON_ERROR;
use JsonSerializable;
use ParagonIE\ConstantTime\Base64;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Throwable;

class PublicKeyCredentialDescriptor implements JsonSerializable
{
    public const CREDENTIAL_TYPE_PUBLIC_KEY = 'public-key';

    public const AUTHENTICATOR_TRANSPORT_USB = 'usb';

    public const AUTHENTICATOR_TRANSPORT_NFC = 'nfc';

    public const AUTHENTICATOR_TRANSPORT_BLE = 'ble';

    public const AUTHENTICATOR_TRANSPORT_INTERNAL = 'internal';

    /**
     * @var string[]
     */
    protected $transports;

    /**
     * @param string[] $transports
     */
    public function __construct(
        protected string $type,
        protected string $id,
        array $transports = []
    ) {
        $this->transports = $transports;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getTransports(): array
    {
        return $this->transports;
    }

    public static function createFromString(string $data): self
    {
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        Assertion::isArray($data, 'Invalid data');

        return self::createFromArray($data);
    }

    /**
     * @param mixed[] $json
     */
    public static function createFromArray(array $json): self
    {
        Assertion::keyExists($json, 'type', 'Invalid input. "type" is missing.');
        Assertion::keyExists($json, 'id', 'Invalid input. "id" is missing.');

        try {
            $id = Base64UrlSafe::decode($json['id']);
        } catch (Throwable) {
            $id = Base64::decode($json['id']);
        }

        return new self($json['type'], $id, $json['transports'] ?? []);
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        $json = [
            'type' => $this->type,
            'id' => Base64UrlSafe::encodeUnpadded($this->id),
        ];
        if (count($this->transports) !== 0) {
            $json['transports'] = $this->transports;
        }

        return $json;
    }
}
