<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
          
            new Cerad\Bundle\CoreBundle   \CeradCoreBundle(),
            new Cerad\Bundle\ProjectBundle\CeradProjectBundle(),
            new Cerad\Bundle\PersonBundle \CeradPersonBundle(),
            new Cerad\Bundle\UserBundle   \CeradUserBundle(),
            new Cerad\Bundle\GameBundle   \CeradGameBundle(),
            new Cerad\Bundle\LevelBundle  \CeradLevelBundle(),
            new Cerad\Bundle\TournBundle  \CeradTournBundle(),
            new Cerad\Bundle\OrgBundle    \CeradOrgBundle(),
            new Cerad\Bundle\AppBundle    \CeradAppBundle(),
            
            new Cerad\Bundle\AppCeradBundle\CeradAppCeradBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
