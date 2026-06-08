@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="content-wrapper">
    <div class="content-body">
        <div class="row mb-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm profile-hero">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img
                                src="{{ $admin->profile_image ? asset('upload/' . $admin->profile_image) : asset('assets/admin/design/pro.png') }}"
                                alt="Profile"
                                class="hero-avatar"
                            >
                            <div class="ml-1">
                                <h3 class="mb-25 text-white">{{ $admin->name }}</h3>
                                <p class="mb-0 text-white-75">{{ $admin->email }}</p>
                                @if($admin->mobile)
                                    <p class="mb-0 text-white-75">{{ $admin->mobile }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-1 mt-md-0">
                            <span class="badge badge-light-primary px-1 py-50">Account Settings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="card border-0 shadow-sm profile-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h4 class="card-title mb-0">Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                <label class="d-block text-muted small text-uppercase font-weight-bold mb-50">Profile Picture</label>
                                <div class="d-flex align-items-center profile-upload-wrap">
                                    <img
                                        id="profilePreview"
                                        src="{{ $admin->profile_image ? asset('upload/' . $admin->profile_image) : asset('assets/admin/design/pro.png') }}"
                                        alt="Profile"
                                        class="profile-preview"
                                    >
                                    <input type="file" name="profile_image" id="profileImageInput" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">Mobile</label>
                                <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $admin->mobile) }}">
                            </div>

                            <button type="submit" class="btn btn-primary px-2">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-12">
                <div class="card border-0 shadow-sm profile-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h4 class="card-title mb-0">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">Current Password</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">New Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-50">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-warning px-2">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .profile-hero {
        background: linear-gradient(135deg, #0f2f45 0%, #1d5b83 55%, #2c7fb0 100%);
    }
    .hero-avatar {
        width: 72px;
        height: 72px;
        border-radius: 999px;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.45);
    }
    .text-white-75 {
        color: rgba(255,255,255,.78);
    }
    .profile-card {
        border-radius: 14px;
    }
    .profile-upload-wrap {
        border: 1px dashed #d4dbe5;
        border-radius: 12px;
        padding: 10px;
        background: #fafcff;
    }
    .profile-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5ecf5;
        margin-right: 12px;
    }
    .profile-card .form-control {
        border-radius: 10px;
        border-color: #dbe4ef;
    }
    .profile-card .form-control:focus {
        border-color: #6aa4cf;
        box-shadow: 0 0 0 .2rem rgba(44,127,176,.14);
    }
</style>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('profileImageInput');
        const preview = document.getElementById('profilePreview');

        if (!imageInput || !preview) return;

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
        });
    });
</script>
@endpush
