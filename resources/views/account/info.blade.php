@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Account Information</h2>

    <!-- Success Message -->
    @if(session('success'))
    @endif

    <!-- Update Name & Email -->
    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Info</button>
    </form>

    <hr>

    <!-- Change Password -->
    <h4>Change Password</h4>
    <form method="POST" action="{{ route('update.password') }}">

        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password:</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password:</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">Change Password</button>
    </form>
</div>
@endsection
