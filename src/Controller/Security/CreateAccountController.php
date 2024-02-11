<?php

namespace App\Controller\Security;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CreateAccountController extends AbstractController
{
    public function __invoke(Request $request, Account $account, AccountRepository $accountRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $result = $accountRepository->createAccount($data);

        return $this->json($result, Response::HTTP_CREATED, ['Content-Type' => 'application/json'], ['groups' => ['Account:Post:Read']]);
    }
}
