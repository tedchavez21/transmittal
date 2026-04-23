@extends('layout.layout')

@section('title', 'NL Records Monitoring')

@section('content')
    @if(session('error'))
        <div class="app-alert app-alert--error">
            {{ session('error') }}
        </div>
    @endif
    <div class="app-hero-bg">
        <div class="app-card">
            <div class="app-card-header">
                <div>
                    <h1 class="app-card-title">NL Records Monitoring</h1>
                    <p class="app-card-subtitle">PCIC Regional Officer III-A</p>
                </div>
            </div>
            <div class="app-card-body">
                <div class="app-grid-tiles">
                    <a class="app-tile od" href="{{ route('officer-of-the-day') }}">
                        <div class="icon">🧑‍💼</div>
                        <div class="label">Officer of the Day</div>
                        <p class="desc">Encode and manage records under OD workflow.</p>
                    </a>

                    <a class="app-tile email" href="{{ route('email-handler') }}">
                        <div class="icon">✉️</div>
                        <div class="label">Email</div>
                        <p class="desc">Encode from email submissions (requires approval).</p>
                    </a>

                    <a class="app-tile facebook" href="{{ route('facebook-handler') }}">
                        <div class="icon">📣</div>
                        <div class="label">Facebook</div>
                        <p class="desc">Encode from Facebook monitoring entries.</p>
                    </a>

                    <button type="button" class="app-tile admin adminLoginButton" style="text-align:left;">
                        <div class="icon">🛡️</div>
                        <div class="label">Admin</div>
                        <p class="desc">Dashboard, approvals, print preview, transmittals.</p>
                    </button>
                </div>

                <div class="app-footer">
                    <div>Use the buttons above to start encoding or to access the admin dashboard.</div>
                    <div style="font-weight: 700;">© {{ date('Y') }} PCIC</div>
                </div>
            </div>
        </div>
    </div>
    <dialog class="loginDialog">
        <h3>ADMINISTRATOR</h3>
        <form action="{{ route('admin.login') }}" method="post">
            @csrf
                <input type="text" id="username" name="username" placeholder="Username" aria-label="Username" autocomplete="username">
                <input type="password" id="password" name="password" placeholder="Password" aria-label="Password" autocomplete="current-password">
                <div class="actions">
                    <button type="button" class="btn btn-outline closeModal">Close</button>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
        </form>

    </dialog>
@endsection
