@extends('layouts.app')

@section('title', 'Profil & Ubah Password')
@section('page-title', 'Profil Saya')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    
    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Overview Card -->
    <div class="card" style="margin-bottom: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 2rem; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 5px 0; color: #1e293b; font-size: 1.3rem;">{{ $user->name }}</h3>
                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <span class="badge" style="background: #e0e7ff; color: #3730a3; padding: 4px 12px; font-weight: 600;">
                        <i class="fas fa-user-tag"></i> {{ $user->role == 'admin' ? 'Administrator' : 'Guru Pembina' }}
                    </span>
                    @if($user->eskul)
                        <span class="badge" style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; font-weight: 600;">
                            <i class="fas fa-basketball-ball"></i> {{ $user->eskul->name }}
                        </span>
                    @endif
                    @if($user->homeroom_class)
                        <span class="badge" style="background: #e8f5e9; color: #2e7d32; padding: 4px 12px; font-weight: 600;">
                            <i class="fas fa-chalkboard-teacher"></i> Wali Kelas {{ $user->homeroom_class }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 0.9rem;">
            <div>
                <span style="color: #64748b; display: block; font-size: 0.8rem; font-weight: 500;">Username Login</span>
                <strong style="color: #334155; font-family: monospace; font-size: 1.05rem;">{{ $user->username }}</strong>
            </div>
            <div>
                <span style="color: #64748b; display: block; font-size: 0.8rem; font-weight: 500;">Nomor WhatsApp</span>
                <strong style="color: #334155;">{{ $user->phone ?? '-' }}</strong>
            </div>
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: #fff7ed; color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h4 style="margin: 0; color: #1e293b; font-size: 1.1rem;">Ubah Password Akun</h4>
                <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.85rem;">Amankan akun Anda dengan mengganti password default dari Admin.</p>
            </div>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; color: #334155;">Password Saat Ini</label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                    <i class="fas fa-eye toggle-pwd" data-target="current_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8;"></i>
                </div>
                @error('current_password')
                    <div style="color: #ef4444; font-size: 0.825rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; color: #334155;">Password Baru</label>
                <div style="position: relative;">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    <i class="fas fa-eye toggle-pwd" data-target="new_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8;"></i>
                </div>
                @error('new_password')
                    <div style="color: #ef4444; font-size: 0.825rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="font-weight: 600; color: #334155;">Konfirmasi Password Baru</label>
                <div style="position: relative;">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required minlength="6">
                    <i class="fas fa-eye toggle-pwd" data-target="new_password_confirmation" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8;"></i>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" class="btn-action-header btn-blue" style="padding: 10px 24px; font-size: 0.95rem;">
                    <i class="fas fa-save"></i> Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.toggle-pwd').forEach(icon => {
        icon.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush
@endsection
