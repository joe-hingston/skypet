<?php

namespace App\Http\View\Composers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Repositories\UserRepository;

class ProfileComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;
    protected $notifications;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...

if(Auth::check()) {

    if ($user = Auth::user()) {
        $array = $user->notifications->toArray();
        $this->notifications = array_column($array, 'data');
    }
}
else {
    $this->notifications = null;
}
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('notifications', $this->notifications);
    }
}
