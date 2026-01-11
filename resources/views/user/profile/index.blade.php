@extends('layouts.app') {{-- Layout standar user --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Alert Notifikasi --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        
                        <div class="col-md-4 bg-light text-center py-5 border-right">
                            <div class="px-4">
                                <div class="mb-4 position-relative mx-auto" style="width: 160px; height: 160px;">
                                    <img id="user-preview" 
                                         src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}" 
                                         class="rounded-circle border border-white shadow-sm w-100 h-100" 
                                         style="object-fit: cover;">
                                    <div class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 35px; height: 35px; bottom: 5px; right: 10px; cursor: pointer;"
                                         onclick="document.getElementById('user-photo-input').click();">
                                        <i class="fas fa-camera small"></i>
                                    </div>
                                </div>
                                
                                <h4 class="text-dark font-weight-bold mb-1">{{ $user->name }}</h4>
                                <p class="text-muted mb-4 badge badge-pill badge-primary px-3">Peserta Tryout</p>

                                <div class="text-left mt-4 small text-secondary">
                                    <p class="mb-2"><i class="fas fa-envelope mr-2 text-primary"></i> {{ $user->email }}</p>
                                    <p class="mb-2"><i class="fas fa-phone mr-2 text-success"></i> {{ $user->phone ?? 'Belum ada nomor' }}</p>
                                    <p class="mb-0"><i class="fas fa-map-marker-alt mr-2 text-danger"></i> {{ $user->address ?? 'Alamat belum diisi' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 p-5 bg-white">
                            <ul class="nav nav-tabs nav-justified mb-4" id="profileTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="true">
                                        <i class="fas fa-user-edit mr-2"></i> Edit Data
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
                                        <i class="fas fa-history mr-2"></i> Riwayat Tryout
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="profileTabContent">
                                <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <input type="file" name="photo" id="user-photo-input" style="display: none;" accept="image/*">

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-uppercase text-muted">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $user->name) }}">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-uppercase text-muted">No. WhatsApp</label>
                                                <input type="text" name="phone" class="form-control bg-light border-0" value="{{ old('phone', $user->phone) }}" placeholder="0812...">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-uppercase text-muted">Password Baru</label>
                                                <input type="password" name="password" class="form-control bg-light border-0" placeholder="(Opsional)">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-uppercase text-muted">Alamat Domisili</label>
                                            <textarea name="address" class="form-control bg-light border-0" rows="3">{{ old('address', $user->address) }}</textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block rounded-pill mt-4 shadow-sm font-weight-bold">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                                    @if(isset($history) && count($history) > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($history as $item)
                                            <div class="list-group-item px-0 py-3">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $item['name'] }}</h6>
                                                        <small class="text-muted"><i class="far fa-calendar-alt"></i> {{ $item['date'] }}</small>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="d-block small text-muted text-uppercase">Skor</span>
                                                        <span class="h5 font-weight-bold {{ $item['score'] >= 400 ? 'text-success' : 'text-warning' }}">
                                                            {{ $item['score'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-300"></i>
                                            <p>Belum ada riwayat tryout.</p>
                                            <a href="#" class="btn btn-sm btn-outline-primary">Mulai Tryout Sekarang</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script Preview Image User
    // Ketika user memilih file dari input hidden atau klik icon kamera
    document.getElementById('user-photo-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Ubah src gambar profil utama
                document.getElementById('user-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush