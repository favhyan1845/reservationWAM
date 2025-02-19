<?php

namespace App\Controller;

use App\Service\CsvReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controlador que maneja las reservas.
 */
class ReservationController extends AbstractController
{
    private CsvReader $csvReader; // Propiedad para almacenar el servicio CsvReader

    /**
     * Constructor del controlador.
     * Symfony inyectará el servicio CsvReader automáticamente.
     */
    public function __construct(CsvReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Ruta: "/reservations"
     * Nombre de la ruta: "reservations_index"
     *
     * Muestra la lista de reservas y permite buscar por término en el CSV.
     */
    #[Route('/reservations', name: 'reservations_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Obtiene todas las reservas desde el archivo CSV
        $reservations = $this->csvReader->getReservations();

        // Obtiene el parámetro "search" de la URL, si existe
        $searchTerm = $request->query->get('search');

        // Filtra las reservas si hay un término de búsqueda
        if ($searchTerm) {
            $reservations = array_filter($reservations, function ($reservation) use ($searchTerm) {
                // Convierte cada reserva en un string y verifica si contiene el término de búsqueda
                return stripos(implode(' ', $reservation), $searchTerm) !== false;
            });
        }

        // Renderiza la plantilla Twig "reservation/index.html.twig" con los datos de reservas y búsqueda
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'searchTerm' => $searchTerm,
        ]);
    }

    /**
     * Ruta: "/reservations/download"
     * Nombre de la ruta: "reservations_download"
     *
     * Convierte las reservas en formato JSON y permite descargarlas.
     */
    #[Route('/reservations/download', name: 'reservations_download', methods: ['GET'])]
    public function download(): Response
    {
        // Obtiene todas las reservas desde el archivo CSV
        $reservations = $this->csvReader->getReservations();

        // Convierte el array de reservas a formato JSON con formato legible
        $json = json_encode($reservations, JSON_PRETTY_PRINT);

        // Crea una respuesta HTTP con el JSON como contenido
        $response = new Response($json);

        // Establece las cabeceras para indicar que es un archivo JSON descargable
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', 'attachment; filename="reservations.json"');

        return $response;
    }
}
