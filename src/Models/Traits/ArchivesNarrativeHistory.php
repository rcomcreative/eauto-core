<?php

namespace Eauto\Core\Models\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ArchivesNarrativeHistory
{
    public static function bootArchivesNarrativeHistory(): void
    {
        static::updating(function ($model) {
            Log::info('ArchivesNarrativeHistory updating', [
                'model' => get_class($model),
                'id' => $model->getKey(),
            ]);

            $model->archiveCurrentState();
        });
    }

    protected function archiveCurrentState(): void
    {
        $historyModelClass = $this->getHistoryModelClass();

        Log::info('ArchivesNarrativeHistory archiveCurrentState', [
            'model' => static::class,
            'source_id' => $this->getKey(),
            'history_model' => $historyModelClass,
        ]);

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

        Log::info('ArchivesNarrativeHistory creating history row', [
            'history_model' => $historyModelClass,
            'source_id' => $this->getKey(),
        ]);

        $historyRecord = $historyModelClass::create($historyData);

        Log::info('ArchivesNarrativeHistory history row created', [
            'history_model' => $historyModelClass,
            'history_id' => $historyRecord->getKey(),
            'source_id' => $this->getKey(),
        ]);
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