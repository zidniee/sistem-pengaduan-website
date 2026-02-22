@extends('layouts.master')
@section('hideNavbar')
@endsection
@section('hideFooter')
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900">{{ __('Confirm Password') }}</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB] focus:border-transparent @error('password') border-red-500 @enderror" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full py-3 bg-[#2E86AB] text-white rounded-lg font-medium hover:bg-[#1f5a7a] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E86AB]">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
