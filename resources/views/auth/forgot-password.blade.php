@extends('layouts.master')
@section('hideNavbar')
@endsection
@section('hideFooter')
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900">{{ __('Reset Password') }}</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if (session('dev_reset_url'))
            <div class="p-4 bg-blue-50 border border-blue-300 text-blue-900 rounded space-y-2">
                <p class="text-sm font-medium">Link reset password (mode lokal):</p>
                <a href="{{ session('dev_reset_url') }}" class="text-sm break-all underline hover:no-underline">
                    {{ session('dev_reset_url') }}
                </a>
                <p class="text-xs text-blue-700">Atur `MAIL_MAILER=smtp` di `.env` untuk pengiriman email ke inbox asli.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-900 mb-2">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB] focus:border-transparent @error('email') border-red-500 @enderror" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full py-3 bg-[#2E86AB] text-white rounded-lg font-medium hover:bg-[#1f5a7a] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E86AB]">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-[#2E86AB] hover:text-[#1f5a7a]">
                    {{ __('Back to login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
