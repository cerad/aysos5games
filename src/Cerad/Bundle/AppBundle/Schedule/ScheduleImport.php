<?php
namespace Cerad\Bundle\AppBundle\Schedule;

use Cerad\Component\Excel\Loader as BaseLoader;

class ScheduleImport extends BaseLoader
{
    protected $record = array
    (
        'num'      => array('cols' => 'Game','req' => true),
        'dow'      => array('cols' => 'Dow',   'req' => true),
        'time'     => array('cols' => 'Time',  'req' => true),
        
        'level'    => array('cols' => 'Pool', 'req' => true),
        'field'    => array('cols' => 'Field', 'req' => true),
        'type'     => array('cols' => 'Type',  'req' => true),
        
        'homeTeam' => array('cols' => 'Home Team',  'req' => true),
        'awayTeam' => array('cols' => 'Away Team',  'req' => true),
    );
    protected $params;
    
    public function __construct($manager)
    {
        parent::__construct();
        $this->manager        = $manager;
        $this->gameManager    = $manager->gameManager;
        $this->fieldManager   = $manager->fieldManager;
        $this->levelManager   = $manager->levelManager;
        $this->projectManager = $manager->projectManager;
    }
    protected function processItem($item)
    {
        $project = $this->project;
        
        $num = (integer)$item['num'];
        if (!$num) return;
        
        switch($item['dow'])
        {
            case 'Fri': $date = '06-14-2013'; break;
            case 'Sat': $date = '06-15-2013'; break;
            case 'Sun': $date = '06-16-2013'; break;
            default:
                print_r($item); die('*** DOW ***');
        }
        $time = $item['time'];
        if (strlen($time) == 3) $time = '0' . $time;
        $time = substr($time,0,2) . ':' . substr($time,2,2) . ':00';
        
        $dtBeg = \DateTime::createFromFormat('m-d-Y*H:i:s',$date . ' ' . $time);

        $dt = $dtBeg->format('Y-m-d H:i');
       
        // Level processing
        $level = $item['level'];
        $age =   substr($level,0,3);
        $sex =   substr($level,3,1);
        
        switch($age)
        {
            case 'U10': $duration = 50; break;
            case 'U12': $duration = 60; break;
            case 'U14': $duration = 70; break;
            case 'U16': $duration = 75; break;
            case 'U19': $duration = 80; break;
            default:
                print_r($item); die('*** AGE ***');
        }
        $params = array
        (
            'sport'     => $project->getSport(),
            'domain'    => $project->getDomain(),
            'domainSub' => $project->getDomainSub(),
            'name'      => $level,
            'age'       => $age,
            'sex'       => $sex,
        );
        $level = $this->levelManager->processEntity($params,$this->persistFlag);
        
        // Field processing
        $field = $item['field'];
        $venue = null;
        if (substr($field,0,2) == 'BC')   $venue = 'BC';
        if (substr($field,0,2) == 'NB')   $venue = 'NB';
        if (substr($field,0,4) == 'Pell') $venue = 'Pell';
       
        $params = array
        (
            'season'    => $project->getSeason(),
            'domain'    => $project->getDomain(),
            'domainSub' => $project->getDomainSub(),
            'name'      => $field,
            'venue'     => $venue,
            'venueSub'  => null,
        );
        $field = $this->fieldManager->processEntity($params,$this->persistFlag);

        // Actually pool type
        $pool = $item['type'];
                
        /* ==========================================================
         * Create game unless have one
         */
        $gameManager = $this->gameManager;
        $game = $gameManager->loadGameForProjectNum($project,$num);
        if (!$game)
        {   
            $game = $gameManager->createGame();
            $gameManager->persist($game);
            
            $homeTeam = $gameManager->createGameTeamHome();
            $awayTeam = $gameManager->createGameTeamAway();
            
            $game->addTeam($homeTeam);
            $game->addTeam($awayTeam);
            
            $gameManager->createGamePerson(array('game' => $game, 'status'=> 'Open', 'slot' => 1, 'role'=> 'Referee'));
            $gameManager->createGamePerson(array('game' => $game, 'status'=> 'Open', 'slot' => 2, 'role'=> 'AR1'));
            $gameManager->createGamePerson(array('game' => $game, 'status'=> 'Open', 'slot' => 3, 'role'=> 'AR2'));
            
        }
        else
        {
            $homeTeam = $game->getHomeTeam();
            $awayTeam = $game->getAwayTeam();            
        }
        // Could do an array thing here
        $game->setNum    ($num);
        $game->setProject($project);
        $game->setLevel  ($level);
        $game->setField  ($field);
        $game->setPool   ($pool);
        
        $game->setDtBeg($dtBeg);
        $game->setDtEnd($dtBeg);
        
        $homeTeam->setLevel($level);
        $awayTeam->setLevel($level);
        
        $homeTeam->setName($item['homeTeam']);
        $awayTeam->setName($item['awayTeam']);
        
        $gameManager->flush();
        
        return;
        
        echo sprintf("DT %s %s %d %s\n",$dt,$item['dow'],$duration,$level->getName());
        return;
    }
    protected $persistFlag = false;
    
    public function importFile($params)
    {   
        $this->persistFlag = $params['persist'];
        
        // All games belong to one project, season,sport,domain,domainSub
        $this->project = $this->projectManager->processEntity($params['project']->getInfo(),$this->persistFlag);
        
        // The file
        $inputFileName = $params['inputFileName'];
        if (isset($params['worksheetName'])) $worksheetName = $params['worksheetName'];
        else                                 $worksheetName = null;
        
        $results = $this->load($inputFileName,$worksheetName);
        
        return $results;
        
    }
}
?>
