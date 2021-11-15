<?php

declare(strict_types=1);

namespace Webauthn;

/**
 * @see https://www.w3.org/TR/webauthn/#authenticatorassertionresponse
 */
class AuthenticatorAssertionResponse extends AuthenticatorResponse
{
    public function __construct(
        CollectedClientData $clientDataJSON,
        private AuthenticatorData $authenticatorData,
        private string $signature,
        private ?string $userHandle
    ) {
        parent::__construct($clientDataJSON);
    }

    public function getAuthenticatorData(): AuthenticatorData
    {
        return $this->authenticatorData;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getUserHandle(): ?string
    {
        if ($this->userHandle === null || $this->userHandle === '') {
            return $this->userHandle;
        }

        return base64_decode($this->userHandle, true);
    }
}
