<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Version2;


use App\Application\Authorization\AuthService;
use App\Application\Permission\CreateUserIfAllowedUseCase;
use App\Application\Permission\DeleteUserIfAllowedUseCase;
use App\Application\Permission\GetUserIfAllowedUseCase;
use App\Application\Permission\GetUsersIfAllowedUseCase;
use App\Application\Permission\UpdateUserIfAllowedUseCase;
use App\Domain\ValueObject\Pagination\AfterId;
use App\Domain\ValueObject\Pagination\PerPage;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Api\Controller\Common\AbstractUserController;
use App\Infrastructure\Api\Formatter\UserFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractUserController
{
    private UserFormatter $userFormatter;

    public function __construct(AuthService $authService, RequestStack $requestStack)
    {
        parent::__construct($authService, $requestStack);
        $this->userFormatter = new UserFormatter();
    }

    public function createUser(CreateUserIfAllowedUseCase $createUserIfAllowedUseCase): Response
    {
        $fields = $this->getFieldsFromJsonRequestContent();
        $user = $createUserIfAllowedUseCase->handle(
            $this->loggedInUser,
            new Login($fields['login'] ?? ''),
            new Password($fields['pass'] ?? ''),
            new PhoneNumber($fields['phone'] ?? '')
        );
        return new JsonResponse($this->userFormatter->formatUser($user), Response::HTTP_CREATED);
    }

    public function deleteUser(DeleteUserIfAllowedUseCase $deleteUserIfAllowedUseCase, $id): Response
    {
        $deleteUserIfAllowedUseCase->handle($this->loggedInUser, new UserId($id));
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function getUser(GetUserIfAllowedUseCase $getUserIfAllowedUseCase, $id): Response
    {
        $user = $getUserIfAllowedUseCase->handle($this->loggedInUser, new UserId($id));
        return new JsonResponse($this->userFormatter->formatUser($user), Response::HTTP_OK);
    }

    public function updateUser(UpdateUserIfAllowedUseCase $updateUserIfAllowedUseCase, $id): Response
    {
        $fields = $this->getFieldsFromJsonRequestContent();

        $user = $updateUserIfAllowedUseCase->handle(
            $this->loggedInUser,
            new UserId($id),
            new Login($fields['login'] ?? ''),
            new Password($fields['pass'] ?? ''),
            new PhoneNumber($fields['phone'] ?? '')
        );
        return new JsonResponse($this->userFormatter->formatUser($user), Response::HTTP_OK);
    }

    public function getUsers(GetUsersIfAllowedUseCase $getUsersIfAllowedUseCase)
    {
        $afterId = $this->request->query->get('afterId', 0);
        $perPage = $this->request->query->get('perPage', PerPage::DEFAULT_PER_PAGE);
        $users = $getUsersIfAllowedUseCase->handle($this->loggedInUser, new AfterId($afterId), new PerPage($perPage));
        return new JsonResponse($this->userFormatter->formatUsers($users), Response::HTTP_OK);
    }
}
