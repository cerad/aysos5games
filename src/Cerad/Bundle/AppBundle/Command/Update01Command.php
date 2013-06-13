<?php

namespace Cerad\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update01Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:schedule:update01')
            ->setDescription('App Schedule Update #1')
        ;
    }
    protected function getService($id)     { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function getProject()
    {
        // Need a better way to get the actual project object
        $projectManager = $this->getService('cerad.project.repository');
        $projectParams  = $this->getService('cerad_tourn.project');
        $projectEntity  = $projectManager->findOneBy(array('hash' => $projectParams->getKey()));
        return $projectEntity;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $project = $this->getProject();
        
        $levelManager = $this->getService('cerad.level.repository');
        
        $levelU19B     = $levelManager->loadLevelForName($project->getDomain(),'U19B');
        $levelU19GBlue = $levelManager->loadLevelForName($project->getDomain(),'U19G Blue');
        
        $fieldManager = $this->getService('cerad.field.repository');
        $fieldPell1 = $fieldManager->loadFieldForName($project->getDomain(),'Pell 1');
        
        $gameManager = $this->getService('cerad.game.repository');
        
        $game901 = $gameManager->loadGameForProjectNum($project,901);
        $game953 = $gameManager->loadGameForProjectNum($project,953);
        
        $game955 = $gameManager->loadGameForProjectNum($project,955);
        $game903 = $gameManager->loadGameForProjectNum($project,903);
        
        $game905 = $gameManager->loadGameForProjectNum($project,905);
        $game906 = $gameManager->loadGameForProjectNum($project,906);
        
        // Friday
        $game901->setNum(-953);
        $game901->setLevel($levelU19GBlue);
        $game901->getHomeTeam()->setName ('Avella 275');
        $game901->getAwayTeam()->setName ('Click 335');
        $game901->getHomeTeam()->setLevel($levelU19GBlue);
        $game901->getAwayTeam()->setLevel($levelU19GBlue);
      //$game901->setStatus('Normal');
        
        $game953->setNum(-901);
        $game953->setLevel($levelU19B);
        $game953->getHomeTeam()->setName('Monnet 498');
        $game953->getAwayTeam()->setName('Martin 605');
        $game953->getHomeTeam()->setLevel($levelU19B);
        $game953->getAwayTeam()->setLevel($levelU19B);
        
        // Saturday
        $game903->setNum(-955);
        $game903->setLevel($levelU19GBlue);
        $game903->getHomeTeam()->setName ('Grady 498');
        $game903->getAwayTeam()->setName ('Avella 275');
        $game903->getHomeTeam()->setLevel($levelU19GBlue);
        $game903->getAwayTeam()->setLevel($levelU19GBlue);

        $game955->setNum(-903);
        $game955->setLevel($levelU19B);
        $game955->getHomeTeam()->setName('Monnet 498');
        $game955->getAwayTeam()->setName('Martin 605');
        $game955->getHomeTeam()->setLevel($levelU19B);
        $game955->getAwayTeam()->setLevel($levelU19B);
        
        // Sunday
        $game905->getHomeTeam()->setName('Roth 337');
        
        $game906->getHomeTeam()->setName('Roth 337');
        $game906->getAwayTeam()->setName('Monnet 498');
        
        $game906->setField($fieldPell1);
        
        $dt = new \DateTime('2013-06-16 16:00:00');
        $game906->setDtBeg($dt);
        
        // First Flush
        $gameManager->flush();
        
        // Need this because of dup stuff
        $game901->setNum(953);
        $game953->setNum(901);
        $game903->setNum(955);
        $game955->setNum(903);
        $gameManager->flush();
        
        
     }
}
?>
