<?php

namespace Canopy\ACL\Events;

use Canopy\ACL\Models\Role;
use Canopy\ACL\Models\User;
use Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    /**
     * @var Role
     */
    public $role;

    /**
     * @var User
     */
    public $user;

    /**
     * RoleAssignmentEvent constructor.
     *
     * @param Role $role
     * @param User $user
     */
    public function __construct(Role $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }
}
