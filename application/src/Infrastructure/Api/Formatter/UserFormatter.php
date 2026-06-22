<?php

namespace App\Infrastructure\Api\Formatter;

use App\Domain\Entity\User;

class UserFormatter
{
    public function formatUser(User $user): array
    {
        return [
            'data' => $this->userToArray($user)
        ];
    }

    public function formatUsers(array $users)
    {
        $data = [];
        foreach ($users as $user) {
            $data[] = $this->userToArray($user);
        }
        return [
            'data' => $data
        ];
    }

    private function userToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'pass' => $user->getPassword(),
            'phone' => $user->getPhoneNumber()
        ];
    }
}
