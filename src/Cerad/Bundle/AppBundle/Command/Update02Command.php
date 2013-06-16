<?php

namespace Cerad\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update02Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:schedule:update02')
            ->setDescription('App Schedule SF Teams')
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
                
        $gameManager = $this->getService('cerad.game.repository');
        
        // U10B
        $game133U10B_SF1 = $gameManager->loadGameForProjectNum($project,133);
        $game134U10B_SF2 = $gameManager->loadGameForProjectNum($project,134);
        
        $game133U10B_SF1->getHomeTeam()->setName('R1 Joyce 132');
        $game133U10B_SF1->getAwayTeam()->setName('B1 Grussing 275');
        $game134U10B_SF2->getHomeTeam()->setName('G1 Karim 605');
        $game134U10B_SF2->getAwayTeam()->setName('WC Graham 279');
        
        // U10G
        $game184U10G_SF1 = $gameManager->loadGameForProjectNum($project,184);
        $game185U10G_SF2 = $gameManager->loadGameForProjectNum($project,185);
        
        $game184U10G_SF1->getHomeTeam()->setName('R1 Rossetti 498');
        $game184U10G_SF1->getAwayTeam()->setName('B1 Lauderback 279');
        $game185U10G_SF2->getHomeTeam()->setName('G1 Brummitt 275');
        $game185U10G_SF2->getAwayTeam()->setName('WC Delgado 722');
        
        // U12B (note, they changed the game numbers
        $game233U12B_SF1 = $gameManager->loadGameForProjectNum($project,233);
        $game232U12B_SF2 = $gameManager->loadGameForProjectNum($project,232);
        
        $game233U12B_SF1->getHomeTeam()->setName('R1 Turner 132');
        $game233U12B_SF1->getAwayTeam()->setName('WC Mischlich 124');
        $game232U12B_SF2->getHomeTeam()->setName('G1 Wessler 605');
        $game232U12B_SF2->getAwayTeam()->setName('B1 Qatawi 132');
        
        // U12G
        $game283U12G_SF1 = $gameManager->loadGameForProjectNum($project,283);
        $game284U12G_SF2 = $gameManager->loadGameForProjectNum($project,284);
        
        $game283U12G_SF1->getHomeTeam()->setName('R1 Hethcoat 727');
        $game283U12G_SF1->getAwayTeam()->setName('B1 Nease 132');
        $game284U12G_SF2->getHomeTeam()->setName('G1 Rasmussen 132');
        $game284U12G_SF2->getAwayTeam()->setName('B1 Elam 279');
        
       // U14B
        $game425U14B_SF1 = $gameManager->loadGameForProjectNum($project,425);
        $game426U14B_SF2 = $gameManager->loadGameForProjectNum($project,426);
        
        $game425U14B_SF1->getHomeTeam()->setName('R1 Mullins 337');
        $game425U14B_SF1->getAwayTeam()->setName('B1 Kohler 335');
        $game426U14B_SF2->getHomeTeam()->setName('G1 Rines 335');
        $game426U14B_SF2->getAwayTeam()->setName('WC Womick 1588');
        
       // U14G
        $game473U14G_SF1 = $gameManager->loadGameForProjectNum($project,473);
        $game474U14G_SF2 = $gameManager->loadGameForProjectNum($project,474);
        
        $game473U14G_SF1->getHomeTeam()->setName('R1 Woods 605');
        $game473U14G_SF1->getAwayTeam()->setName('B2 Rossetti 498');
        $game474U14G_SF2->getHomeTeam()->setName('B1 Phonthibsvads 160');
        $game474U14G_SF2->getAwayTeam()->setName('R2 Stinson 160');
        
        // Done
        $gameManager->flush();
      }
}
?>
