<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Services\Security\AuditDataSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class AuditLogObserver
{
    public function created(Model $model): void
    {
        $this->logEvent($model, 'CREATED', null, AuditDataSanitizer::sanitize($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
        $newValues = $model->getChanges();

        unset($oldValues['updated_at'], $newValues['updated_at']);

        if (!empty($newValues)) {
            $this->logEvent(
                $model,
                'UPDATED',
                AuditDataSanitizer::sanitize($oldValues),
                AuditDataSanitizer::sanitize($newValues)
            );
        }
    }

    public function deleted(Model $model): void
    {
        $this->logEvent($model, 'DELETED', AuditDataSanitizer::sanitize($model->getAttributes()), null);
    }

    public function restored(Model $model): void
    {
        $this->logEvent($model, 'RESTORED', null, AuditDataSanitizer::sanitize($model->getAttributes()));
    }

    private function logEvent(Model $model, string $event, ?array $oldValues, ?array $newValues): void
    {
        $agentParser = new Agent();

        // Deteksi apakah dijalankan dari Web/API atau dari Background Job/Terminal Console
        $userAgentString = request()->userAgent() ?? 'System Console / Background Job';
        $agentParser->setUserAgent($userAgentString);

        $ip = request()->ip() ?? '127.0.0.1';

        AuditLog::create([
            'user_id'     => Auth::id(), // Akan bernilai null jika dieksekusi oleh Job/Sistem
            'event'       => $event,
            'table_name'  => $model->getTable(),
            'record_id'   => (string) $model->getKey(),
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => $ip,
            'user_agent'  => $userAgentString,
            'device_type' => $agentParser->isPhone() ? 'Mobile' : ($agentParser->isTablet() ? 'Tablet' : 'Desktop'),
            'platform'    => $agentParser->platform() ?: 'Unknown OS',
            'browser'     => $agentParser->browser() ?: 'Unknown Browser',
            'is_robot'    => $agentParser->isRobot(),
        ]);
    }
}
