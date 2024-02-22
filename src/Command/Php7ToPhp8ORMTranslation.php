<?php
namespace App\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigratePhp7ToPhp8Command extends Command
{
    // Nom de la commande (partie après "bin/console")
    protected static $defaultName = 'tirbois:migrate-php7-to-8';

    protected function configure(): void
    {
        $this
            ->setDescription('Remplace les annotations de PHP7 par des Attributs pour PHP8.')
            ->setHelp('Cette commande vous permet de migrer le code de PHP 7 à PHP 8...');        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO écrire le code de la conversion: lire les fichiers + remplacer les annotations par des attributs
        $content = $input->getArgument('content');
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../')->name('*.php');

        foreach ($finder as $file) {
            $filePath = $file->getRealPath();
            $fileContent = file_get_contents($filePath);

            // Utilisation d'une expression régulière pour remplacer /** */ par /** contenu spécifique */
            $updatedContent = preg_replace('/\/\*\* *\*\/\s*/', "/**\n * $content\n */\n", $fileContent);

            if ($updatedContent !== null && $updatedContent !== $fileContent) {
                file_put_contents($filePath, $updatedContent);
                $output->writeln("Mise à jour effectuée dans : $filePath");
            }
        }

        $output->writeln('Terminé !');

        return Command::SUCCESS;
    }
}
