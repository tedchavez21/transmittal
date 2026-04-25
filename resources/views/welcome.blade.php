@extends('layout.layout')

@section('title', 'NL Records Monitoring')

@section('content')

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-pcic-50 via-white to-pcic-50 relative overflow-hidden">
        {{-- Rice field silhouette background --}}
        <div class="absolute inset-0 opacity-[0.07] pointer-events-none"
             style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 200%22 preserveAspectRatio=%22none%22%3E%3Cpath fill=%22%23006c35%22 d=%22M0 200V120c60-10 120 20 180 5s120-35 180-20 120 25 180 10 120-30 180-15 120 20 180 5 120-25 180-10v110z%22/%3E%3Cpath fill=%22%23005428%22 d=%22M0 200v-40c40-8 80 15 120 5s80-25 120-12 80 18 120 8 80-22 120-10 80 16 120 6 80-20 120-8 80 14 120 4v67z%22/%3E%3Cg fill=%22%23006c35%22 opacity=%220.6%22%3E%3Cpath d=%22M60 120c0-30 3-50 3-70s-3-20-3-30%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M180 105c0-25 3-45 3-65s-3-18-3-28%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M300 110c0-28 3-48 3-68s-3-19-3-29%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M420 100c0-24 3-44 3-64s-3-17-3-27%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M540 110c0-28 3-48 3-68s-3-19-3-29%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M660 105c0-25 3-45 3-65s-3-18-3-28%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M780 115c0-30 3-50 3-70s-3-20-3-30%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M900 100c0-24 3-44 3-64s-3-17-3-27%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M1020 110c0-28 3-48 3-68s-3-19-3-29%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3Cpath d=%22M1140 105c0-25 3-45 3-65s-3-18-3-28%22 stroke=%22%23006c35%22 stroke-width=%221.5%22 fill=%22none%22/%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat-x; background-position: center bottom; background-size: 1200px 200px;">
        </div>
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-5 gap-5">

            {{-- Brand Panel --}}
            <div class="lg:col-span-2 bg-pcic-950 text-white rounded-2xl p-7 flex flex-col gap-5 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-pcic-800/30 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-harvest-500/20 rounded-full blur-3xl"></div>

                <div class="relative flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pcic-400 to-harvest-400 flex items-center justify-center text-white font-black text-lg tracking-wider shadow-lg shrink-0">PCIC</div>
                    <div>
                        <div class="text-[11px] font-bold tracking-widest uppercase text-pcic-300">Philippine Crop Insurance Corporation</div>
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
                        Secure internal portal
                    </span>
                    <span class="font-bold">© {{ date('Y') }} PCIC</span>
                </div>
            </div>

            {{-- Action Cards --}}
            <div class="lg:col-span-3 flex flex-col gap-4">

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-5">
                    <div class="text-[11px] font-black tracking-widest uppercase text-gray-400 mb-3">Modules</div>

                    <a href="{{ route('officer-of-the-day') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5">
                        <div class="w-10 h-10 rounded-lg bg-pcic-100 text-pcic-700 flex items-center justify-center text-xs font-black border border-pcic-200 shrink-0">OD</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Officer of the Day</div>
                            <div class="text-xs text-gray-500 font-medium">Encode and manage records under OD workflow.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                    <a href="{{ route('email-handler') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5">
                        <div class="w-10 h-10 rounded-lg bg-harvest-50 text-harvest-600 flex items-center justify-center text-xs font-black border border-harvest-100 shrink-0">EM</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Email</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from email submissions (approval required).</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                    <a href="{{ route('facebook-handler') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group">
                        <div class="w-10 h-10 rounded-lg bg-pcic-50 text-pcic-600 flex items-center justify-center text-xs font-black border border-pcic-100 shrink-0">FB</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Facebook</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from Facebook monitoring entries.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-5">
                    <div class="text-[11px] font-black tracking-widest uppercase text-gray-400 mb-3">Administration</div>
                    <button type="button" class="adminLoginButton w-full flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-black border border-gray-200 shrink-0">AD</div>
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
