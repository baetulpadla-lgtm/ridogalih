<?php

declare(strict_types=1);

namespace App\Services\Security;

class TotpService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    private const PERIOD_SECONDS = 30;

    public function generateSecret(): string
    {
        return $this->base32Encode(random_bytes(20));
    }

    /**
     * @return array<int, string>
     */
    public function generateRecoveryCodes(): array
    {
        return array_map(
            static fn (): string => strtoupper(bin2hex(random_bytes(12))),
            range(1, 8)
        );
    }

    public function provisioningUri(string $secret, string $account, string $issuer): string
    {
        $label = rawurlencode($issuer.':'.$account);

        return 'otpauth://totp/'.$label.'?'.http_build_query([
            'secret' => $secret,
            'issuer' => $issuer,
            'algorithm' => 'SHA1',
            'digits' => 6,
            'period' => self::PERIOD_SECONDS,
        ], '', '&', PHP_QUERY_RFC3986);
    }

    public function matchingStep(string $secret, string $code, ?int $timestamp = null): ?int
    {
        if (! preg_match('/^\d{6}$/', $code)) {
            return null;
        }

        $key = $this->base32Decode($secret);
        $currentStep = intdiv($timestamp ?? now()->timestamp, self::PERIOD_SECONDS);

        foreach ([-1, 0, 1] as $offset) {
            $step = $currentStep + $offset;
            if ($step >= 0 && hash_equals($this->codeForStep($key, $step), $code)) {
                return $step;
            }
        }

        return null;
    }

    public function currentCode(string $secret, ?int $timestamp = null): string
    {
        return $this->codeForStep(
            $this->base32Decode($secret),
            intdiv($timestamp ?? now()->timestamp, self::PERIOD_SECONDS)
        );
    }

    private function codeForStep(string $key, int $step): string
    {
        $counter = pack('N2', intdiv($step, 0x100000000), $step % 0x100000000);
        $hash = hash_hmac('sha1', $counter, $key, true);
        $offset = ord($hash[19]) & 0x0F;
        $binary = ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF);

        return str_pad((string) ($binary % 1_000_000), 6, '0', STR_PAD_LEFT);
    }

    private function base32Encode(string $value): string
    {
        $bits = '';
        foreach (unpack('C*', $value) as $byte) {
            $bits .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
        }

        $encoded = '';
        foreach (str_split($bits, 5) as $chunk) {
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            }
            $encoded .= self::BASE32_ALPHABET[bindec($chunk)];
        }

        return $encoded;
    }

    private function base32Decode(string $value): string
    {
        $value = strtoupper(rtrim($value, '='));
        if ($value === '' || ! preg_match('/^[A-Z2-7]+$/', $value)) {
            return '';
        }

        $bits = '';
        foreach (str_split($value) as $character) {
            $bits .= str_pad(decbin(strpos(self::BASE32_ALPHABET, $character)), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';
        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $bytes .= chr(bindec($chunk));
            }
        }

        return $bytes;
    }
}
