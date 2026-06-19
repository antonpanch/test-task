<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Role;

enum Permission
{
    case CREATE_USER;
    case GET_ANY_USER;
    case GET_SELF_USER;
    case UPDATE_ANY_USER;
    case UPDATE_SELF_USER;
    case DELETE_ANY_USER;
}
