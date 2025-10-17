<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route(
        '/weather/{slug}',
        name: 'app_weather',
        requirements: [
            'slug' => '[\p{L}\p{M}\-]+(?:-[A-Za-z]{2})?'
        ]
    )]
    public function city(
        string $slug,
        LocationRepository $locationRepository,
        MeasurementRepository $measurementRepository
    ): Response {
        $parts = explode('-', $slug);
        $countryCode = null;
        $lastPart = end($parts);
        if (preg_match('/^[A-Za-z]{2}$/', $lastPart)) {
            $countryCode = strtoupper($lastPart);
            array_pop($parts);
        }

        $cityName = implode(' ', array_map('ucfirst', $parts));

        $criteria = ['city' => $cityName];
        if ($countryCode) {
            $criteria['country'] = $countryCode;
        }

        $location = $locationRepository->findOneBy($criteria);
        if (!$location) {
            throw new NotFoundHttpException(sprintf('Nie znaleziono lokalizacji: %s', $slug));
        }

        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

}
