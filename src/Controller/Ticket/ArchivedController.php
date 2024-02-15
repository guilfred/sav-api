<?php

namespace App\Controller\Ticket;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivedController extends AbstractController
{
    /**
     * Invokes the function with the given Ticket, Request, and TicketRepository,
     * applies the archive to the ticket, and returns the JSON response.
     *
     * @param Ticket           $ticket
     * @param Request          $request
     * @param TicketRepository $ticketRepository
     *
     * @return JsonResponse
     */
    public function __invoke(Ticket $ticket, Request $request, TicketRepository $ticketRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $result = $ticketRepository->applyArchive($ticket, (bool) $data['archived']);

        return $this->json($result, Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => ['Ticket:Read']]);
    }
}
