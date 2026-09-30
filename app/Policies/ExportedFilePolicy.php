<?php

namespace App\Policies;

use App\Models\ExportedFile;
use App\Models\User;

class ExportedFilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('penduduk.export');
    }

    public function download(User $user, ExportedFile $exportedFile): bool
    {
        return $user->hasPermission('penduduk.export')
            && (int) $exportedFile->user_id === (int) $user->id;
    }
}
