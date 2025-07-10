<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Compliance\Container\ComplianceServiceProvider;
use Ghostwriter\Compliance\Value\Composer\Composer;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class AbstractTestCase extends TestCase
{
    public Composer $composer;

    public ContainerInterface $container;

    public FilesystemInterface $filesystem;

    /**
     * @throws Throwable
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Container::getInstance();

        $this->container->provide(ComplianceServiceProvider::class);

        $this->composer = $this->container->get(Composer::class);

        $this->filesystem = $this->container->get(FilesystemInterface::class);
    }

    /**
     * @throws Throwable
     */
    #[Override]
    protected function tearDown(): void
    {
        $this->container->clear();

        parent::tearDown();
    }
}
