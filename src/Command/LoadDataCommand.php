<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Vehicle;

class LoadDataCommand extends Command
{
    private $csvParsingOptions = array(
        'finder_in' => 'assets/data/',
        'finder_name' => 'test.csv',
        'ignoreFirstLine' => true
    );

    protected static $defaultName = 'loaddata';
    protected static $defaultDescription = 'Load data from CSV.';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }
    
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $csv = $this->parseCSV();
        $this->bulkInsert($csv);

        $io->success('Successfully loaded data from the csv.');

        return Command::SUCCESS;
    }

    /**
     * Insert rows into the database
     * @param array $rows data to be inserted
     * @return void
     */
    private function bulkInsert($rows) : void 
    {
        $em = $this->entityManager;
        $batchSize = 20;
        for ($i = 1; $i <= count($rows); ++$i) {
            $vehicle = new Vehicle;
            $row = $rows[$i - 1];
            $vehicle->setYear($row[1]);
            $vehicle->setMake($row[2]);
            $vehicle->setModel($row[3]);
            $vehicle->setBodyStyle($row[4]);
            $vehicle->setColor($row[5]);
            $vehicle->setVin($row[6]);
            $vehicle->setOdometer($row[7]);
            $vehicle->setEngineSize($row[8]);
            $vehicle->setCurrentBid($row[9]);
            $vehicle->setSaleDate($row[10] === "NULL" ? null : new \DateTime($row[10]));
            $vehicle->setSaleStartAt($row[10] === "NULL" ? null : new \DateTime($row[11]));
            $vehicle->setLocationName($row[12]);
            $em->persist($vehicle);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }
        }
        $em->flush(); // Persist objects that did not make up an entire batch
        $em->clear();
    }

    /**
     * Parse a csv file
     * 
     * @return array
     */
    private function parseCSV(): array
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($this->csvParsingOptions['finder_name'])
        ;
        foreach ($finder as $file) {
            $csv = $file;
        }

        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
