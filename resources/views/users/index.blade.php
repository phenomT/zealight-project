<!-- resources/views/users/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>User List</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            @if ($user->photo)
                                <img src="{{ asset($user->photo) }}" alt="Avatar" class="avatar">
                            @else
                                <img src="{{ asset('images/default-avatar.jpg') }}" alt="N/A" class="avatar">
                            @endif
                        </td>
                        <td>
                            {{ $user->prefixname }} {{ $user->firstname }}
                            {{ $user->middlename ? $user->middlename : '' }}
                            {{ $user->lastname }} {{ $user->suffixname }}
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <!-- Soft Delete Button -->
                            <form action="{{ route('users.softDelete', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-trash-restore-alt"></i> Soft Delete
                                </button>
                            </form>

                            <!-- Permanent Delete Button -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
