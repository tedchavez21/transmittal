@extends('layout.layout')

@section('title', 'NL Records Monitoring')

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
        background: url('/images/background.png') center/cover no-repeat;
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

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-5 gap-5">

            {{-- Brand Panel --}}
            <div class="lg:col-span-2 bg-pcic-950 text-white rounded-2xl p-7 flex flex-col gap-5 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-pcic-800/30 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-harvest-500/20 rounded-full blur-3xl"></div>

                <div class="relative flex items-center gap-4">
                    <img src="/images/PCIC_RO3A_LOGO.jpg" alt="PCIC" width="64" height="64" class="rounded-full">
                    <div>
                        <div class="text-[11px] font-bold tracking-widest uppercase text-pcic-300">PCIC</div>
                        <div class="text-2xl font-black text-white mt-1 leading-tight">NL Records Monitoring</div>
                        <div class="text-sm text-pcic-300 font-semibold mt-0.5">Regional Office III-A</div>
                    </div>
                </div>

                <div class="relative bg-white/10 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-sm font-medium text-pcic-100 leading-relaxed">Select a module to start encoding, reviewing, and managing Notice of Loss (NL) records.</p>
                </div>

                <div class="relative mt-auto flex items-center justify-between text-xs text-pcic-400/80">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-harvest-400 inline-block"></span>
                        Records Management System
                    </span>
                    <span class="font-bold">© {{ date('Y') }} Chvz_Td</span>
                </div>
            </div>

            {{-- Action Cards --}}
            <div class="lg:col-span-3 flex flex-col gap-4">

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-5">
                    <div class="text-[11px] font-black tracking-widest uppercase text-gray-400 mb-3">Modules</div>

                    <a href="{{ route('officer-of-the-day') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5">
                        <div class="w-10 h-10 rounded-lg bg-pcic-100 text-pcic-700 flex items-center justify-center border border-pcic-200 shrink-0"><img src="/images/officer-of-the-day.svg" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Officer of the Day</div>
                            <div class="text-xs text-gray-500 font-medium">Encode and manage records under OD workflow.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                    <a href="{{ route('email-handler') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5">
                        <div class="w-10 h-10 rounded-lg bg-harvest-50 text-harvest-600 flex items-center justify-center border border-harvest-100 shrink-0"><img src="/images/email.svg" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Email</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from email submissions (approval required).</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                    <a href="{{ route('facebook-handler') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-50 shrink-0"><img src="/images/facebook.svg" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Facebook</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from Facebook submissions (approval required).</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                                    </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-5">
                    <div class="text-[11px] font-black tracking-widest uppercase text-gray-400 mb-3">Administration</div>
                    <button type="button" class="adminLoginButton w-full flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center border border-gray-200 shrink-0"><img src="/images/admin.svg" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Admin Dashboard</div>
                            <div class="text-xs text-gray-500 font-medium">Approvals, monitoring, transmittals, print preview.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <dialog class="loginDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40">
        <div class="p-6 w-[min(420px,calc(100vw-2rem))]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-pcic-700 text-white flex items-center justify-center text-xs font-black">AD</div>
                <h3 class="text-lg font-black text-gray-900">Administrator Login</h3>
            </div>
            <form action="{{ route('admin.login') }}" method="post" class="flex flex-col gap-3">
                @csrf
                <input type="text" id="username" name="username" placeholder="Username" aria-label="Username" autocomplete="username"
                    class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <input type="password" id="password" name="password" placeholder="Password" aria-label="Password" autocomplete="current-password"
                    class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <div class="flex gap-3 mt-2">
                    <button type="button" class="closeModal flex-1 h-10 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
                    <button type="submit" class="flex-1 h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Login</button>
                </div>
            </form>
        </div>
    </dialog>
@endsection
