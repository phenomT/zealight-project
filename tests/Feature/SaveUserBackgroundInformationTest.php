use App\Events\UserSavedEvent;
use App\Listeners\SaveUserBackgroundInformation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveUserBackgroundInformationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_user_details_to_details_table_when_user_is_saved()
    {
        // Arrange
        $user = factory(User::class)->create([
            'firstname' => 'John',
            'middlename' => 'Doe',
            'lastname' => 'Smith',
            'photo' => 'path_to_avatar',
            'prefixname' => 'Mr.',
        ]);

        $event = new UserSavedEvent($user);
        $listener = new SaveUserBackgroundInformation();

        // Act
        $listener->handle($event);

        // Assert
        $this->assertDatabaseHas('details', [
            'key' => 'Full name',
            'value' => 'John Doe Smith',
            'type' => 'bio',
            'user_id' => $user->id,
        ]);

        // Add more assertions for other details you save
    }
}
