<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }


    public function store(UserRequest $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads'), $photoName);
        } else {
            $photoName = null;
        }

        // Create a new user
        User::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'photo' => $photoName, // Save the file name in the database
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return abort(404);
        }

        return view('users.show', compact('user'));
    }


    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the request data, adjust validation rules as needed
        $validatedData = $request->validate([
            'prefixname' => 'in:Mr,Mrs,Ms', // You can add more validation rules here
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'suffixname' => 'nullable',
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required|min:8',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust image validation rules as needed
        ]);

        // Handle the uploaded photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        // Update user details
        $user->prefixname = $request->input('prefixname');
        $user->firstname = $request->input('firstname');
        $user->middlename = $request->input('middlename');
        $user->lastname = $request->input('lastname');
        $user->suffixname = $request->input('suffixname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password')); // You may adjust password hashing as needed

        $user->save();

        // Redirect with a success toast message
        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function softDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        // Soft delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User soft deleted successfully');
    }


    public function delete($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);

            $user->forceDelete();

            return redirect()->route('users.trashed')->with('success', 'User permanently deleted.');
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return redirect()->route('users.trashed')->with('error', 'Error occurred while deleting user.');
        }
    }
}
