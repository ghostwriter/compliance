<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Service\Factory;

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use RuntimeException;
use Throwable;
use function is_file;
use function sprintf;
use const DIRECTORY_SEPARATOR;

/**
 * @implements FactoryInterface<Automation>
 */
final readonly class AutomationFactory implements FactoryInterface
{
    private const string AUTOMATION_FILE = <<<EOD
        <?php

        declare(strict_types=1);

        use Ghostwriter\Compliance\Automation;
        use Ghostwriter\Compliance\Enum\ComposerStrategy;
        use Ghostwriter\Compliance\Enum\OperatingSystem;
        use Ghostwriter\Compliance\Enum\PhpVersion;
        use Ghostwriter\Compliance\Enum\Tool;

        return Automation::new()
            ->composerStrategies(...ComposerStrategy::cases())
            ->operatingSystems(...OperatingSystem::cases())
            ->phpVersions(...PhpVersion::cases())
            ->tools(...Tool::cases());

        EOD;

    public function __construct(
        private FilesystemInterface $filesystem,
    ) {}

    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container): Automation
    {
        $currentWorkingDirectory = $this->filesystem->currentWorkingDirectory();

        $automationFile = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'automation.php';

        if (! is_file($automationFile)) {

            $this->filesystem->createFile($automationFile, self::AUTOMATION_FILE);
        }

        /** @var Automation $automation */
        $automation = require $automationFile;
        if (! $automation instanceof Automation) {
            throw new RuntimeException(
                sprintf('File "%s" must return an instance of %s', $automationFile, Automation::class)
            );
        }

        return $automation;
    }
}
