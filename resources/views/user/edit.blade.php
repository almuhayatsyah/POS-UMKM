@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Edit Pengguna</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Pengguna</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required />
                            @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="peran">Peran (Role)</label>
                            <select class="form-select" id="peran" name="peran" required>
                                <option value="ADMIN" {{ old('peran', $user->peran) == 'ADMIN' ? 'selected' : '' }}>Admin (Pemilik)</option>
                                <option value="KASIR" {{ old('peran', $user->peran) == 'KASIR' ? 'selected' : '' }}>Kasir</option>
                                <option value="DAPUR" {{ old('peran', $user->peran) == 'DAPUR' ? 'selected' : '' }}>Staf Dapur</option>
                            </select>
                            @error('peran') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <hr class="my-4">
                        <small class="text-muted d-block mb-3">Kosongkan jika tidak ingin mengubah kata sandi.</small>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Kata Sandi Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
