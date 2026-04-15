@extends('layout.layout')

@section('title', 'transmittal')

@section('content')
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <div class="container">
        <a href="{{ route('officer-of-the-day') }}">
            <button class="officerOfTheDayButton">
                OFFICER OF THE DAY
            </button>
        </a>
        <button class="adminLoginButton">
            ADMIN
        </button>
    </div>
    <dialog class="loginDialog">
        <form action="{{ route('admin.login') }}" method="post">
            @csrf
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <button type="submit">Login</button>
        </form>
        <button class="closeModal">Close</button>
    </dialog>
@endsection
