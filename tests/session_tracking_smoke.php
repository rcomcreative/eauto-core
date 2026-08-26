<?php

declare(strict_types=1);

use Eauto\Core\Models\SessionDurationPolicy;
use Eauto\Core\Models\UserSession;
use Eauto\Core\Services\SessionDurationPolicyResolver;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Carbon;
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

$container = new Container;
$container->instance('config', new Repository([
    'permission' => [],
    'scout' => [
        'after_commit' => false,
        'soft_delete' => false,
    ],
]));
$container->instance('events', new Dispatcher($container));
$container->instance('log', new NullLogger);
Container::setInstance($container);
Facade::setFacadeApplication($container);
$capsule = new Capsule($container);
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => ':memory:',
]);
$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->schema()->create('session_duration_policies', function (Blueprint $table): void {
    $table->id();
    $table->string('scope_type');
    $table->unsignedBigInteger('scope_id');
    $table->string('audience');
    $table->unsignedInteger('inactivity_minutes');
    $table->unsignedInteger('absolute_lifetime_minutes');
    $table->boolean('enabled')->default(true);
    $table->unsignedBigInteger('created_by_user_id')->nullable();
    $table->unsignedBigInteger('updated_by_user_id')->nullable();
    $table->timestamps();
});

$systemMember = SessionDurationPolicy::query()->create([
    'scope_type' => SessionDurationPolicy::SCOPE_SYSTEM,
    'scope_id' => 0,
    'audience' => SessionDurationPolicy::AUDIENCE_TEAM_MEMBER,
    'inactivity_minutes' => 120,
    'absolute_lifetime_minutes' => 720,
]);

$systemAdmin = SessionDurationPolicy::query()->create([
    'scope_type' => SessionDurationPolicy::SCOPE_SYSTEM,
    'scope_id' => 0,
    'audience' => SessionDurationPolicy::AUDIENCE_ADMIN,
    'inactivity_minutes' => 480,
    'absolute_lifetime_minutes' => 720,
]);

$departmentPolicy = SessionDurationPolicy::query()->create([
    'scope_type' => SessionDurationPolicy::SCOPE_DEPARTMENT,
    'scope_id' => 266,
    'audience' => SessionDurationPolicy::AUDIENCE_TEAM_MEMBER,
    'inactivity_minutes' => 360,
    'absolute_lifetime_minutes' => 720,
]);

$userPolicy = SessionDurationPolicy::query()->create([
    'scope_type' => SessionDurationPolicy::SCOPE_USER,
    'scope_id' => 42,
    'audience' => SessionDurationPolicy::AUDIENCE_TEAM_MEMBER,
    'inactivity_minutes' => 300,
    'absolute_lifetime_minutes' => 720,
]);

$resolver = new SessionDurationPolicyResolver;

assert($resolver->resolveForIds(42, 266, false)->is($userPolicy));

$userPolicy->update(['enabled' => false]);
assert($resolver->resolveForIds(42, 266, false)->is($departmentPolicy));

$departmentPolicy->update(['enabled' => false]);
assert($resolver->resolveForIds(42, 266, false)->is($systemMember));
assert($resolver->resolveForIds(42, 266, true)->is($systemAdmin));

$now = Carbon::parse('2026-08-26 12:00:00', 'UTC');
$session = new UserSession([
    'signed_in_at' => $now,
    'last_activity_at' => $now,
    'inactivity_expires_at' => $now->copy()->addMinutes(120),
    'absolute_expires_at' => $now->copy()->addMinutes(720),
]);

assert($session->isActive($now->copy()->addMinutes(119)));
assert(! $session->isActive($now->copy()->addMinutes(120)));

$invalidPolicyRejected = false;

try {
    SessionDurationPolicy::query()->create([
        'scope_type' => SessionDurationPolicy::SCOPE_SYSTEM,
        'scope_id' => 0,
        'audience' => SessionDurationPolicy::AUDIENCE_TEAM_MEMBER,
        'inactivity_minutes' => 800,
        'absolute_lifetime_minutes' => 720,
    ]);
} catch (InvalidArgumentException) {
    $invalidPolicyRejected = true;
}

assert($invalidPolicyRejected);

fwrite(STDOUT, "Session policy precedence, validation, and active-state checks passed.\n");
