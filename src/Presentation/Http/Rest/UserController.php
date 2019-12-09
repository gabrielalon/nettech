<?php

namespace App\Presentation\Http\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("user/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("status", name="api-user-status", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $data = ['success' => true, 'username' => $this->getUser()->getUsername()];
        
        return new JsonResponse($data, Response::HTTP_OK);
    }
}