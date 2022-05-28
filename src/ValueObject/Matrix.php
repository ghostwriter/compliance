<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ValueObject;

final class Matrix
{
    /**
     * @var array{
     *     exclude:list<string>,
     *     include:list<string>,
     * }
     */
    private array $data = [
        'include' => [],
        'exclude' => [],
    ];

    private string $dependencies;

    private string $job;

    private string $name;

    private string $os;

    public function __construct(string $name, string $os, string $job, string $dependencies)
    {
        $this->name = $name;
        $this->os = $os;
        $this->job = $job;
        $this->dependencies = $dependencies;
    }

    public function addJob(Job $job): void
    {
//
        $this->data['include'] = $job::class;

        $this->data['exclude'] = $job::class;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getDependencies(): string
    {
        return $this->dependencies;
    }

    public function getJob(): string
    {
        return $this->job;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOs(): string
    {
        return $this->os;
    }
}
