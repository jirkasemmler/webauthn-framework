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
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * @group unit
 * @group Fido2
 *
 * @covers \Webauthn\PublicKeyCredentialCreationOptions
 *
 * @internal
 */
class PublicKeyCredentialCreationOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function anPublicKeyCredentialCreationOptionsCanBeCreatedAndValueAccessed(): void
    {
        $rp = new PublicKeyCredentialRpEntity('RP');
        $user = new PublicKeyCredentialUserEntity('USER', 'id', 'FOO BAR');

        $credential = new PublicKeyCredentialDescriptor('type', 'id', ['transport']);
        $credentialParameters = new PublicKeyCredentialParameters('type', -100);

        $options = PublicKeyCredentialCreationOptions::create(
                $rp,
                $user,
                'challenge',
                [$credentialParameters]
            )
            ->excludeCredential($credential)
            ->setTimeout(1000)
            ->setAttestation(PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_DIRECT)
        ;

        static::assertEquals('challenge', $options->getChallenge());
        static::assertInstanceOf(AuthenticationExtensionsClientInputs::class, $options->getExtensions());
        static::assertEquals([$credential], $options->getExcludeCredentials());
        static::assertEquals([$credentialParameters], $options->getPubKeyCredParams());
        static::assertEquals('direct', $options->getAttestation());
        static::assertEquals(1000, $options->getTimeout());
        static::assertInstanceOf(PublicKeyCredentialRpEntity::class, $options->getRp());
        static::assertInstanceOf(PublicKeyCredentialUserEntity::class, $options->getUser());
        static::assertInstanceOf(AuthenticatorSelectionCriteria::class, $options->getAuthenticatorSelection());
        static::assertEquals('{"rp":{"name":"RP"},"pubKeyCredParams":[{"type":"type","alg":-100}],"challenge":"Y2hhbGxlbmdl","attestation":"direct","user":{"name":"USER","id":"aWQ=","displayName":"FOO BAR"},"authenticatorSelection":{"requireResidentKey":false,"userVerification":"preferred"},"excludeCredentials":[{"type":"type","id":"aWQ","transports":["transport"]}],"timeout":1000}', json_encode($options));

        $data = PublicKeyCredentialCreationOptions::createFromString('{"rp":{"name":"RP"},"pubKeyCredParams":[{"type":"type","alg":-100}],"challenge":"Y2hhbGxlbmdl","attestation":"direct","user":{"name":"USER","id":"aWQ","displayName":"FOO BAR"},"authenticatorSelection":{"requireResidentKey":false,"userVerification":"preferred"},"excludeCredentials":[{"type":"type","id":"aWQ","transports":["transport"]}],"timeout":1000}');
        static::assertEquals('challenge', $data->getChallenge());
        static::assertInstanceOf(AuthenticationExtensionsClientInputs::class, $data->getExtensions());
        static::assertEquals([$credential], $data->getExcludeCredentials());
        static::assertEquals([$credentialParameters], $data->getPubKeyCredParams());
        static::assertEquals('direct', $data->getAttestation());
        static::assertEquals(1000, $data->getTimeout());
        static::assertInstanceOf(PublicKeyCredentialRpEntity::class, $data->getRp());
        static::assertInstanceOf(PublicKeyCredentialUserEntity::class, $data->getUser());
        static::assertInstanceOf(AuthenticatorSelectionCriteria::class, $data->getAuthenticatorSelection());
        static::assertEquals('{"rp":{"name":"RP"},"pubKeyCredParams":[{"type":"type","alg":-100}],"challenge":"Y2hhbGxlbmdl","attestation":"direct","user":{"name":"USER","id":"aWQ=","displayName":"FOO BAR"},"authenticatorSelection":{"requireResidentKey":false,"userVerification":"preferred"},"excludeCredentials":[{"type":"type","id":"aWQ","transports":["transport"]}],"timeout":1000}', json_encode($data));
    }

    /**
     * @test
     */
    public function anPublicKeyCredentialCreationOptionsWithoutExcludeCredentialsCanBeSerializedAndDeserialized(): void
    {
        $rp = new PublicKeyCredentialRpEntity('RP');
        $user = new PublicKeyCredentialUserEntity('USER', 'id', 'FOO BAR');

        $credentialParameters = new PublicKeyCredentialParameters('type', -100);

        $options = PublicKeyCredentialCreationOptions::create(
                $rp,
                $user,
                'challenge',
                [$credentialParameters]
            )
            ->setTimeout(1000)
            ->setAttestation(PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_INDIRECT)
        ;

        $json = json_encode($options);
        static::assertEquals('{"rp":{"name":"RP"},"pubKeyCredParams":[{"type":"type","alg":-100}],"challenge":"Y2hhbGxlbmdl","attestation":"indirect","user":{"name":"USER","id":"aWQ=","displayName":"FOO BAR"},"authenticatorSelection":{"requireResidentKey":false,"userVerification":"preferred"},"timeout":1000}', $json);
        $data = PublicKeyCredentialCreationOptions::createFromString($json);
        static::assertEquals([], $data->getExcludeCredentials());
    }
}
