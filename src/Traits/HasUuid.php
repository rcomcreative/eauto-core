<?php

namespace Eauto\Core\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->{$key})) {
                $model->{$key} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing(): bool { return false; }
    public function getKeyType(): string { return 'string'; }
}