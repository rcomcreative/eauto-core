<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class SessionDurationPolicy extends Model
{
    public const SCOPE_SYSTEM = 'system';

    public const SCOPE_DEPARTMENT = 'department';

    public const SCOPE_USER = 'user';

    public const AUDIENCE_TEAM_MEMBER = 'team_member';

    public const AUDIENCE_ADMIN = 'admin';

    protected $fillable = [
        'scope_type',
        'scope_id',
        'audience',
        'inactivity_minutes',
        'absolute_lifetime_minutes',
        'enabled',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'scope_id' => 'integer',
            'inactivity_minutes' => 'integer',
            'absolute_lifetime_minutes' => 'integer',
            'enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $policy): void {
            if (! in_array($policy->scope_type, self::scopeTypes(), true)) {
                throw new InvalidArgumentException('The session policy scope type is invalid.');
            }

            if (! in_array($policy->audience, self::audiences(), true)) {
                throw new InvalidArgumentException('The session policy audience is invalid.');
            }

            if ($policy->scope_type === self::SCOPE_SYSTEM && (int) $policy->scope_id !== 0) {
                throw new InvalidArgumentException('System session policies must use scope ID 0.');
            }

            if ($policy->scope_type !== self::SCOPE_SYSTEM && (int) $policy->scope_id < 1) {
                throw new InvalidArgumentException('Department and user session policies require a valid scope ID.');
            }

            if ((int) $policy->inactivity_minutes < 1) {
                throw new InvalidArgumentException('The inactivity duration must be at least one minute.');
            }

            if ((int) $policy->absolute_lifetime_minutes < (int) $policy->inactivity_minutes) {
                throw new InvalidArgumentException('The absolute session lifetime cannot be shorter than the inactivity duration.');
            }
        });
    }

    public static function scopeTypes(): array
    {
        return [self::SCOPE_SYSTEM, self::SCOPE_DEPARTMENT, self::SCOPE_USER];
    }

    public static function audiences(): array
    {
        return [self::AUDIENCE_TEAM_MEMBER, self::AUDIENCE_ADMIN];
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function lastEditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
