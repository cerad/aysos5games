<?php
namespace Cerad\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

class ExportPersons2013Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad_app__persons2013__export')
            ->setDescription('Export Persons');
        ;
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
        
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn =  $this->getService('doctrine.dbal.s5games2013_connection');
        $export = new ExportPersons2013($conn);
        
        $persons = $export->process();
        echo sprintf("person count: %d\n",count($persons));
        
        file_put_contents('data/Persons.yml',Yaml::dump($persons,10));
        return;
   }
}
?>
