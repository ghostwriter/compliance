<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Composer\Semver\Semver;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Compliance\Option\PhpVersion;
use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Container\Container;
use Ghostwriter\Json\Json;
use RuntimeException;
use Throwable;
use function getcwd;

final class MatrixListener implements EventListenerInterface
{
    /**
     * @var string[]
     */
    private const DEPENDENCIES = ['highest', 'locked', 'lowest'];

    public function __construct(
        private Container $container
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $phpVersions = [
            PhpVersion::PHP_81,
            PhpVersion::PHP_82,
            PhpVersion::PHP_83,
        ];

        $root = getcwd();

        if ($root === false) {
            throw new RuntimeException('Could not get current working directory');
        }

        $composerJson = file_get_contents(
            $this->container->get(Composer::class)->getJsonFilePath($root)
        );

        if ($composerJson === false) {
            throw new RuntimeException('Could not find composer.json');
        }

        /** @var string $constraints */
        $constraints = Json::decode($composerJson)[Composer::REQUIRE][Composer::PHP] ?? '*';

        foreach ($this->container->tagged(ToolInterface::class) as $tool) {
            if (! $tool->isPresent()) {
                continue;
            }

            $name = $tool->name();
            $command = $tool->command();
            $extensions = $tool->extensions();
            if ($tool instanceof PHPUnit) {
                foreach ($phpVersions as $phpVersion) {
                    if (! Semver::satisfies(PhpVersion::TO_STRING[$phpVersion], $constraints)) {
                        continue;
                    }

                    $isExperimental = $phpVersion === PhpVersion::DEV;

                    foreach (self::DEPENDENCIES as $dependency) {
                        $generateMatrixEvent->include(
                            new Job(
                                $name,
                                $command,
                                $extensions,
                                $dependency,
                                $phpVersion,
                                $isExperimental
                            )
                        );
                    }
                }
                continue;
            }

            $generateMatrixEvent->include(
                new Job(
                    $name,
                    $tool->command(),
                    $tool->extensions(),
                    'locked',
                    PhpVersion::LATEST
                )
            );
        }
    }
}
