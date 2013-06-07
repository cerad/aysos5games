<?php

namespace Cerad\Bundle\AppBundle\Command;

use Zayso\ArbiterBundle\Schedule\LoadLesSchedule;
use Zayso\ArbiterBundle\Schedule\SaveArbiterSchedule;
use Zayso\ArbiterBundle\Schedule\SaveRefereeSchedule;

use Zayso\ArbiterBundle\Schedule\LoadArbiterSchedule;
use Zayso\ArbiterBundle\Schedule\CompareSchedules;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:schedule:import')
            ->setDescription('App Schedule')
        ;
    }
    protected function getService($id)     { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = 'Schedule20130607x.xls';
        $datax = $this->getParameter('datax');
        
        $project = $this->getService('cerad_tourn.project');
        
        $params = array
        (
            'truncate'  => true,
            'persist'   => true,
            'output'    => 'Post', // Scan, Excel
            'project'   => $project,
            
          //'sport'     => 'Soccer',
          //'season'    => 'SP2013',
          //'domain'    => 'AYSOA5B',
          //'domainSub' => 'Games',
            
            'defaultGameStatus' => 'Normal',
            'inputFileName'     => $datax . $file,
          //'worksheetName'     => 'Schedule',
        );
        $import = $this->getService('cerad_app.schedule.import');
        
        $results = $import->importFile($params);

      //$this->testLoadLesSchedule();
      //$this->testLoadArbiterSchedule();
    }
}
?>
