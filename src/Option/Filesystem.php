<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final readonly class Filesystem
{
    public function parentDirectory(string $path, int $levels = 1): string
    {
        return dirname($path, $levels);
    }

    public function createFile(string $path): void
    {
        if (file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::create() failed because the path already exists: %s',
                $path
            ));
        }

        $parentDirectory = $this->parentDirectory($path);

        if (!is_dir($parentDirectory)) {
            $this->createDirectory($parentDirectory);
        }

        $this->write($path, '');
    }

    public function createDirectory(string $path): void
    {
        if (file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::createDirectory() failed because the path already exists: %s',
                $path
            ));
        }

        mkdir($path, 0777, true);
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function missing(string $path): bool
    {
        return !file_exists($path);
    }

    public function cleanDirectory(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf(
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
    public function deleteDirectory(string $path): void
    {
        $this->cleanDirectory($path);

        rmdir($path);
    }

    public function deleteFile(string $path): void
    {
        unlink($path);
    }

    public function delete(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::delete() failed because the path does not exist: %s',
                $path
            ));
        }

        is_dir($path) ?
            $this->deleteDirectory($path) :
            $this->deleteFile($path);
    }

    public function write(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents);

        if (false === $bytesWritten) {
            throw new \RuntimeException(sprintf(
                'Filesystem::write() failed to write to path: %s',
                $path
            ));
        }
    }

    public function read(string $path): string
    {
        $contents = file_get_contents($path);

        if (false === $contents) {
            throw new \RuntimeException(sprintf(
                'Filesystem::read() failed to read from path: %s',
                $path
            ));
        }

        return $contents;
    }

    public function append(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents, FILE_APPEND);

        if (false === $bytesWritten) {
            throw new \RuntimeException(sprintf(
                'Filesystem::append() failed to write to path: %s',
                $path
            ));
        }
    }

    public function prepend(string $path, string $contents): void
    {
        $bytesWritten = file_put_contents($path, $contents . $this->read($path));

        if (false === $bytesWritten) {
            throw new \RuntimeException(sprintf(
                'Filesystem::prepend() failed to write to path: %s',
                $path
            ));
        }
    }

    public function copy(string $source, string $destination): void
    {
        if (!file_exists($source)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::copy() failed because the source path does not exist: %s',
                $source
            ));
        }

        copy($source, $destination);
    }

    public function move(string $source, string $destination): void
    {
        if (!file_exists($source)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::move() failed because the source path does not exist: %s',
                $source
            ));
        }

        rename($source, $destination);
    }

    public function chmod(string $path, int $mode): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::chmod() failed because the path does not exist: %s',
                $path
            ));
        }

        chmod($path, $mode);
    }

    public function chown(string $path, string $user): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::chown() failed because the path does not exist: %s',
                $path
            ));
        }

        chown($path, $user);
    }

    public function chgrp(string $path, string $group): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf(
                'Filesystem::chgrp() failed because the path does not exist: %s',
                $path
            ));
        }

        chgrp($path, $group);
    }
    /**
     * @return bool
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        $paths = glob($pattern, $flags);

        if (false === $paths) {
            throw new \RuntimeException(sprintf(
                'Filesystem::glob() failed to glob pattern: %s',
                $pattern
            ));
        }

        return $paths;
    }


    public function files(string $directory): array
    {
        $files = scandir($directory);

        if (false === $files) {
            throw new \RuntimeException(sprintf(
                'Filesystem::files() failed to scan directory: %s',
                $directory
            ));
        }

        return array_filter($files, function ($file) use ($directory) {
            return is_file($directory . DIRECTORY_SEPARATOR . $file);
        });
    }
    /**
     * @return array<<missing>|array-key,<missing>>
     */
    public function allFiles(string $directory): array
    {
        $files = [];

        foreach ($this->directories($directory) as $directory) {
            $files = array_merge($files, $this->allFiles($directory));
        }

        return array_merge($files, $this->files($directory));
    }


    public function directories(string $directory): array
    {
        $directories = scandir($directory);

        if (false === $directories) {
            throw new \RuntimeException(sprintf(
                'Filesystem::directories() failed to scan directory: %s',
                $directory
            ));
        }

        return array_filter(
            $directories, 
            static fn (
                string $directory
            ): bool => is_dir($directory . DIRECTORY_SEPARATOR . $directory)
        );
    }
}
