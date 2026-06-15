<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $teams = Team::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('nama', 'like', "%{$request->search}%")
                      ->orWhere('jabatan', 'like', "%{$request->search}%")
                      ->orWhere('bidang', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('tipe'), fn ($q) => $q->where('tipe', $request->tipe))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('urutan')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.teams.index', [
            'teams'    => $teams,
            'statuses' => Team::statuses(),
            'tipes'    => Team::tipes(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.teams.create', [
            'team'     => new Team(['status' => Team::STATUS_DRAFT, 'tipe' => Team::TIPE_STAFF]),
            'statuses' => Team::statuses(),
            'tipes'    => Team::tipes(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('teams/photos', 'public');
        }
        unset($data['foto']);

        Team::create($data);

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(Team $team)
    {
        $this->authorizeContentManager();

        return view('admin.teams.edit', [
            'team'     => $team,
            'statuses' => Team::statuses(),
            'tipes'    => Team::tipes(),
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $team);

        if ($request->hasFile('foto')) {
            $this->deleteFile($team->foto_path);
            $data['foto_path'] = $request->file('foto')->store('teams/photos', 'public');
        }
        unset($data['foto']);

        $team->update($data);

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(Team $team)
    {
        $this->authorizeContentManager();

        $this->deleteFile($team->foto_path);
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Team $team = null): array
    {
        return $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'jabatan'   => ['required', 'string', 'max:255'],
            'bidang'    => ['nullable', 'string', 'max:255'],
            'foto'      => [$team ? 'nullable' : 'required', 'image', 'max:4096'],
            'email'     => ['nullable', 'email', 'max:255'],
            'telepon'   => ['nullable', 'string', 'max:50'],
            'linkedin'  => ['nullable', 'url', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'github'    => ['nullable', 'string', 'max:255'],
            'tipe'      => ['required', Rule::in(Team::tipes())],
            'status'    => ['required', Rule::in(Team::statuses())],
            'urutan'    => ['nullable', 'integer'],
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
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
