<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AktivitasDosen;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ValidatesAktivitasDosen
{

    protected function validasiAktivitas(
        Request $request,
        ?AktivitasDosen $aktivitas = null,
        ?int $userId = null,
        array $aturanTambahan = [],
        array $atributTambahan = []
    ): array {
        $jenis = (string) $request->input('jenis');
        $request->merge([
            'judul' => preg_replace('/\s+/u', ' ', trim((string) $request->input('judul'))),
        ]);

        $butuhSkala = in_array($jenis, [AktivitasDosen::JENIS_PENELITIAN, AktivitasDosen::JENIS_ABDIMAS], true);
        $butuhPeran = in_array($jenis, [AktivitasDosen::JENIS_PENELITIAN, AktivitasDosen::JENIS_ABDIMAS, AktivitasDosen::JENIS_PRODUK], true);
        $butuhSub = in_array($jenis, [AktivitasDosen::JENIS_PRODUK, AktivitasDosen::JENIS_KOLABORASI, AktivitasDosen::JENIS_KEPEMIMPINAN], true);
        $butuhMitra = $jenis === AktivitasDosen::JENIS_KOLABORASI;
        $adaMitra = $jenis !== AktivitasDosen::JENIS_KEPEMIMPINAN;

        $tahunSekarang = (int) date('Y');
        $tahunInput = (int) $request->input('tahun');

        $aturan = array_merge([
            'jenis' => ['required', Rule::in(AktivitasDosen::jenisKeys())],
            'judul' => [
                'required', 'string', 'min:5', 'max:255', new SafeText,
                Rule::unique('aktivitas_dosen', 'judul')
                    ->where(fn ($q) => $q->where('user_id', $userId)->where('jenis', $jenis)->where('tahun', $tahunInput))
                    ->ignore($aktivitas?->id),
            ],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . $tahunSekarang],
            'skala' => $butuhSkala
                ? ['required', Rule::in(array_keys(AktivitasDosen::skalaList()))]
                : ['nullable'],
            'peran' => $butuhPeran
                ? ['required', Rule::in(array_keys(AktivitasDosen::peranList()))]
                : ['nullable'],
            'sub_jenis' => $butuhSub
                ? ['required', Rule::in(array_keys(AktivitasDosen::subJenisList($jenis)))]
                : ['nullable'],
            'bidang_smart_city' => ['nullable', Rule::in(array_keys(config('dss.bidang_smart_city', [])))],
            'mitra' => [$butuhMitra ? 'required' : 'nullable', 'string', 'min:2', 'max:150', new SafeText],
            'keterangan' => ['nullable', 'string', 'max:1000', new SafeText],
        ], $aturanTambahan);

        $data = $request->validate($aturan, [
            'judul.unique' => 'Aktivitas dengan judul, jenis, dan tahun yang sama sudah tercatat.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun berjalan.',
            'skala.required' => 'Skala wajib dipilih.',
            'peran.required' => 'Peran wajib dipilih.',
            'sub_jenis.required' => 'Pilihan ini wajib diisi.',
            'mitra.required' => 'Nama mitra wajib diisi untuk jenis kolaborasi.',
        ], array_merge([
            'jenis' => 'Jenis aktivitas',
            'judul' => 'Judul',
            'tahun' => 'Tahun',
            'skala' => 'Skala',
            'peran' => 'Peran',
            'sub_jenis' => 'Pilihan jenis',
            'bidang_smart_city' => 'Bidang Smart City',
            'mitra' => 'Mitra',
            'keterangan' => 'Keterangan',
        ], $atributTambahan));

        $data['skala'] = $butuhSkala ? ($data['skala'] ?? null) : null;
        $data['sub_jenis'] = $butuhSub ? ($data['sub_jenis'] ?? null) : null;
        $data['peran'] = $butuhPeran ? $data['peran'] : 'anggota';
        $data['mitra'] = $adaMitra ? ($data['mitra'] ?? null) : null;
        $data['bidang_smart_city'] = $data['bidang_smart_city'] ?? null;
        $data['keterangan'] = $data['keterangan'] ?? null;
        $data['berkelanjutan'] = $jenis === AktivitasDosen::JENIS_ABDIMAS && $request->boolean('berkelanjutan');

        return $data;
    }
}