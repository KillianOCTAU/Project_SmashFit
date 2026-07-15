<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BwfApiService
{
    private const FFBAD_URL = 'https://www.ffbad.org/api';

    public function __construct(
        private readonly HttpClientInterface $http,
        private readonly string $apiKey = 'demo_key',
    ) {}

    public function rechercherClubs(string $query): array
    {
        try {
            $response = $this->http->request('GET', self::FFBAD_URL . '/clubs/search', [
                'timeout' => 4.0,
                'headers' => ['Authorization' => 'Bearer ' . $this->apiKey],
                'query'   => ['q' => $query, 'limit' => 10],
            ]);
            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return array_map(fn($c) => [
                    'id'   => $c['id']   ?? uniqid(),
                    'nom'  => $c['name'] ?? $c['nom'] ?? 'Club inconnu',
                    'ville'=> $c['city'] ?? $c['ville'] ?? '',
                ], $data['clubs'] ?? $data);
            }
        } catch (\Exception) {}

        return $this->demoClubs($query);
    }

    public function verifierLicence(string $numero): array
    {
        try {
            $response = $this->http->request('GET', self::FFBAD_URL . '/licences/' . urlencode($numero), [
                'timeout' => 4.0,
                'headers' => ['Authorization' => 'Bearer ' . $this->apiKey],
            ]);
            if ($response->getStatusCode() === 200) {
                return array_merge(['valide' => true], $response->toArray());
            }
        } catch (\Exception) {}

        return [
            'valide'  => true,
            'demo'    => true,
            'message' => 'Vérification indisponible (mode démo)',
        ];
    }

    public function getActualites(int $n = 3): array
    {
        return [
            ['titre' => "Championnats d'Europe 2025 – Résultats",  'date' => '2025-03-15'],
            ['titre' => 'Victor Axelsen conserve sa place de N°1', 'date' => '2025-03-10'],
            ['titre' => 'Nouvelles règles BWF pour 2025',          'date' => '2025-02-28'],
        ];
    }

    private function demoClubs(string $query): array
    {
        $clubs = [
            ['id' => '1', 'nom' => 'Badminton Club Lillois',  'ville' => 'Lille'],
            ['id' => '2', 'nom' => 'BC Roubaix',              'ville' => 'Roubaix'],
            ['id' => '3', 'nom' => 'Smash Club Tourcoing',    'ville' => 'Tourcoing'],
            ['id' => '4', 'nom' => "AS Villeneuve Badminton", 'ville' => "Villeneuve d'Ascq"],
            ['id' => '5', 'nom' => 'BC Dunkerque',            'ville' => 'Dunkerque'],
        ];

        if (!$query) return $clubs;

        $q = strtolower($query);
        return array_values(array_filter($clubs, fn($c) =>
            str_contains(strtolower($c['nom']), $q) ||
            str_contains(strtolower($c['ville']), $q)
        ));
    }
}