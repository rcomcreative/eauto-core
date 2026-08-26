<?php

namespace Eauto\Core\Services;

use Eauto\Core\Models\Department;
use Eauto\Core\Models\SessionDurationPolicy;
use Eauto\Core\Models\User;
use RuntimeException;

class SessionDurationPolicyResolver
{
    public function resolve(User $user, ?Department $department, bool $isAdminSession): SessionDurationPolicy
    {
        return $this->resolveForIds(
            (int) $user->getKey(),
            $department !== null ? (int) $department->getKey() : null,
            $isAdminSession,
        );
    }

    public function resolveForIds(int $userId, ?int $departmentId, bool $isAdminSession): SessionDurationPolicy
    {
        $audience = $isAdminSession
            ? SessionDurationPolicy::AUDIENCE_ADMIN
            : SessionDurationPolicy::AUDIENCE_TEAM_MEMBER;

        $candidates = [
            [SessionDurationPolicy::SCOPE_USER, $userId],
        ];

        if ($departmentId !== null) {
            $candidates[] = [SessionDurationPolicy::SCOPE_DEPARTMENT, $departmentId];
        }

        $candidates[] = [SessionDurationPolicy::SCOPE_SYSTEM, 0];

        foreach ($candidates as [$scopeType, $scopeId]) {
            $policy = SessionDurationPolicy::query()
                ->enabled()
                ->where('scope_type', $scopeType)
                ->where('scope_id', $scopeId)
                ->where('audience', $audience)
                ->first();

            if ($policy !== null) {
                return $policy;
            }
        }

        throw new RuntimeException("No enabled {$audience} session duration policy is configured.");
    }
}
