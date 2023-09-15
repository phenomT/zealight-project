@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>User Details</h1>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Prefix</th>
                    <td>{{ $user->prefixname }}</td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td>{{ $user->firstname }}</td>
                </tr>
                <tr>
                    <th>Middle Name</th>
                    <td>{{ $user->middlename }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ $user->lastname }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
