<?php

namespace Cerad\Bundle\AppBundle\Action\Welcome;

use Symfony\Component\HttpFoundation\Request;

use Cerad\Bundle\CoreBundle\Action\ActionController;

class WelcomeController extends ActionController
{   
    public function action(Request $request)
    {   
        // And render, pass the model directly to the view?
        $tplName = $request->attributes->get('_template');
        $tplData = array();
        return $this->templating->renderResponse($tplName, $tplData);
    }
}
