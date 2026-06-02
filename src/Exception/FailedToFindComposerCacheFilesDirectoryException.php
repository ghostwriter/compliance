<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Exception;

use Ghostwriter\Compliance\Interface\ComplianceExceptionInterface;
use RuntimeException;

final class FailedToFindComposerCacheFilesDirectoryException extends RuntimeException implements ComplianceExceptionInterface {}
