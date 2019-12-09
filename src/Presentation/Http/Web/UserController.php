<?php

namespace App\Presentation\Http\Web;

use App\Application\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("user/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("list", name="user-list")
     *
     * @param UserService $userService
     * @return Response
     */
    public function index(UserService $userService): Response
    {
        return $this->render('user/list.html.twig', [
            'userCollection' => $userService->findAllUsers()
        ]);
    }

    /**
     * @Route("remove/{uuid}", name="user-remove", requirements={"uuid"=".+"})
     * @param string $uuid
     * @param UserService $userService
     * @return Response
     */
    public function remove(string $uuid, UserService $userService): Response
    {
        $userService->removeUser($uuid);

        return $this->redirectToRoute('user-list');
    }
}