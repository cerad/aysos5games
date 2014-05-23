<?php

namespace Cerad\Bundle\AppBundle\Action\Index;

use Symfony\Component\HttpFoundation\Request;

use Cerad\Bundle\CoreBundle\Action\ActionController;

class IndexController extends ActionController
{   
    public function action(Request $request)
    {   
        // Try defining the redirects in the route
        $redirectAttr = $this->isGranted('ROLE_USER') ? '_redirectHome' : '_redirectWelcome';
        
        return $this->redirectResponse($request->attributes->get($redirectAttr));
    }
}
