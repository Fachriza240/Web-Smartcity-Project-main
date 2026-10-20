<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatistikProyek;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StatistikProyekController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $tipeDipilih = $request->query('tipe');

        if (! in_array($tipeDipilih, StatistikProyek::tipeKeys(), true)) {
            $tipeDipilih = StatistikProyek::TIPE_INTERNASIONAL;
        }

        return view('admin.statistik-proyek.index', [
            'tipes' => StatistikProyek::tipes(),
            'tipeDipilih' => $tipeDipilih,
            'daftar' => StatistikProyek::orderBy('urutan')->orderBy('id')->get()->groupBy('tipe'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);

        if (! isset($data['urutan'])) {
            $data['urutan'] = (int) StatistikProyek::where('tipe', $data['tipe'])->max('urutan') + 1;
        }

        StatistikProyek::create($data);

        return redirect()
            ->route('admin.statistik-proyek.index', ['tipe' => $data['tipe']])
            ->with('success', StatistikProyek::tipes()[$data['tipe']] . ' berhasil ditambahkan.');
    }

    public function edit(StatistikProyek $statistikProyek)
    {
        $this->authorizeContentManager();

        return view('admin.statistik-proyek.edit', [
            'item' => $statistikProyek,
            'tipes' => StatistikProyek::tipes(),
        ]);
    }

    public function update(Request $request, StatistikProyek $statistikProyek)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $statistikProyek);

        if (! isset($data['urutan'])) {
            $data['urutan'] = $statistikProyek->urutan;
        }

        $statistikProyek->update($data);

        return redirect()
            ->route('admin.statistik-proyek.index', ['tipe' => $statistikProyek->tipe])
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(StatistikProyek $statistikProyek)
    {
        $this->authorizeContentManager();

        $tipe = $statistikProyek->tipe;
        $statistikProyek->delete();

        return redirect()
            ->route('admin.statistik-proyek.index', ['tipe' => $tipe])
            ->with('success', 'Data berhasil dihapus.');
    }

    private function validatedData(Request $request, ?StatistikProyek $item = null): array
    {
        $request->merge(['nama' => preg_replace('/\s+/u', ' ', trim((string) $request->input('nama')))]);

        return $request->validate([
            'tipe' => ['required', Rule::in(StatistikProyek::tipeKeys())],
            'nama' => [
                'required',
                'string',
                'min:2',
                'max:150',
                new SafeText,
                Rule::unique('statistik_proyek', 'nama')
                    ->where('tipe', $request->input('tipe'))
                    ->ignore($item?->id),
            ],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'nama.unique' => 'Nama tersebut sudah ada pada kategori yang dipilih.',
        ], [
            'tipe' => 'Kategori',
            'nama' => 'Nama',
            'urutan' => 'Urutan',
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }
}