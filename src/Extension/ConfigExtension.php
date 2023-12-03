<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Config\Config;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Throwable;

/**
 * @implements ExtensionInterface<Config>
 */
final readonly class ConfigExtension implements ExtensionInterface
{
    /**
     * @param Config $service
     *
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container, object $service): Config
    {
        $currentWorkingDirectory = getenv('GITHUB_WORKSPACE') ?: getcwd() ?: dirname(__DIR__);

        $result = chdir($currentWorkingDirectory);
        if ($result === false) {
            error(
                sprintf(
                    'Unable to change current working directory; %s; "%s" given.',
                    error_get_last()['message'] ?? 'No such file or directory',
                    $currentWorkingDirectory
                ),
            __FILE__,
            __LINE__
            );
        }

        $service->set(Compliance::CURRENT_WORKING_DIRECTORY, $currentWorkingDirectory);

        $complianceWorkflowTemplate = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'src/compliance.yml.dist';
        if (file_exists($complianceWorkflowTemplate)) {
            $service->set(Compliance::WORKFLOW_TEMPLATE, realpath($complianceWorkflowTemplate));
        }

        return $service;
    }
}
