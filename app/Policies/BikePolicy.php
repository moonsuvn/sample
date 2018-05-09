<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bike;

class BikePolicy extends Policy
{
    public function update(User $user, Bike $bike)
    {
        // return $bike->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Bike $bike)
    {
        return true;
    }
}
