<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\GPG;

use RuntimeException;

final readonly class GnuPG2
{
    public function __construct(
        private string $binary,
    )
    {
        if (! file_exists($binary)) {
            throw new RuntimeException(sprintf('GnuPG binary "%s" does not exist', $binary));
        }
    }

    public function getBinary(): string
    {
        return $this->binary;
    }
}


// $gpg = new GnuPG2('/usr/bin/gpg');

// $gnupg = new \gnupg('/usr/bin/gpg');
// $fingerprint = new Fingerprint($gpg);

// $gpg->importPublicKey('/path/to/public.key');
// $gpg->importPrivateKey('/path/to/private.key');

// PrivateKey::fromFile('/path/to/private.key');
// PublicKey::fromFile('/path/to/public.key');

// PrivateKey::fromString('-----BEGIN PGP PRIVATE KEY BLOCK ...');
// PublicKey::fromString('-----BEGIN PGP PUBLIC KEY BLOCK ...');