@extends('layout.layout')

@section('title', 'Admin Login')

@section('page-styles')
<style>
    body {
        position: relative;
    }
    body::before {
        content: '';
        position: fixed;
        top: -10px;
        left: -10px;
        width: calc(100% + 20px);
        height: calc(100% + 20px);
        background: url('{{ asset('images/background.png') }}') center/cover no-repeat;
        filter: blur(3px) brightness(0.55);
        z-index: -2;
    }
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.25);
        z-index: -1;
    }
</style>
@endsection

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100/80 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-lg bg-pcic-700 text-white flex items-center justify-center text-sm font-black">AD</div>
                    <div>
                        <h1 class="text-xl font-black text-gray-900">Administrator Login</h1>
                        <p class="text-sm text-gray-500 font-medium">Login to access the admin dashboard</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="post" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-700 mb-1.5">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" aria-label="Username" autocomplete="username"
                            class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full" required>
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" aria-label="Password" autocomplete="current-password"
                            class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full" required>
                    </div>
                    <button type="submit" class="w-full h-11 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer mt-2">
                        Login
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('welcome') }}" class="text-sm text-gray-500 hover:text-pcic-700 font-medium transition-colors">
                        ← Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
