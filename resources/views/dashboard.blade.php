@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1 class="mb-4">Welcome, {{ Auth::user()->name }}</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Your Profile</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ Auth::user()->email }}<br>
                <strong>Logged in via:</strong> {{ Auth::user()->auth_provider }}
            </p>
        </div>
    </div>

    <div class="mt-4 d-grid">
        <a href="{{ route('logout') }}" 
           class="btn btn-danger"
           onclick="event.preventDefault(); 
                    document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@endsection