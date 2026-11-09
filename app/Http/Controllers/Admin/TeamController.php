<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Rules\PersonName;
use App\Rules\SafeText;
use App\Services\PublicFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'teams' => $teams,
            'statuses' => Team::statuses(),
            'tipes' => Team::tipes(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.teams.create', [
            'team' => new Team([
                'status' => Team::STATUS_DRAFT,
                'tipe' => Team::TIPE_STAFF,
            ]),
            'statuses' => Team::statuses(),
            'tipes' => Team::tipes(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        unset($data['foto']);

        app(PublicFileService::class)->save(new Team, $data, [
            'foto_path' => [$request->file('foto'), 'teams/photos'],
        ]);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(Team $team)
    {
        $this->authorizeContentManager();

        return view('admin.teams.edit', [
            'team' => $team,
            'statuses' => Team::statuses(),
            'tipes' => Team::tipes(),
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $team);
        unset($data['foto']);

        app(PublicFileService::class)->save($team, $data, [
            'foto_path' => [$request->file('foto'), 'teams/photos'],
        ]);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(Team $team)
    {
        $this->authorizeContentManager();

        app(PublicFileService::class)->delete($team, ['foto_path']);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Anggota tim berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Team $team = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:100', new PersonName],
            'jabatan' => ['required', 'string', 'min:2', 'max:100', new SafeText],
            'bidang' => ['nullable', 'string', 'min:2', 'max:150', new SafeText],
            'foto' => [$team ? 'nullable' : 'required', 'image', 'max:4096'],
            'email' => ['nullable', 'email:rfc', 'min:6', 'max:254'],
            'telepon' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'linkedin' => ['nullable', 'url', 'max:500'],
            'instagram' => ['nullable', 'string', 'regex:/^@?[A-Za-z0-9._]{1,30}$/'],
            'github' => ['nullable', 'string', 'regex:/^[A-Za-z0-9-]{1,39}$/'],
            'tipe' => ['required', Rule::in(Team::tipes())],
            'status' => ['required', Rule::in(Team::statuses())],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}