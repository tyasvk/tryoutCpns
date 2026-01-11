@extends('layouts.admin') {{-- Pastikan nama layout sesuai dengan project Anda --}}

@section('title', 'Profil Administrator')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Akun</h1>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-5 mb-4">
            <div class="card shadow border-left-primary h-100 py-2">
                <div class="card-body text-center">
                    <div class="mb-3">
                        {{-- Logic: Jika ada foto di DB pakai itu, jika tidak pakai default --}}
                        <img id="admin-preview" 
                             src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-profile.png') }}" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h4 class="font-weight-bold text-primary">{{ $user->name }}</h4>
                    <span class="badge badge-dark px-3 py-2">Administrator</span>
                    
                    <hr class="my-4">
                    
                    <div class="text-left small">
                        <div class="mb-2">
                            <i class="fas fa-envelope fa-fw mr-2 text-gray-400"></i> {{ $user->email }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-calendar fa-fw mr-2 text-gray-400"></i> Terdaftar: {{ $user->created_at->format('d M Y') }}
                        </div>
                        <div>
                            <i class="fas fa-laptop fa-fw mr-2 text-gray-400"></i> IP Terakhir: {{ request()->ip() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Informasi Akun</h6>
                </div>
                <div class="card-body">
                    {{-- Alert Sukses/Gagal --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email Login</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="photo">Ganti Foto Profil</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="admin-photo-input" name="photo" accept="image/*">
                                <label class="custom-file-label" for="admin-photo-input">Pilih file...</label>
                            </div>
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="password" class="text-danger">Ganti Password (Opsional)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group text-right mt-4">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script Preview Image Admin
    document.getElementById('admin-photo-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('admin-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
            // Update label nama file (jika pakai custom file input bootstrap)
            event.target.nextElementSibling.innerText = file.name;
        }
    });
</script>
@endpush