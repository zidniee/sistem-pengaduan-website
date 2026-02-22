
 @extends('layouts.user-dashboard')
@section('user-dashboard-content')

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Profil</h1>
    <p class="text-gray-600 mt-1">Kelola informasi profil Anda</p>
</div>

<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
