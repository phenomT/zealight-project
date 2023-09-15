<?php

namespace App\Listeners;
use App\Events\UserSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\UserService;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserBackgroundInformation
{
    /**
     * Create the event listener.
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(UserSaved $event)
    {

        $user = $event->user;

        $this->userService->saveUserDetails([
            'firstname' => $user->firstname,
            'middlename' => $user->middlename,
            'lastname' => $user->lastname,
            'photo' => $user->photo,
            'prefixname' => $user->prefixname,
        ]);
    }



}
