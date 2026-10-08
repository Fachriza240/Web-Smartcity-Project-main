@csrf

<div class="row">
    <div class="col-md-8 mb-3" data-field>
        <label for="nama" class="form-label">Nama Anggota <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $team->nama) }}" required
               data-label="Nama" data-rules="required|min:3|max:100|name">
        <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
        @error('nama')<div class="invalid-feedback d-block" data-error-for="nama">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
               value="{{ old('urutan', $team->urutan ?? 0) }}" min="0" max="9999"
               data-label="Urutan" data-rules="range:0,9999">
        @error('urutan')<div class="invalid-feedback d-block" data-error-for="urutan">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
        <input type="text" id="jabatan" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
               value="{{ old('jabatan', $team->jabatan) }}" required
               data-label="Jabatan" data-rules="required|min:2|max:100|safe">
        @error('jabatan')<div class="invalid-feedback d-block" data-error-for="jabatan">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="bidang" class="form-label">Bidang Keahlian</label>
        <input type="text" id="bidang" name="bidang" class="form-control @error('bidang') is-invalid @enderror"
               value="{{ old('bidang', $team->bidang) }}" placeholder="Contoh: AI, IoT, Web Development"
               data-label="Bidang" data-rules="min:2|max:150|safe">
        @error('bidang')<div class="invalid-feedback d-block" data-error-for="bidang">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror" required data-label="Tipe" data-rules="required">
            @foreach ($tipes as $tipe)
                <option value="{{ $tipe }}" @selected(old('tipe', $team->tipe) === $tipe)>{{ $tipe }}</option>
            @endforeach
        </select>
        @error('tipe')<div class="invalid-feedback d-block" data-error-for="tipe">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $team->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="foto" class="form-label">Foto @unless ($team->exists)<span class="text-danger">*</span>@endunless</label>
    <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror"
           accept="image/*" @if (! $team->exists) required @endif
           data-label="Foto" data-rules="{{ $team->exists ? '' : 'required|' }}file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
    <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
    @if ($team->foto_path)
        <div class="mt-2">
            <img class="adm-avatar-lg" src="{{ asset('storage/'.$team->foto_path) }}" alt="{{ $team->nama }}">
            <div class="small text-muted mt-1">Foto saat ini. Unggah file baru untuk mengganti.</div>
        </div>
    @endif
    @error('foto')<div class="invalid-feedback d-block" data-error-for="foto">{{ $message }}</div>@enderror
</div>

<hr>
<h6 class="mb-3 text-muted"><i class="bi bi-person-lines-fill me-1" aria-hidden="true"></i>Kontak & Sosial Media</h6>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $team->email) }}" placeholder="nama@contoh.com"
               data-label="Email" data-rules="email|min:6|max:254">
        @error('email')<div class="invalid-feedback d-block" data-error-for="email">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="telepon" class="form-label">Telepon</label>
        <input type="text" id="telepon" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
               value="{{ old('telepon', $team->telepon) }}" placeholder="+62 xxx xxxx xxxx"
               data-label="Telepon" data-rules="pattern" data-pattern="^[0-9+\-\s()]{6,20}$"
               data-pattern-message="Telepon hanya boleh berisi angka, spasi, tanda plus, tanda hubung, dan kurung (6 sampai 20 karakter).">
        @error('telepon')<div class="invalid-feedback d-block" data-error-for="telepon">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3" data-field>
        <label for="linkedin" class="form-label"><i class="bi bi-linkedin me-1 text-primary" aria-hidden="true"></i>URL LinkedIn</label>
        <input type="url" id="linkedin" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror"
               value="{{ old('linkedin', $team->linkedin) }}" placeholder="https://linkedin.com/in/..."
               data-label="LinkedIn" data-rules="url|max:500">
        @error('linkedin')<div class="invalid-feedback d-block" data-error-for="linkedin">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="instagram" class="form-label"><i class="bi bi-instagram me-1 text-danger" aria-hidden="true"></i>Instagram</label>
        <input type="text" id="instagram" name="instagram" class="form-control @error('instagram') is-invalid @enderror"
               value="{{ old('instagram', $team->instagram) }}" placeholder="@username"
               data-label="Instagram" data-rules="pattern" data-pattern="^@?[A-Za-z0-9._]{1,30}$"
               data-pattern-message="Instagram hanya boleh berisi huruf, angka, titik, dan garis bawah (maksimal 30 karakter).">
        @error('instagram')<div class="invalid-feedback d-block" data-error-for="instagram">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="github" class="form-label"><i class="bi bi-github me-1" aria-hidden="true"></i>GitHub</label>
        <input type="text" id="github" name="github" class="form-control @error('github') is-invalid @enderror"
               value="{{ old('github', $team->github) }}" placeholder="username"
               data-label="GitHub" data-rules="pattern" data-pattern="^[A-Za-z0-9-]{1,39}$"
               data-pattern-message="GitHub hanya boleh berisi huruf, angka, dan tanda hubung (maksimal 39 karakter).">
        @error('github')<div class="invalid-feedback d-block" data-error-for="github">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan data anggota tim ini?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.teams.index') }}" class="btn btn-light">Batal</a>
</div>
