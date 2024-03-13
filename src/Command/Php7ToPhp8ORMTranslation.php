<?php
namespace App\Command;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use App\Command\Php7ToPhp8ORMTranslation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Php7ToPhp8ORMTranslation extends Command
{
    // Nom de la commande (partie après "bin/console")
    protected static $defaultName = 'tirbois:migrate-php7-to-8';

    protected function configure(): void
    {
        $this
            ->setDescription('Remplace les annotations de PHP7 par des Attributs pour PHP8.')
            ->setHelp('Cette commande vous permet de migrer le code de PHP 7 à PHP 8...')
            ->addOption('mapping-file', null, InputOption::VALUE_REQUIRED, 'Chemin vers le fichier YAML de correspondance');        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mappingFile = $input->getOption('mapping-file');

        if (!file_exists($mappingFile)) {
            throw new \InvalidArgumentException("Le fichier de correspondance '$mappingFile' n'existe pas.");
        }

        $mappingData = Yaml::parseFile($mappingFile);

        if ($mappingData === null) {
            throw new \InvalidArgumentException("Erreur lors de la lecture du fichier YAML.");
        }

        $rules = $mappingData['rules'] ?? [];

        foreach ($rules as $rule) {
            $find = $rule['find'] ?? null;
            $replace = $rule['replace'] ?? null;

            $output->writeln("find : $find") ;
            $output->writeln("replace : $replace") ;
        
            if ($find !== null && $replace !== null) {
                $finder = new Finder();
                $finder->files()->in(__DIR__.'/../../src')->name('*.php');
        
                foreach ($finder as $file) {
                    $filePath = $file->getRealPath();
                    $fileContent = file_get_contents($filePath);


                    if (preg_match_all($find, $fileContent, $matches)) {
                        foreach ($matches[0] as $value) {        
                            $updatedContent = preg_replace($find, $replace, $fileContent);
                            
                            if ($updatedContent !== $fileContent) {
                                file_put_contents($filePath, $updatedContent);
                                $output->writeln("Mise à jour effectuée dans : $filePath ");
                            }
                        }
                    }
                }
            }
        }        
        $output->writeln('Terminé !');

        return Command::SUCCESS;
    }
}
