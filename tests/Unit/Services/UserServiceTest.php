<?php

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Factory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService(new User, new Request());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        // Arrangements
        $userService = new UserService(new User, new Request());

        // Actions
        $result = $userService->list();

        // Assertions
        $result->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $userData = [
            'prefixname' => 'Mr.',
            'firstname' => 'John',
            'middlename' => 'Doe',
            'lastname' => 'Smith',
            'username' => 'johnsmith',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'type' => 'user',
        ];

        // Actions
        $user = $this->userService->store($userData);

        // Assertions
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertDatabaseHas('users', ['username' => 'johnsmith']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Arrangements
        $user = factory(User::class)->create();

        // Actions
        $foundUser = $this->userService->find($user->id);

        // Assertions
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        // Create a user record in the database.
        $user = factory(User::class)->create();

        // Actions
        // Define the updated data for the user.
        $updatedData = [
            'firstname' => 'Sodiq',
            'email' => 'temidoswag@gmail.com',
        ];

        // Call the method in UserService to update the user's information.
        $updatedUser = $this->userService->update($user->id, $updatedData);

        // Assertions
        // Assert that the user's information has been updated in the database.
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Sodiq',
            'email' => 'temidoswag@gmail.com',

        ]);
        $this->assertInstanceOf(User::class, $updatedUser);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrangements
        $user = factory(User::class)->create();
        $userService = new UserService(new User, new Request());

        // Actions
        $userService->destroy($user->id);

        // Assertions
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {

        // Actions
        $result = $this->userService->listTrashed();

        // Assertions
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $user = factory(User::class)->create();

        $this->userService->destroy($user->id);

        // Actions
        $this->userService->restore($user->id);

        // Assertions
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrangements
        factory(User::class, 5)->create();
        factory(User::class, 3)->create(['deleted_at' => now()]);

        // Actions
        $result = $this->userService->listTrashed();

        // Assertions
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result->items());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // Arrangements
        $userService = new UserService(new User, new Request());
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Actions
        $result = $userService->upload($file);

        // Assertions
        $this->assertNotNull($result);
    }
}
