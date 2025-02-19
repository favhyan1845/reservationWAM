<?php

namespace App\Controller;

use App\Service\CsvReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private CsvReader $csvReader;

    public function __construct(CsvReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    #[Route('/reservations', name: 'reservations_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $reservations = $this->csvReader->getReservations();
        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $reservations = array_filter($reservations, function ($reservation) use ($searchTerm) {
                return stripos(implode(' ', $reservation), $searchTerm) !== false;
            });
        }

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/reservations/download', name: 'reservations_download', methods: ['GET'])]
    public function download(): Response
    {
        $reservations = $this->csvReader->getReservations();
        $json = json_encode($reservations, JSON_PRETTY_PRINT);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', 'attachment; filename="reservations.json"');

        return $response;
    }
}
