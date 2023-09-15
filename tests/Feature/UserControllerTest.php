use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'prefixname' => 'Mr.',
            'firstname' => 'John',
            'middlename' => 'M.',
            'lastname' => 'Doe',
            'suffixname' => 'Jr.',
            'username' => 'johndoe123',
            'email' => 'john@example.com',
            'password' => 'password123',

        ];

        $response = $this->post(route('users.store'), $userData);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', $userData);
    }

        /** @test */
    public function it_can_update_a_user()
    {

        $user = factory(User::class)->create();


        $updatedData = [
            'prefixname' => 'Updated Prefix',
            'firstname' => 'Updated Firstname',
            'middlename' => 'Updated Middle',
            'lastname' => 'Updated Lastname',
            'suffixname' => 'Updated Suffix',
            'username' => 'updated_username',
            'email' => 'updated@example.com',
            'password' => 'updated_password',

        ];


        $response = $this->put(route('users.update', $user->id), $updatedData);


        $response->assertStatus(302);



        foreach ($updatedData as $field => $value) {
            $this->assertDatabaseHas('users', [$field => $value]);
        }
    }

    /** @test */
    public function it_can_delete_a_user()
    {

        $user = factory(User::class)->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

        /** @test */
    public function it_can_list_all_users()
    {

        factory(User::class, 5)->create();

        $response = $this->get(route('users.index'));


        $response->assertStatus(200);
        $response->assertViewIs('users.index');
        $response->assertViewHas('users', User::all());
    }

    /** @test */
    public function it_can_list_all_soft_deleted_users()
    {

        factory(User::class, 5)->create(['deleted_at' => now()]);


        $response = $this->get(route('users.trashed'));


        $response->assertStatus(200);
        $response->assertViewIs('users.trashed');
        $response->assertViewHas('users', User::onlyTrashed()->get());
    }

    /** @test */
    public function it_can_restore_a_soft_deleted_user()
    {

        $user = factory(User::class)->create(['deleted_at' => now()]);


        $response = $this->patch(route('users.restore', $user->id));


        $response->assertStatus(302); /
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    /** @test */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {

        $user = factory(User::class)->create(['deleted_at' => now()]);


        $response = $this->delete(route('users.delete', $user->id));


        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDeleted('users', ['id' => $user->id]);
    }
}
