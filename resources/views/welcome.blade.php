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

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-5 gap-5">

            {{-- Brand Panel --}}
            <div class="lg:col-span-2 bg-pcic-950 text-white rounded-2xl p-7 flex flex-col gap-5 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-pcic-800/30 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-harvest-500/20 rounded-full blur-3xl"></div>

                <div class="relative flex items-center gap-4">
                    <img src="{{ asset('images/PCIC_RO3A_LOGO.jpg') }}" alt="PCIC" width="64" height="64" class="rounded-full">
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

                    <button type="button" class="channelLogin flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5 w-full text-left cursor-pointer" data-channel="OD">
                        <div class="w-10 h-10 rounded-lg bg-pcic-100 text-pcic-700 flex items-center justify-center border border-pcic-200 shrink-0"><img src="{{ asset('images/officer-of-the-day.svg') }}" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Officer of the Day</div>
                            <div class="text-xs text-gray-500 font-medium">Encode and manage records under OD workflow.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </button>

                    <button type="button" class="channelLogin flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5 w-full text-left cursor-pointer" data-channel="Email">
                        <div class="w-10 h-10 rounded-lg bg-harvest-50 text-harvest-600 flex items-center justify-center border border-harvest-100 shrink-0"><img src="{{ asset('images/email.svg') }}" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Email</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from email submission.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </button>

                    <button type="button" class="channelLogin flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5 w-full text-left cursor-pointer" data-channel="Facebook">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-50 shrink-0"><img src="{{ asset('images/facebook.svg') }}" alt="" width="22" height="22"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">Facebook</div>
                            <div class="text-xs text-gray-500 font-medium">Encode from Facebook submissions.</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </button>

                    <a href="{{ route('all-records') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group mb-2.5 w-full">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-50 shrink-0">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 text-sm">All Records</div>
                            <div class="text-xs text-gray-500 font-medium">View all NL records with advanced filtering (public access).</div>
                        </div>
                        <div class="text-gray-300 text-lg group-hover:text-pcic-600 group-hover:translate-x-0.5 transition-all">›</div>
                    </a>

                                    </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-5">
                    <div class="text-[11px] font-black tracking-widest uppercase text-gray-400 mb-3">Administration</div>
                    <button type="button" class="adminLoginButton w-full flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white hover:border-pcic-300 hover:shadow-md transition-all duration-150 group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center border border-gray-200 shrink-0"><img src="{{ asset('images/admin.svg') }}" alt="" width="22" height="22"></div>
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
                <div class="w-9 h-9 rounded-lg bg-pcic-700 text-white flex items-center justify-center text-xs font-black" id="loginIcon">LG</div>
                <h3 class="text-lg font-black text-gray-900" id="loginTitle">Login</h3>
            </div>
            <form id="loginForm" action="{{ route('auth.login.submit') }}" method="post" class="flex flex-col gap-3">
                @csrf
                <input type="hidden" name="channel" id="loginChannel">
                <div>
                    <label for="loginUsername" class="block text-xs font-bold text-gray-700 mb-1" id="usernameLabel">Username</label>
                    <input type="text" id="loginUsername" name="username" placeholder="Enter your username" aria-label="Username" autocomplete="username"
                        class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                </div>
                <div>
                    <label for="loginPassword" class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" aria-label="Password" autocomplete="current-password"
                        class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                </div>
                <div class="flex gap-3 mt-2">
                    <button type="button" class="closeModal flex-1 h-10 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
                    <button type="submit" class="flex-1 h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Login</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog class="adminLoginDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40" style="z-index: 10001;">
        <div class="p-6 w-[min(420px,calc(100vw-2rem))]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-pcic-700 text-white flex items-center justify-center text-xs font-black">AD</div>
                <h3 class="text-lg font-black text-gray-900">Administrator Login</h3>
            </div>
            <form action="{{ route('admin.login') }}" method="post" class="flex flex-col gap-3" id="adminLoginForm">
                @csrf
                <input type="text" id="adminUsername" name="username" placeholder="Username" aria-label="Username" autocomplete="username"
                    class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full" required>
                <input type="password" id="adminPassword" name="password" placeholder="Password" aria-label="Password" autocomplete="current-password"
                    class="h-11 px-4 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full" required>
                <div class="flex gap-3 mt-2">
                    <button type="button" class="closeAdminModal flex-1 h-10 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
                    <button type="submit" class="flex-1 h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Login</button>
                </div>
            </form>
        </div>
    </dialog>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginDialog = document.querySelector('.loginDialog');
    const adminLoginDialog = document.querySelector('.adminLoginDialog');
    const channelButtons = document.querySelectorAll('.channelLogin');
    const adminButton = document.querySelector('.adminLoginButton');
    const closeButtons = document.querySelectorAll('.closeModal');
    const closeAdminButtons = document.querySelectorAll('.closeAdminModal');
    
    // Debug: Log element selection
    console.log('Elements found:', {
        loginDialog: !!loginDialog,
        adminLoginDialog: !!adminLoginDialog,
        channelButtons: channelButtons.length,
        adminButton: !!adminButton,
        closeButtons: closeButtons.length,
        closeAdminButtons: closeAdminButtons.length
    });
    
    console.log('Admin button classes:', adminButton ? adminButton.className : 'not found');
    console.log('Channel buttons found:', channelButtons.length);
    
    const loginForm = document.getElementById('loginForm');
    const loginChannel = document.getElementById('loginChannel');
    const loginIcon = document.getElementById('loginIcon');
    const loginTitle = document.getElementById('loginTitle');
    const usernameLabel = document.getElementById('usernameLabel');
    const loginUsername = document.getElementById('loginUsername');
    
    // Channel login configurations
    const channelConfig = {
        'OD': {
            icon: 'OD',
            title: 'Officer of the Day Login',
            usernameLabel: 'Username',
            usernamePlaceholder: 'Enter your username'
        },
        'Email': {
            icon: 'EM',
            title: 'Email Handler Login',
            usernameLabel: 'Name',
            usernamePlaceholder: 'Enter your username'
        },
        'Facebook': {
            icon: 'FB',
            title: 'Facebook Handler Login',
            usernameLabel: 'Username',
            usernamePlaceholder: 'Enter your username'
        }
    };
    
    // Channel button clicks - ONLY for channel buttons
    channelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const channel = this.getAttribute('data-channel');
            
            // Only handle if this is actually a channel button
            if (channel && ['OD', 'Email', 'Facebook'].includes(channel)) {
                console.log('Channel button clicked:', channel);
                
                const config = channelConfig[channel];
                
                if (config) {
                    loginChannel.value = channel;
                    loginIcon.textContent = config.icon;
                    loginTitle.textContent = config.title;
                    usernameLabel.textContent = config.usernameLabel;
                    loginUsername.placeholder = config.usernamePlaceholder;
                    
                    loginDialog.showModal();
                }
            }
        });
    });
    
    // Admin button click - COMPLETELY SEPARATE
    if (adminButton) {
        // Remove any existing event listeners first
        adminButton.replaceWith(adminButton.cloneNode(true));
        
        // Get fresh reference to the new button
        const freshAdminButton = document.querySelector('.adminLoginButton');
        
        freshAdminButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            console.log('ADMIN BUTTON CLICKED - EXCLUSIVE HANDLER');
            
            // Close ALL other dialogs first
            if (loginDialog && loginDialog.open) {
                loginDialog.close();
            }
            
            // Show ONLY admin login dialog
            adminLoginDialog.showModal();
        });
    }
    
    // Close modal buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            loginDialog.close();
        });
    });
    
    // Close modals when clicking outside
    loginDialog.addEventListener('click', function(e) {
        if (e.target === loginDialog) {
            loginDialog.close();
        }
    });
    
    // Close admin modal buttons
    closeAdminButtons.forEach(button => {
        button.addEventListener('click', function() {
            adminLoginDialog.close();
        });
    });
    
    adminLoginDialog.addEventListener('click', function(e) {
        if (e.target === adminLoginDialog) {
            adminLoginDialog.close();
        }
    });

    // Admin login form - simplified without interference
    const adminLoginForm = document.getElementById('adminLoginForm');
    if (adminLoginForm) {
        // Only handle modal close, don't interfere with form submission
        adminLoginForm.addEventListener('submit', function(e) {
            // Let the form submit normally - no JavaScript interference
        });
    }
});
</script>
@endpush
