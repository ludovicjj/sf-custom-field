<?php

namespace App\Service;

use App\Entity\Departement;
use App\Entity\Region;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;

class ImportCitiesService
{
    public function __construct(private string $projectDir, private EntityManagerInterface $entityManager)
    {
    }

    public function importCities(SymfonyStyle $io): void
    {
        $io->title('Import Cities');
        $datas = $this->readCsvFile();
        $io->progressStart(count($datas));

        // purge tables
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeQuery($platform->getTruncateTableSQL('ville', true));
        $connection->executeQuery($platform->getTruncateTableSQL('region', true));
        $connection->executeQuery($platform->getTruncateTableSQL('departement', true));

        $regions = [];
        $departements = [];
        $villes = [];

        foreach ($datas as $data) {
            // Regions
            if (!key_exists($data['region_name'], $regions)) {
                $region = new Region();
                $region->setName($data['region_name']);
                $regions[$data["region_name"]] = $region;
                $this->entityManager->persist($region);
            } else {
                $region = $regions[$data["region_name"]];
            }

            // Departement
            if (!key_exists($data['department_number'], $departements)) {
                $departement = new Departement();
                $departement->setName($data["department_name"]);
                $departement->setCode((int)$data["department_number"]);
                $departement->setRegion($region);
                $departements[$data['department_number']] = $departement;
                $this->entityManager->persist($departement);
            } else {
                $departement = $departements[$data['department_number']];
            }

            // Ville
            $ville = new Ville();
            $ville->setName($data['label']);
            $ville->setCode($data['zip_code']);
            $ville->setDepartement($departement);
            $this->entityManager->persist($ville);

            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('Import finish');
    }

    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath($this->projectDir .'/import/cities.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}