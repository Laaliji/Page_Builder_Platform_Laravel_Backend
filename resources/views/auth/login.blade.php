@extends('layouts.app')

@section('content')
<div class="text-center">
    <h2 class="mb-4">Login to Your Account</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-2">
        <a href="{{ url('auth/github') }}" class="btn btn-dark btn-lg">
            <i class="bi bi-github me-2"></i>Login with GitHub
        </a>
    </div>

    <div class="mt-3 text-muted">
        <small>Connect with your GitHub account</small>
    </div>
</div>
@endsection