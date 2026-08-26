<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class UserSession extends Model
{
    public const APPLICATION_ADMIN = 'admin';

    public const APPLICATION_CLIENT = 'client';

    public const TERMINATION_MANUAL_SIGN_OUT = 'manual_sign_out';

    public const TERMINATION_INACTIVITY_TIMEOUT = 'inactivity_timeout';

    public const TERMINATION_ABSOLUTE_TIMEOUT = 'absolute_timeout';

    public const TERMINATION_ADMIN_REVOKED = 'admin_revoked';

    public const TERMINATION_USER_REVOKED_ALL = 'user_revoked_all';

    public const TERMINATION_ACCOUNT_DISABLED = 'account_disabled';

    public const TERMINATION_MEMBERSHIP_DISABLED = 'membership_disabled';

    public const TERMINATION_SESSION_REPLACED = 'session_replaced';

    public const TERMINATION_UNKNOWN = 'unknown';

    protected $fillable = [
        'session_uuid',
        'laravel_session_id',
        'user_id',
        'team_id',
        'department_id',
        'application',
        'guard_name',
        'is_admin_session',
        'role_at_sign_in',
        'user_name',
        'user_email',
        'team_name',
        'department_name',
        'session_duration_policy_id',
        'policy_scope_type',
        'inactivity_minutes',
        'absolute_lifetime_minutes',
        'signed_in_at',
        'last_activity_at',
        'inactivity_expires_at',
        'absolute_expires_at',
        'signed_out_at',
        'termination_reason',
        'revoked_by_user_id',
        'revoked_at',
        'termination_notes',
        'ip_address',
        'last_ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'is_admin_session' => 'boolean',
            'inactivity_minutes' => 'integer',
            'absolute_lifetime_minutes' => 'integer',
            'signed_in_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'inactivity_expires_at' => 'datetime',
            'absolute_expires_at' => 'datetime',
            'signed_out_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public static function terminationReasons(): array
    {
        return [
            self::TERMINATION_MANUAL_SIGN_OUT,
            self::TERMINATION_INACTIVITY_TIMEOUT,
            self::TERMINATION_ABSOLUTE_TIMEOUT,
            self::TERMINATION_ADMIN_REVOKED,
            self::TERMINATION_USER_REVOKED_ALL,
            self::TERMINATION_ACCOUNT_DISABLED,
            self::TERMINATION_MEMBERSHIP_DISABLED,
            self::TERMINATION_SESSION_REPLACED,
            self::TERMINATION_UNKNOWN,
        ];
    }

    public function isActive(?Carbon $at = null): bool
    {
        $at ??= now();

        return $this->signed_out_at === null
            && $this->revoked_at === null
            && $this->inactivity_expires_at->isAfter($at)
            && $this->absolute_expires_at->isAfter($at);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function durationPolicy(): BelongsTo
    {
        return $this->belongsTo(SessionDurationPolicy::class, 'session_duration_policy_id');
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by_user_id');
    }
}
