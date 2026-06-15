<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $partners = Partner::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('nama', 'like', "%{$request->search}%")
                      ->orWhere('deskripsi', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('urutan')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.partners.index', [
            'partners' => $partners,
            'statuses' => Partner::statuses(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.partners.create', [
            'partner'  => new Partner(['status' => Partner::STATUS_DRAFT]),
            'statuses' => Partner::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('partners/logos', 'public');
        }
        unset($data['logo']);

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        $this->authorizeContentManager();

        return view('admin.partners.edit', [
            'partner'  => $partner,
            'statuses' => Partner::statuses(),
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $partner);

        if ($request->hasFile('logo')) {
            $this->deleteFile($partner->logo_path);
            $data['logo_path'] = $request->file('logo')->store('partners/logos', 'public');
        }
        unset($data['logo']);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        $this->authorizeContentManager();

        $this->deleteFile($partner->logo_path);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Partner $partner = null): array
    {
        return $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'logo'      => ['nullable', 'image', 'max:4096'],
            'website'   => ['nullable', 'url', 'max:500'],
            'status'    => ['required', Rule::in(Partner::statuses())],
            'urutan'    => ['nullable', 'integer'],
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
