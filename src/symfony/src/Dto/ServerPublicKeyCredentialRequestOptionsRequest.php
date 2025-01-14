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

namespace Webauthn\Bundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Webauthn\PublicKeyCredentialRequestOptions;

final class ServerPublicKeyCredentialRequestOptionsRequest
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(allowNull=true)
     */
    public $username;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Choice({PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_PREFERRED, PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED, PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_DISCOURAGED})
     */
    public $userVerification;

    /**
     * @var array|null
     */
    public $extensions;
}
