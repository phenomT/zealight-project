@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit User</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use the PUT method for updates -->

            <!-- Display success or error toast messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Input fields for user details -->
            <div class="form-group">
                <label for="prefixname">Prefix</label>
                <select class="form-control" id="prefixname" name="prefixname">
                    <option value="Mr" {{ $user->prefixname === 'Mr' ? 'selected' : '' }}>Mr</option>
                    <option value="Mrs" {{ $user->prefixname === 'Mrs' ? 'selected' : '' }}>Mrs</option>
                    <option value="Ms" {{ $user->prefixname === 'Ms' ? 'selected' : '' }}>Ms</option>
                </select>
            </div>

            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $user->firstname }}" required>
            </div>

            <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename" value="{{ $user->middlename }}">
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $user->lastname }}" required>
            </div>

            <div class="form-group">
                <label for="suffixname">Suffix</label>
                <input type="text" class="form-control" id="suffixname" name="suffixname" value="{{ $user->suffixname }}">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="photo">Profile Photo</label>
                <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
@endsection
