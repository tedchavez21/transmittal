@extends('layout.layout')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pcic-100 via-white to-pcic-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
        <div class="px-6 pt-6 pb-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white text-center">
            <div class="w-12 h-12 rounded-xl bg-pcic-100 text-pcic-700 flex items-center justify-center text-sm font-black border border-pcic-200 mx-auto mb-3">
                @switch($channel)
                    @case('OD')
                        OD
                        @break
                    @case('Email')
                        EM
                        @break
                    @case('Facebook')
                        FB
                        @break
                    @default
                        LOGIN
                @endswitch
            </div>
            <h1 class="text-xl font-black text-gray-900">
                @switch($channel)
                    @case('OD')
                        Officer of the Day Login
                        @break
                    @case('Email')
                        Email Handler Login
                        @break
                    @case('Facebook')
                        Facebook Handler Login
                        @break
                    @default
                        Login
                @endswitch
            </h1>
            <p class="text-sm text-gray-500 font-semibold mt-1">Enter your credentials to continue.</p>
        </div>
        
        <div class="px-6 py-5">
            @if(session('error'))
                <div class="mb-4 px-3 py-2.5 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="mb-4 px-3 py-2.5 rounded-lg bg-green-50 border border-green-200 text-green-800 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('auth.login') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <input type="hidden" name="channel" value="{{ $channel }}">
                
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-700 mb-1">
                        @switch($channel)
                            @case('OD')
                                Username
                                @break
                            @case('Email')
                                Name
                                @break
                            @case('Facebook')
                                Username
                                @break
                            @default
                                Username
                        @endswitch
                    </label>
                    <input type="text" id="username" name="username" required
                           placeholder="@switch($channel)
                                @case('OD')
                                    Enter your username
                                    @break
                                @case('Email')
                                    Enter your full name
                                    @break
                                @case('Facebook')
                                    Enter your username
                                    @break
                                @default
                                    Enter your username
                            @endswitch"
                           class="h-11 px-3 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                </div>
                
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Enter your password"
                           class="h-11 px-3 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                </div>
                
                <button type="submit" class="h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">
                    Login
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="{{ route('welcome') }}" class="text-xs text-gray-500 hover:text-gray-700 font-semibold">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
