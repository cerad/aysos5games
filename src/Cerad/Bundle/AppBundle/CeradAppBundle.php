<?php
namespace Cerad\Bundle\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Cerad\Bundle\AppBundle\DependencyInjection\AppExtension;

class CeradAppBundle extends Bundle
{  
    /* ====================================================
     * Wanted to put app templates under AppBundle\Resources
     * 
     * This works
        return $this->render('CeradTournBundle::welcome.html.twig', $tplData);
     * This does not
        return $this->render('@CeradTourn/welcome.html.twig', $tplData);
     */
    public function getParent()
    {
        return 'CeradTournBundle';
    }
    public function getContainerExtension()
    {
        return new AppExtension();
    }
}   
?>
