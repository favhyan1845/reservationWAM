<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class CsvReader
{
    private string $csvFilePath;

    /**
     * Constructor del controlador.
     * Symfony inyectará el servicio KernelInterface automáticamente.
     */
    public function __construct(KernelInterface $kernel)
    {
        // Obtiene la ruta absoluta del archivo CSV
        $this->csvFilePath = $kernel->getProjectDir() . '/public/reservations.csv';
    }

    /**
     * Servicio para leer el archivo CSV.
     * 
     */
    public function getReservations(): array
    {
        $reservations = [];

        // Verifica si el archivo existe antes de intentar leerlo
        if (!file_exists($this->csvFilePath)) {
            throw new \Exception("El archivo CSV no existe en: " . $this->csvFilePath);
        }

        // Lee el contenido del archivo CSV
        if (($handle = fopen($this->csvFilePath, "r")) !== false) {
            $headers = fgetcsv($handle); // Lee la primera línea como encabezados

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $reservations[] = array_combine($headers, $data);
            }

            fclose($handle);
        }

        return $reservations;
    }
}
