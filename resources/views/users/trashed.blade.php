@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Soft Deleted Users</h1>

        @if ($trashedUsers->isEmpty())
            <p>No soft deleted users found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trashedUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <!-- Add more columns if needed -->
                            <td>
                                <a href="{{ route('users.restore', $user->id) }}" class="btn btn-success">Restore</a>
                                <form action="{{ route('users.delete', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
