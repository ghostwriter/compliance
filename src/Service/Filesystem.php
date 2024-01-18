<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use DirectoryIterator;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use const DIRECTORY_SEPARATOR;
use const FILE_APPEND;
use function array_diff;
use function array_merge;
use function chgrp;
use function chmod;
use function chown;
use function copy;
use function dirname;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function glob;
use function is_dir;
use function mkdir;
use function rename;
use function rmdir;
use function scandir;
use function sprintf;
use function unlink;

final readonly class Filesystem
{
    /**
     * @return list<string>
     */
    public function allFiles(string $directory): array
    {
        $files = [];

        foreach ($this->directories($directory) as $directory) {
            $files = array_merge($files, $this->allFiles($directory));
        }

        return array_merge($files, $this->files($directory));
    }

    public function append(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents, FILE_APPEND);

        if ($bytesWritten === false) {
            throw new RuntimeException(sprintf('Filesystem::append() failed to write to path: %s', $path));
        }
    }

    public function chgrp(string $path, string $group): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::chgrp() failed because the path does not exist: %s',
                $path
            ));
        }

        chgrp($path, $group);
    }

    public function chmod(string $path, int $mode): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::chmod() failed because the path does not exist: %s',
                $path
            ));
        }

        chmod($path, $mode);
    }

    public function chown(string $path, string $user): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::chown() failed because the path does not exist: %s',
                $path
            ));
        }

        chown($path, $user);
    }

    public function cleanDirectory(string $path): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::deleteDirectory() failed because the path does not exist: %s',
                $path
            ));
        }

        // TODO: replace with recursive iterator

        $files = array_diff(scandir($path), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
                continue;
            }

            $this->deleteFile($filePath);
        }
    }

    public function copy(string $source, string $destination): void
    {
        if (! file_exists($source)) {
            throw new RuntimeException(sprintf(
                'Filesystem::copy() failed because the source path does not exist: %s',
                $source
            ));
        }

        copy($source, $destination);
    }

    public function createDirectory(string $path): void
    {
        if (file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::createDirectory() failed because the path already exists: %s',
                $path
            ));
        }

        mkdir($path, 0777, true);
    }

    public function createFile(string $path): void
    {
        if (file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::create() failed because the path already exists: %s',
                $path
            ));
        }

        $parentDirectory = $this->parentDirectory($path);

        if (! is_dir($parentDirectory)) {
            $this->createDirectory($parentDirectory);
        }

        $this->write($path, '');
    }

    public function delete(string $path): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Filesystem::delete() failed because the path does not exist: %s',
                $path
            ));
        }

        is_dir($path) ?
            $this->deleteDirectory($path) :
            $this->deleteFile($path);
    }

    public function deleteDirectory(string $path): void
    {
        $this->cleanDirectory($path);

        rmdir($path);
    }

    public function deleteFile(string $path): void
    {
        unlink($path);
    }

    /**
     * @return list<string>
     */
    public function directories(string $directory): array
    {
        $directories = [];

        foreach ($this->findIn($directory) as $path) {
            if (! $path->isDir()) {
                continue;
            }

            $directories[] = $path->getPathname();
        }

        return $directories;
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @return list<string>
     */
    public function files(string $directory): array
    {
        $directories = [];

        foreach ($this->findIn($directory) as $path) {
            if (! $path->isFile()) {
                continue;
            }

            $directories[] = $path->getPathname();
        }

        return $directories;
    }

    /**
     * @return Generator<SplFileInfo>
     */
    public function findIn(string $directory): Generator
    {
        yield from new DirectoryIterator($directory);
    }

    /**
     * @return Generator<SplFileInfo>
     */
    public function findInRecursive(string $directory): Generator
    {
        yield from new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    }

    /**
     * @return bool
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        $paths = glob($pattern, $flags);

        if ($paths === false) {
            throw new RuntimeException(sprintf('Filesystem::glob() failed to glob pattern: %s', $pattern));
        }

        return $paths;
    }

    public function missing(string $path): bool
    {
        return ! file_exists($path);
    }

    public function move(string $source, string $destination): void
    {
        if (! file_exists($source)) {
            throw new RuntimeException(sprintf(
                'Filesystem::move() failed because the source path does not exist: %s',
                $source
            ));
        }

        rename($source, $destination);
    }

    public function parentDirectory(string $path, int $levels = 1): string
    {
        return dirname($path, $levels);
    }

    public function prepend(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents . $this->read($path));

        if ($bytesWritten === false) {
            throw new RuntimeException(sprintf('Filesystem::prepend() failed to write to path: %s', $path));
        }
    }

    public function read(string $path): string
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException(sprintf('Filesystem::read() failed to read from path: %s', $path));
        }

        return $contents;
    }

    public function write(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents);

        if ($bytesWritten === false) {
            throw new RuntimeException(sprintf('Filesystem::write() failed to write to path: %s', $path));
        }
    }
}
