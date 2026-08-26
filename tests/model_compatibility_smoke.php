<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Psr\Log\NullLogger;

$packageRoot = dirname(__DIR__);
$autoloadPath = getenv('EAUTO_CORE_AUTOLOAD') ?: $packageRoot.'/vendor/autoload.php';

if (! is_file($autoloadPath)) {
    throw new RuntimeException("Unable to locate Composer autoload file at {$autoloadPath}.");
}

require $autoloadPath;

spl_autoload_register(function (string $class) use ($packageRoot): void {
    $prefix = 'Eauto\\Core\\';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = $packageRoot.'/src/'.str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass).'.php';

    if (is_file($path)) {
        require $path;
    }
}, prepend: true);

$application = new Application($packageRoot);

Facade::setFacadeApplication($application);

$application->instance('config', new Repository([
    'media-library' => [],
    'permission' => [],
    'scout' => [
        'after_commit' => false,
        'soft_delete' => false,
    ],
]));
$application->instance('events', new Dispatcher($application));
$application->instance('log', new NullLogger());

$sourceDirectory = realpath($packageRoot.'/src');

if ($sourceDirectory === false) {
    throw new RuntimeException('Unable to locate the package source directory.');
}

$modelDirectory = $sourceDirectory.DIRECTORY_SEPARATOR.'Models';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modelDirectory));
$modelCount = 0;

foreach ($files as $file) {
    if (! $file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $relativePath = substr(
        $file->getRealPath(),
        strlen($sourceDirectory.DIRECTORY_SEPARATOR),
        -4,
    );
    $class = 'Eauto\\Core\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

    if (! class_exists($class)) {
        continue;
    }

    $reflection = new ReflectionClass($class);

    if ($reflection->isAbstract() || $reflection->isTrait() || $reflection->isEnum()) {
        continue;
    }

    $instance = $reflection->newInstance();

    if (! $instance instanceof Model) {
        continue;
    }

    $instance->getTable();
    $instance->getFillable();
    $instance->getCasts();
    $modelCount++;
}

if ($modelCount === 0) {
    throw new RuntimeException('No Eloquent models were discovered by the compatibility test.');
}

fwrite(
    STDOUT,
    sprintf(
        "Instantiated %d model classes on Laravel %s.%s",
        $modelCount,
        $application->version(),
        PHP_EOL,
    ),
);
