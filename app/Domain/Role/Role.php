<?php

namespace App\Domain\Role;


class Role extends \Spatie\Permission\Models\Role
{
    const ROLE_TEAM_OWNER = 'Owner';
    const ROLE_DESIGNER = 'Designer';
    const ROLE_DEVELOPER = 'Developer';
    const ROLE_TESTER = 'Tester';
}