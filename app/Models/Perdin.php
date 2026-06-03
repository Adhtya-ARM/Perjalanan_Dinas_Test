<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Perdin extends Model
{
    use HasFactory;

    protected $table = 'perdin';

    protected $fillable = [
        'user_id',
        'kota_asal_id',
        'kota_tujuan_id',
        'tanggal_berangkat',
        'tanggal_pulang',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_pulang' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kotaAsal()
    {
        return $this->belongsTo(Kota::class, 'kota_asal_id');
    }

    public function kotaTujuan()
    {
        return $this->belongsTo(Kota::class, 'kota_tujuan_id');
    }

    public function getJarakAttribute(): float
    {
        $asal = $this->kotaAsal;
        $tujuan = $this->kotaTujuan;

        if (!$asal || !$tujuan) {
            return 0.0;
        }

        $earthRadius = 6371;

        $lat1 = deg2rad($asal->latitude);
        $lon1 = deg2rad($asal->longitude);
        $lat2 = deg2rad($tujuan->latitude);
        $lon2 = deg2rad($tujuan->longitude);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function getDurasiAttribute(): int
    {
        if (!$this->tanggal_berangkat || !$this->tanggal_pulang) {
            return 0;
        }

        $berangkat = Carbon::parse($this->tanggal_berangkat);
        $pulang = Carbon::parse($this->tanggal_pulang);

        if ($pulang->lt($berangkat)) {
            return 0;
        }

        if ($berangkat->equalTo($pulang)) {
            return 1;
        }

        return $berangkat->diffInDays($pulang);
    }

    public function getTarifHarianAttribute(): float
    {
        $asal = $this->kotaAsal;
        $tujuan = $this->kotaTujuan;

        if (!$asal || !$tujuan) {
            return 0.0;
        }

        if ($tujuan->is_overseas) {
            return 891000.0;
        }

        $jarak = $this->jarak;
        if ($jarak <= 60.0) {
            return 0.0;
        }

        if ($asal->provinsi === $tujuan->provinsi) {
            return 200000.0;
        }

        if ($asal->pulau === $tujuan->pulau) {
            return 250000.0;
        }

        return 300000.0;
    }

    public function getTarifHarianFormatAttribute(): string
    {
        $tujuan = $this->kotaTujuan;
        if ($tujuan && $tujuan->is_overseas) {
            return "USD 50 (Rp " . number_format(891000, 0, ',', '.') . ")";
        }
        return "Rp " . number_format($this->tarif_harian, 0, ',', '.');
    }

    public function getTotalUangSakuAttribute(): float
    {
        return $this->durasi * $this->tarif_harian;
    }

    public function getTotalUangSakuFormatAttribute(): string
    {
        $tujuan = $this->kotaTujuan;
        if ($tujuan && $tujuan->is_overseas) {
            $totalUsd = $this->durasi * 50;
            return "USD " . number_format($totalUsd, 0, ',', '.') . " (Rp " . number_format($this->total_uang_saku, 0, ',', '.') . ")";
        }
        return "Rp " . number_format($this->total_uang_saku, 0, ',', '.');
    }
}
