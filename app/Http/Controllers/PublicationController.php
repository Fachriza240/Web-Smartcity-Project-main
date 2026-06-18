<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Publication::published()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('abstrak', 'like', "%{$search}%")
                        ->orWhere('penerbit', 'like', "%{$search}%")
                        ->orWhere('doi', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori'), fn($query) => $query->where('kategori', $request->kategori))
            ->when($request->filled('tahun'), fn($query) => $query->where('tahun', $request->tahun))
            ->orderByDesc('tahun')
            ->latest();

        $publications = $query->paginate(8)->withQueryString();

        $years = Publication::published()
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // Cek apakah user adalah dosen yang sudah login
        $isDosen = auth()->check() && auth()->user()->role === 'dosen';

        return view('halaman-user.publication-user', [
            'publications' => $publications,
            'categories' => Publication::categories(),
            'years' => $years,
            'isDosen' => $isDosen,
        ]);
    }

    public function show(Publication $publication)
    {
        abort_unless($publication->status === Publication::STATUS_PUBLISH, 404);

        return view('publications.show', compact('publication'));
    }

    public function download(Publication $publication)
    {
        abort_unless($publication->status === Publication::STATUS_PUBLISH, 404);
        abort_unless($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path), 404);

        $filename = str($publication->judul)->slug()->append('.pdf')->toString();

        return Storage::disk('public')->download($publication->pdf_path, $filename);
    }
}
