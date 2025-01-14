<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2021 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Webauthn\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Webauthn\AttestedCredentialData;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientOutputs;
use Webauthn\AuthenticatorData;

/**
 * @group unit
 * @group Fido2
 *
 * @covers \Webauthn\AttestedCredentialData
 *
 * @internal
 */
class AuthenticatorDataTest extends TestCase
{
    /**
     * @test
     */
    public function anAuthenticatorDataCanBeCreatedAndValueAccessed(): void
    {
        $attestedCredentialData = new AttestedCredentialData(Uuid::uuid4(), '', null);
        $extensions = new AuthenticationExtensionsClientOutputs();

        $authenticatorData = new AuthenticatorData('auth_data', 'rp_id_hash', 'A', 100, $attestedCredentialData, $extensions);

        static::assertEquals('auth_data', $authenticatorData->getAuthData());
        static::assertEquals('rp_id_hash', $authenticatorData->getRpIdHash());
        static::assertTrue($authenticatorData->isUserPresent());
        static::assertFalse($authenticatorData->isUserVerified());
        static::assertEquals(100, $authenticatorData->getSignCount());
        static::assertEquals(0, $authenticatorData->getReservedForFutureUse1());
        static::assertEquals(0, $authenticatorData->getReservedForFutureUse2());
        static::assertTrue($authenticatorData->hasAttestedCredentialData());
        static::assertInstanceOf(AttestedCredentialData::class, $authenticatorData->getAttestedCredentialData());
        static::assertFalse($authenticatorData->hasExtensions());
        static::assertNull($authenticatorData->getExtensions());
    }
}
