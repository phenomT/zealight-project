use Tests\TestCase;
use App\Listeners\SaveUserBackgroundInformation;
use App\Events\UserSaved;
use App\Services\UserService;
use App\Models\User;

class SaveUserBackgroundInformationTest extends TestCase
{
    public function test_user_details_are_saved_when_user_is_saved()
    {
        // Arrange
        $user = factory(User::class)->make();
        $event = new UserSaved($user);

        // Create a mock of the UserService
        $userService = $this->mock(UserService::class);

        // Create an instance of the SaveUserBackgroundInformation listener
        $listener = new SaveUserBackgroundInformation($userService);

        // Expectation
        $userService->shouldReceive('saveUserDetails')
            ->once()
            ->with($user->toArray());

        // Act
        $listener->handle($event);
    }
}
