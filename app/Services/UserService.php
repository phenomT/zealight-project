<?php

namespace App\Services;

use App\Models\User;
use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    protected $model;
    protected $request;

    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    public function rules($id = null)
    {
        return [
            'prefixname' => 'nullable|string|in:Mr,Mrs,Ms',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffixname' => 'nullable|string',
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }



    public function list()
    {
        // Retrieve and paginate the list of users
        return $this->model->paginate(10);
    }

    public function store(array $attributes)
    {
        // Handle file upload for photo
        if ($this->request->hasFile('photo')) {
            $attributes['photo'] = $this->upload($this->request->file('photo'));
        }

        // Create and return the user
        return $this->model->create($attributes);
    }

    public function saveUserDetails(array $attributes)
    {
        // Extract user attributes
        $firstname = $attributes['firstname'];
        $middlename = $attributes['middlename'] ?? '';
        $lastname = $attributes['lastname'];
        $photo = $attributes['photo'] ?? null;
        $prefixname = $attributes['prefixname'] ?? '';

        // Create or update user details in the `details` table
        $userDetails = [
            [
                'key' => 'Full name',
                'value' => "{$firstname} {$middlename} {$lastname}",
                'type' => 'bio',
            ],
            [
                'key' => 'Middle Initial',
                'value' => $middlename ? "{$middlename[0]}." : '',
                'type' => 'bio',
            ],
            [
                'key' => 'Avatar',
                'value' => $photo ? url($photo) : null,
                'type' => 'bio',
            ],
            [
                'key' => 'Gender',
                'value' => ($prefixname === 'Mr.' || $prefixname === 'Sir') ? 'Male' : 'Female',
                'type' => 'bio',
            ],
        ];


        $userId = $attributes['id'];
        $existingDetails = Detail::where('user_id', $userId)->get();

        if ($existingDetails->isEmpty()) {

            Detail::insert(array_map(function ($detail) use ($userId) {
                $detail['user_id'] = $userId;
                return $detail;
            }, $userDetails));
        } else {

            foreach ($existingDetails as $existingDetail) {
                foreach ($userDetails as $newDetail) {
                    if ($existingDetail->key === $newDetail['key']) {
                        $existingDetail->update(['value' => $newDetail['value']]);
                    }
                }
            }
        }
    }

    public function find(int $id): ?User
    {

        return $this->model->find($id);
    }

    public function update(int $id, array $attributes): bool
    {
        // Find the user by ID
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        // Handle file upload for photo if provided
        if ($this->request->hasFile('photo')) {
            $attributes['photo'] = $this->upload($this->request->file('photo'));
        }

        // Update the user's attributes
        return $user->update($attributes);
    }

    public function destroy($id)
    {
        // Soft delete the user
        $user = $this->model->find($id);

        if ($user) {
            $user->delete();
        }
    }

    public function listTrashed()
    {
        // Retrieve and paginate soft deleted users
        return $this->model->onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        // Restore a soft deleted user by ID
        $user = $this->model->withTrashed()->find($id);

        if ($user) {
            $user->restore();
        }
    }

    public function delete($id)
    {
        // Permanently delete a soft deleted user by ID
        $user = $this->model->withTrashed()->find($id);

        if ($user) {
            // Delete the photo file if it exists
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $user->forceDelete();
        }
    }

    public function hash(string $key): string
    {
        // Generate and return a random hash key
        return Hash::make($key);
    }

    public function upload(UploadedFile $file): ?string
    {
        // Upload the given file and return the file path
        $path = $file->store('user_photos', 'public');
        return $path ? Storage::url($path) : null;
    }
}
