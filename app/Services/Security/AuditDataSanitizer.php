<?php

declare(strict_types=1);

namespace App\Services\Security;

class AuditDataSanitizer
{
    /**
     * Strict list of keys that must NEVER be stored in audit logs.
     */
    protected const REDACTED_KEYS = [
        'password',
        'password_confirmation',
        'remember_token',
        'otp',
        'otp_code',
        'token',
        'access_token',
        'refresh_token',
        'secret',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'totp_secret',
        'mfa_secret',
        'mfa_recovery_codes',
        'api_key',
        'private_key',
    ];

    /**
     * Mask value for redacted fields.
     */
    protected const MASK = '********';

    /**
     * Sanitize an array of model attributes or changes.
     *
     * @param  array<string, mixed>|null  $data
     * @return array<string, mixed>|null
     */
    public static function sanitize(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        $sanitized = [];

        foreach ($data as $key => $value) {
            $normalizedKey = strtolower((string) $key);

            if (self::isRedactedKey($normalizedKey)) {
                $sanitized[$key] = self::MASK;

                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = self::sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Determine if the key should be redacted.
     */
    protected static function isRedactedKey(string $key): bool
    {
        if (in_array($key, self::REDACTED_KEYS, true)) {
            return true;
        }

        foreach (self::REDACTED_KEYS as $redactedKey) {
            if (str_contains($key, $redactedKey)) {
                return true;
            }
        }

        return false;
    }
}
