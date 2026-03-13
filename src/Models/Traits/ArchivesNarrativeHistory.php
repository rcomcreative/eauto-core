<?php

namespace Eauto\Core\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait ArchivesNarrativeHistory
{
    public static function bootArchivesNarrativeHistory(): void
    {
        static::updating(function ($model) {
            $model->archiveCurrentState();
        });
    }

    protected function archiveCurrentState(): void
    {
        $historyModelClass = $this->getHistoryModelClass();

        if (! class_exists($historyModelClass)) {
            throw new \RuntimeException(sprintf(
                'History model class [%s] does not exist for [%s].',
                $historyModelClass,
                static::class
            ));
        }

        // Defensive safeguard: if the trait is ever added to a history model,
        // do not archive history rows into themselves.
        if ($this instanceof $historyModelClass) {
            return;
        }

        $historyData = $this->buildHistoryPayload();

        $historyModelClass::create($historyData);
    }

    protected function buildHistoryPayload(): array
    {
        $attributes = $this->getAttributes();

        unset($attributes['id']);

        return array_merge($attributes, [
            'source_id' => $this->getKey(),
            'edited_by_admin_id' => Auth::id() ?: null,
            'archived_at' => now(),
        ]);
    }

    abstract protected function getHistoryModelClass(): string;
}