<?php

if (! function_exists('tanggal_valid')) {
    /**
     * Pastikan string berformat Y-m-d dan tanggalnya nyata.
     * Jika tidak valid, kembalikan $default (hari ini jika null).
     */
    function tanggal_valid(mixed $tanggal, ?string $default = null): string
    {
        $default ??= date('Y-m-d');

        if (! is_string($tanggal) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return $default;
        }

        [$tahun, $bulan, $hari] = array_map('intval', explode('-', $tanggal));

        return checkdate($bulan, $hari, $tahun) ? $tanggal : $default;
    }
}

if (! function_exists('tanggal_indo')) {
    /**
     * 2026-08-28 -> 28 Agustus 2026
     */
    function tanggal_indo(string $tanggal): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $ts = strtotime($tanggal);

        if ($ts === false) {
            return $tanggal;
        }

        return date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}