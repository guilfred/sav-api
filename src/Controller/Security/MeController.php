<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeController extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->json($this->getUser(), Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => ['Account:Me']]);
    }
}
