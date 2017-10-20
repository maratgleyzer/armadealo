<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_TRANSITIONAL');
        $view->headTitle('armadealo | mobile coupon system..');
//		$view->headLink()->setStylesheet('/gpsol/public/css/gpsol.css');
//		$view->headScript()->appendFile('/gpsol/public/js/script.js');
		$view->headLink()->setStylesheet('/css/layout.css');
		$view->headScript()->appendFile('/js/main.js');
		
//		$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');

//		$view->dojo()->enable()
//       		  		 ->setDjConfigOption('parseOnLoad', true)
//       		  		 ->addStylesheet('/gpsol/public/css/tundra.css')
//             		 ->requireModule('dijit.layout.AccordionContainer')
//					 ->setCdnVersion('1.3');
             		 
//		Zend_Dojo_View_Helper_Dojo::setUseDeclarative();

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

}

