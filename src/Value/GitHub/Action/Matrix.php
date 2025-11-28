<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\GitHub\Action;

use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use Throwable;

final class Matrix
{
    /**
     * @param list<array{name:string,command:string,extensions:list<string>,os:string,php:string,dependency:string,experimental:bool}> $include
     * @param list<string>                                                                                                             $exclude
     */
    public function __construct(
        public FilesystemInterface $filesystem,
        public JsonInterface $json,
        public array $include = [],
        public array $exclude = [],
    ) {}

    /** @param list<string> $matrices */
    public function exclude(array $matrices): void
    {
        foreach ($matrices as $matrix) {
            $this->exclude[] = $matrix;
        }
    }

    public function include(Job $job): void
    {
        $this->include[] = $job->toArray();
    }

    /** @throws Throwable */
    public function toString(): string
    {
        if ([] === $this->include) {
            $this->include(Job::noop($this->filesystem));
        }

        return $this->json->encode([
            'include' => $this->include,
            'exclude' => $this->exclude,
        ]);
    }
}
