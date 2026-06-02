<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Factory;

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use RuntimeException;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function implode;
use function sprintf;

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

    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Automation
    {
        $filesystem = $container->get(FilesystemInterface::class);

        $currentWorkingDirectory = $filesystem->currentWorkingDirectory();

        $automationFile = implode(DIRECTORY_SEPARATOR, [$currentWorkingDirectory, 'automation.php']);

        if (! $filesystem->isFile($automationFile)) {
            $filesystem->write($automationFile, self::AUTOMATION_FILE);
        }

        /** @var Automation $automation */
        $automation = require $automationFile;
        if (! $automation instanceof Automation) {
            throw new RuntimeException(sprintf(
                'File "%s" must return an instance of "%s".',
                $automationFile,
                Automation::class
            ));
        }

        return $automation;
    }
}
