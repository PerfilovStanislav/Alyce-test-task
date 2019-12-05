<?php

namespace App\Adapters;

use App\Interfaces\UserAdapterInterface;

class UserAdapter implements UserAdapterInterface
{
    /**
     * @TODO realize your own method
     * @return int
     */
    public function getUserId() : int
    {
        return 1;
    }
}
