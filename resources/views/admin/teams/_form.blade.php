@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $team->nama) }}" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Urutan</label>
        <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $team->urutan ?? 0) }}" min="0">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
               value="{{ old('jabatan', $team->jabatan) }}" required>
        @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Bidang Keahlian</label>
        <input type="text" name="bidang" class="form-control"
               value="{{ old('bidang', $team->bidang) }}" placeholder="Contoh: AI, IoT, Web Development">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipe <span class="text-danger">*</span></label>
        <select name="tipe" class="form-select" required>
            @foreach($tipes as $tipe)
                <option value="{{ $tipe }}" @selected(old('tipe', $team->tipe) === $tipe)>{{ $tipe }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $team->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Foto {{ $team->exists ? '' : '<span class="text-danger">*</span>' }}</label>
    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
           accept="image/*" @if(!$team->exists) required @endif>
    @if($team->foto_path)
        <div class="mt-2">
            <img src="{{ asset('storage/'.$team->foto_path) }}" alt="{{ $team->nama }}"
                 style="width:80px;height:80px;object-fit:cover;border-radius:50%;">
            <div class="small text-muted mt-1">Foto saat ini. Upload baru untuk mengganti.</div>
        </div>
    @endif
    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr>
<h6 class="mb-3 text-muted"><i class="bi bi-person-lines-fill me-1"></i>Kontak & Sosial Media</h6>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $team->email) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Telepon</label>
        <input type="text" name="telepon" class="form-control"
               value="{{ old('telepon', $team->telepon) }}" placeholder="+62 xxx xxxx xxxx">
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label"><i class="bi bi-linkedin me-1 text-primary"></i>LinkedIn URL</label>
        <input type="url" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror"
               value="{{ old('linkedin', $team->linkedin) }}" placeholder="https://linkedin.com/in/...">
        @error('linkedin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label"><i class="bi bi-instagram me-1 text-danger"></i>Instagram</label>
        <input type="text" name="instagram" class="form-control"
               value="{{ old('instagram', $team->instagram) }}" placeholder="@username">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label"><i class="bi bi-github me-1"></i>GitHub</label>
        <input type="text" name="github" class="form-control"
               value="{{ old('github', $team->github) }}" placeholder="username">
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('admin.teams.index') }}" class="btn btn-light">Batal</a>
</div>
