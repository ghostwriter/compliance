<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Compliance\Composer\ComposerManager;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class AbstractTestCase extends TestCase
{
    public ComposerManager $composer;

    public ContainerInterface $container;

    public FilesystemInterface $filesystem;

    /** @throws Throwable */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Container::getInstance();

        $this->composer = $this->container->get(ComposerManager::class);

        $this->filesystem = $this->container->get(FilesystemInterface::class);
    }

    /** @throws Throwable */
    #[Override]
    protected function tearDown(): void
    {
        $this->container->reset();

        parent::tearDown();
    }
}
