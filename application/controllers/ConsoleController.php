<?php

class ConsoleController extends Zend_Controller_Action
{	

	private static $VALID_CHARACTERS = array("A-Z0-9",",",".","@","#","^","'","`","~","&","_","+","-","s","/","$","%","=",":");

	public $authorized = "";
	
	public function init()
	{
		$authorized = new Zend_Session_Namespace('authorized');

		if (!($authorized->vid > 0))
    	{
			$this->_redirect('../');
    		exit;
    	}
    	$this->_helper->layout->setLayout('console');
    	
    	$this->view->useHomePane = 'false';
    	$this->view->useReportPane = 'false';
    	$this->view->useOfferPane = 'false';
    	$this->view->useProfilePane = 'false';
    	$this->view->useStorePane = 'false';
    	$this->view->useDistributionPane = 'false';

    	$this->vendor = $authorized->vid;
    	$this->view->username = $authorized->username;
    	$this->view->vendor_name = $authorized->vendor_name;
    	$this->contact_name = $authorized->contact_name;
    	$this->contact_phone = $authorized->contact_phone;
    	$this->primary_address = $authorized->primary_address;
    	$this->primary_country = $authorized->primary_country;
    	$this->primary_zip = $authorized->primary_zip; 

    	$this->view->console = array();
    	
		// getHref() returns /
		$this->view->console['home'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'index',
    			'controller' => 'console'
				));

		// getHref() returns /blog/post/view
		$this->view->console['stores'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'stores',
    			'controller' => 'console'
				));

		// getHref() returns /blog/post/view/id/1337
		$this->view->console['offers'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'offers',
    			'controller' => 'console'
				));

		// getHref() returns /blog/post/view/id/1337
		$this->view->console['distribution'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'distribution',
    			'controller' => 'console'
				));

		// getHref() returns /blog/post/view/id/1337
		$this->view->console['reporting'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'reports',
    			'controller' => 'console'
    			));
    			
		// getHref() returns /blog/post/view/id/1337
		$this->view->console['account'] = new Zend_Navigation_Page_Mvc(array(
    			'action'     => 'account',
    			'controller' => 'console'
    			));

    	$this->has_a_store = $this->doesStoreExist();  	
    	$this->has_a_offer = $this->doesOfferExist();
    	
    	$this->multiple_store_titles = $this->hasMultipleStoreTitles();
    	$this->multiple_store_states = $this->hasMultipleStoreStates();
    	$this->multiple_store_countries = $this->hasMultipleStoreCountries();
    	
    	if ($this->multiple_store_titles ||
    		$this->multiple_store_states ||
    		$this->multiple_store_countries)
			$this->view->has_distribution = true;
	
	}
	
    public function indexAction()
    {
//  	    if (!$this->has_a_store) $this->_redirect('/console/stores');
//  	    if (!$this->has_a_offer) $this->_redirect('/console/offers');
  	    
    	$this->view->toolPane = $this->view->render('console/index.phtml');
    	
    }
      
    

    
    
    
    public function offersAction()
    {
    	
    	
    	if (!$this->has_a_store)
    	{
    		$this->_redirect('/console/stores');
    		return false;
    	}    	
    	
//    	if (!$this->has_a_offer)
//    	{
//    		$this->view->save_error = true;
//    	}
    	
	    $VALID_REGEX = "/^[".implode("\\",self::$VALID_CHARACTERS)."]+$/i";
    	
    	$form = new Zend_Form;
		
    	// Create and configure offer title element:
		$ot = $form->createElement('text', 'offer_head', array('label' => 'The title of your offer:', 'maxlength' => 48));
		$ot->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array($VALID_REGEX))
		   ->addValidator('stringLength', false, array(8, 48))
//    ->addValidator('StringLength', array(5,12, 'message' => array(
//	Zend_Validate_StringLength::TOO_SHORT => '%s must be at least %min% characters.',
//	Zend_Validate_StringLength::TOO_LONG => '%s must be shorter than %max% characters.'
//    )));
		   ->setRequired(true)
           ->addFilter('StringTrim');
         		   
    	// Create and configure password element:
		$ob = $form->createElement('textarea', 'offer_body', array('label' => 'Details about your offer:', 'maxlength' => 128, 'rows' => 3));
		$ob->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('StringLength', false, array(8, 128))
           ->setRequired(true)
		   ->addFilter('StringTrim');

    	// Create and configure username element:
		$oe = $form->createElement('text', 'expires', array('label' => 'When does it expire? (YYYY-MM-DD)', 'id' => 'offerDate', 'maxlength' => 10));
		$oe->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array('/^[0-9\-]+$/'))
		   ->addValidator('stringLength', false, array(6, 10))
		   ->addValidator('date', false)
		   ->setRequired(true)
   		   ->addFilter('StringTrim');

    	// Create and configure confirmation element:
		$oc = $form->createElement('text', 'offer_code', array('label' => 'What is the offer code?', 'maxlength' => 16));
		$oc->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array($VALID_REGEX))
		   ->addValidator('StringLength', false, array(4, 16))
		   ->setRequired(false)
		   ->addFilter('StringTrim');

    	// Create and configure username element:
		$ol = $form->createElement('text', 'offer_link', array('label' => 'Link it to your website:', 'id' => 'offerLink', 'maxlength' => 128));
		$ol->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array($VALID_REGEX))
		   ->addValidator('hostname', false)
		   ->addValidator('stringLength', false, array(8, 128))
		   ->setRequired(false)
           ->addFilter('StringTrim');
         		 	
        $submit = $form->createElement('submit', 'save_offer', array('label' => 'Save This Offer', 'class' => 'button'));
		$submit->removeDecorator('DtDdWrapper');
				

		// Add elements to form:
		$form->addElement($ot)
			 ->addElement($ob)
			 ->addElement($oe)
			 ->addElement($oc)
			 ->addElement($ol)
			 ->addElement($submit);
			 
		$this->view->ot = $ot;
		$this->view->ob = $ob;
		$this->view->oe = $oe;
		$this->view->oc = $oc;
		$this->view->ol = $ol;
		$this->view->submit = $submit;
			 
		if ($this->_request->getParam('add') == 1)
		$this->view->save_error = true;
		
    	if ($this->_request->isPost() && $this->_request->getParam('save_offer'))
		{
			if ($form->isValid($_POST))
			{
				$bootstrap = $this->getInvokeArg('bootstrap');
	    		$db = $bootstrap->getResource('db');
	    			
				$data['offer_head'] = ucwords(strtolower($form->getValue('offer_head')));
				$data['offer_body'] = ucfirst(strtolower($form->getValue('offer_body')));
				$data['expires'] = $form->getValue('expires');
				$data['offer_code'] = $form->getValue('offer_code');
				$data['offer_link'] = $form->getValue('offer_link');
				$data['vid'] = $this->vendor;
					
				$db->beginTransaction();
			
				try {
					$db->insert('offers', $data);
					$oid = $db->lastInsertId('offers', 'oid');

					if (!$this->view->has_distribution)
					{
						$sql = "select `sid` from stores where vid = $this->vendor and disable = 0";
						$results = $db->fetchAll($sql);

						if (!is_array($results) || count($results) < 1)
						throw new Exception("You have not added any stores to the system.","500");
							
						foreach ($results as $result)
						{
							$o2s_data['oid'] = $oid;
							$o2s_data['sid'] = $result['sid'];
							if (!$db->insert('offers2stores', $o2s_data))
							throw new Exception("You are attempting to create a duplicate distribution.","500");
						}
					}

					$db->commit();
					$this->view->save_error = false;
					$this->view->success = 'Your offer has been created and distributed for your stores.';

				} catch ( Exception $e ) {
					$db->rollback();
					$this->view->save_error = 'You are attempting to create a duplicate offer.';
				}
					
				$db->closeConnection();
					
			}	
				
			else
			{
				$this->view->save_error = true;
			}
		}

		//        $this->view->form = $form->render();
        //$this->view->offerPane = $this->view->render('console/offers-save.phtml');

    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');

	    $sql = "select oid,offer_head,offer_code,offer_link,created,expires,disable,disable as `served` from `offers` where vid = $this->vendor order by created desc";
    	$offers = $db->fetchAll($sql);

    	$db->closeConnection();

		$this->view->offers = $this->htmlEntityConvert($offers);    		
   		
   	    $saved = $this->_request->getParam('saved');
   	    
    	$this->view->toolPane = $this->view->render('console/offers.phtml');
    
    }
    
    
    
    
    
    

    
    
    
    
    
    
        
    
    public function storesAction()
    {

    	if (!$this->has_a_store)
    	$this->view->success = 'You must first add one or more stores to the system, before creating any offers.';
    	
	    $VALID_REGEX = "/^[".implode("\\",self::$VALID_CHARACTERS)."]+$/i";
    	
		$csv_form = new Zend_Form;
		
    	// Create and configure password element:
		$ln = $csv_form->createElement('text', 's_name', array('maxlength' => 2, 'size' => '3'));
		$ln->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(1, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(true);

    	// Create and configure password element:
		$la = $csv_form->createElement('text', 's_address', array('maxlength' => 2, 'size' => '3'));
		$la->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(1, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(true);
    	
    	// Create and configure password element:
		$lc = $csv_form->createElement('text', 's_city', array('maxlength' => 2, 'size' => '3'));
		$lc->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(false);
    	
    	// Create and configure password element:
		$ls = $csv_form->createElement('text', 's_state', array('maxlength' => 2, 'size' => '3'));
		$ls->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(false);
    	
    	// Create and configure password element:
		$lz = $csv_form->createElement('text', 's_zip', array('maxlength' => 2, 'size' => '3'));
		$lz->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(1, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(true);
    	
    	// Create and configure password element:
		$ly = $csv_form->createElement('text', 's_country', array('maxlength' => 2, 'size' => '3'));
		$ly->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(1, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(true);

    	// Create and configure password element:
		$ll = $csv_form->createElement('text', 's_latitude', array('maxlength' => 2, 'size' => '3'));
		$ll->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		   ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(false);

    	// Create and configure password element:
		$lo = $csv_form->createElement('text', 's_longitude', array('maxlength' => 2, 'size' => '3'));
		$lo->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		    ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(false);

    	// Create and configure password element:
		$lp = $csv_form->createElement('text', 's_phone', array('maxlength' => 2, 'size' => '3'));
		$lp->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->addValidator('StringLength', true, array(1, 2, 'messages' => '1 or 2 digits'))
		    ->addValidator('Int', true, array('messages' => 'letters entered'))
		   ->addValidator('NotEmpty', true, array('messages' => 'required'))
		   ->addFilter('StringTrim')
		   ->setRequired(true);

    	// Create and configure password element:
		$loh = $csv_form->createElement('text', 's_open', array('maxlength' => 2, 'size' => '3'));
		$loh->removeDecorator('label')
            ->removeDecorator('HtmlTag')
		    ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		    ->addValidator('Int', true, array('messages' => 'letters entered'))
		    ->addValidator('NotEmpty', true, array('messages' => 'required'))
		    ->addFilter('StringTrim')
		    ->setRequired(false);

    	// Create and configure password element:
		$lch = $csv_form->createElement('text', 's_close', array('maxlength' => 2, 'size' => '3'));
		$lch->removeDecorator('label')
            ->removeDecorator('HtmlTag')
		    ->addValidator('StringLength', true, array(0, 2, 'messages' => '1 or 2 digits'))
		    ->addValidator('Int', true, array('messages' => 'letters entered'))
		    ->addValidator('NotEmpty', true, array('messages' => 'required'))
		    ->addFilter('StringTrim')
		    ->setRequired(false);

    	// Create and configure password element:
		$fl = $csv_form->createElement('checkbox', 'ignore_first');
		$fl->removeDecorator('label')
           ->removeDecorator('HtmlTag')
		   ->setRequired(false);
		    
    	// Create and configure password element:
		$csv = $csv_form->createElement('file', 'csv_file', array('size' => '45'));
		$csv->removeDecorator('label')
            ->removeDecorator('HtmlTag')
		    ->addValidator('NotEmpty', true, array('messages' => 'required'))
		    ->addFilter('StringTrim')
		    ->setRequired(true); 
		    
        $csv_submit = $csv_form->createElement('submit', 'upload_csv', array('label' => 'Upload Your Store Location File', 'class' => 'button'));
		$csv_submit->removeDecorator('DtDdWrapper');

		// Add elements to form:
		$csv_form->addElement($ln)
				 ->addElement($la)
		 		 ->addElement($lc)
			 	 ->addElement($lz)
			 	 ->addElement($ls)
			 	 ->addElement($ly)
			 	 ->addElement($ll)
			 	 ->addElement($lo)
			 	 ->addElement($loh)
			 	 ->addElement($lch)
			 	 ->addElement($lp)
			 	 ->addElement($fl)
			 	 ->addElement($csv)
			 	 ->addElement($csv_submit);

		$this->view->ln = $ln;
		$this->view->la = $la;
		$this->view->lc = $lc;
		$this->view->lz = $lz;
		$this->view->ls = $ls;
		$this->view->ly = $ly;
		$this->view->ll = $ll;
		$this->view->lo = $lo;
		$this->view->loh = $loh;
		$this->view->lch = $lch;
		$this->view->lp = $lp;
		$this->view->fl = $fl;
		$this->view->csv = $csv;
		$this->view->csv_submit = $csv_submit;
		   
		

		
		
		
    	$store_form = new Zend_Form;
		
    	// Create and configure offer title element:
		$st = $store_form->createElement('text', 'store_title', array('label' => 'Store Name:', 'maxlength' => 32));
		$st->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('stringLength', false, array(4, 32))
//    ->addValidator('StringLength', array(5,12, 'message' => array(
//	Zend_Validate_StringLength::TOO_SHORT => '%s must be at least %min% characters.',
//	Zend_Validate_StringLength::TOO_LONG => '%s must be shorter than %max% characters.'
//    )));
		   ->setRequired(true)
           ->addFilter('StringTrim')
           ->setValue($this->view->vendor_name);
         		   
    	// Create and configure password element:
		$sa = $store_form->createElement('text', 'store_address', array('label' => 'Address:', 'maxlength' => 48));
		$sa->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('StringLength', false, array(6, 48))
            ->setRequired(true)
		    ->addFilter('StringTrim')
            ->setValue(!$this->has_a_store && !$this->_request->isPost() ? $this->primary_address : "");

    	// Create and configure username element:
		$sc = $store_form->createElement('text', 'store_city', array('label' => 'City:', 'maxlength' => 32));
		$sc->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('regex', false, array($VALID_REGEX))
		    ->addValidator('stringLength', false, array(2, 32))
		    ->setRequired(false)
   		    ->addFilter('StringTrim');

    	// Create and configure confirmation element:
		$sz = $store_form->createElement('text', 'store_zip', array('label' => 'Zip/Postal Code', 'id' => 'storeZip', 'maxlength' => 8));
		$sz->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('regex', false, array($VALID_REGEX))
		    ->addValidator('StringLength', false, array(4, 8))
		    ->setRequired(true)
		    ->addFilter('StringTrim')
		    ->setValue(!$this->has_a_store && !$this->_request->isPost() ? $this->primary_zip : "");

    	// Create and configure username element:
		$ss = $store_form->createElement('select', 'store_state', array('label' => 'State'));
		$ss->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('regex', false, array($VALID_REGEX))
		    ->addValidator('stringLength', false, array(2, 4))
		    ->setRequired(false)
            ->addFilter('StringTrim')
            ->addMultiOptions($this->getStateOptions('US'));
           

    	// Create and configure username element:
		$sy = $store_form->createElement('select', 'store_country', array('id' => 'storeCountry'));
		$sy->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array($VALID_REGEX))
		   ->addValidator('stringLength', false, array(2, 4))
		   ->setRequired(true)
           ->addFilter('StringTrim')
           ->setValue(!$this->has_a_store && !$this->_request->isPost() ? $this->primary_country : "")
           ->addMultiOptions($this->getCountryOptions());
           
    	// Create and configure username element:
		$sl = $store_form->createElement('text', 'store_lat', array('label' => 'Store Latitude:', 'maxlength' => 16));
		$sl->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array('/^[0-9\s\'\`\"\.\-]+$/i'))
		   ->addValidator('stringLength', false, array(2, 16))
		   ->setRequired(false)
           ->addFilter('StringTrim');
           
    	// Create and configure username element:
		$so = $store_form->createElement('text', 'store_lon', array('label' => 'Store Longitude:', 'maxlength' => 16));
		$so->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('regex', false, array('/^[0-9\s\'\`\"\.\-]+$/i'))
		   ->addValidator('stringLength', false, array(2, 16))
		   ->setRequired(false)
           ->addFilter('StringTrim');           
           
    	// Create and configure username element:
		$soh = $store_form->createElement('select', 'store_open', array('label' => 'Open?'));
		$soh->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('regex', false, array('/^[0-9\:]+$/i'))
		   	->addValidator('stringLength', false, array(8, 8))
		   	->setRequired(false)
           	->addFilter('StringTrim')
           	->addMultiOptions(array(
           '00:00:00' => 'Always',
           '05:00:00' => '5:00 AM',
           '06:00:00' => '6:00 AM',
           '07:00:00' => '7:00 AM',
           '08:00:00' => '8:00 AM',
           '09:00:00' => '9:00 AM',
           '10:00:00' => '10:00 AM',
           '11:00:00' => '11:00 AM',
           '12:00:00' => '12:00 PM',
           '13:00:00' => '1:00 PM',
           '14:00:00' => '2:00 PM',
           '15:00:00' => '3:00 PM',
           '16:00:00' => '4:00 PM',
           '17:00:00' => '5:00 PM',
           '18:00:00' => '6:00 PM'));           

    	// Create and configure username element:
		$sch = $store_form->createElement('select', 'store_close', array('label' => 'Close?'));
		$sch->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->addValidator('regex', false, array('/^[0-9\:]+$/i'))
		   	->addValidator('stringLength', false, array(8, 8))
		   	->setRequired(false)
            ->addFilter('StringTrim')
            ->addMultiOptions(array(
           '23:59:00' => 'Never',
           '12:00:00' => '12:00 PM',
           '13:00:00' => '1:00 PM',
           '14:00:00' => '2:00 PM',
           '15:00:00' => '3:00 PM',
           '16:00:00' => '4:00 PM',
           '17:00:00' => '5:00 PM',
           '18:00:00' => '6:00 PM',
           '19:00:00' => '7:00 PM',
           '20:00:00' => '8:00 PM',
           '21:00:00' => '9:00 PM',
           '22:00:00' => '10:00 PM',
           '23:00:00' => '11:00 PM',
           '23:58:00' => '12:00 AM'));           
           
           
    	// Create and configure username element:
		$sp = $store_form->createElement('text', 'store_phone', array('label' => 'Phone Number:', 'id' => 'storePhone', 'maxlength' => 16));
		$sp->removeDecorator('label')
            ->removeDecorator('HtmlTag')
		    ->addValidator('regex', false, array('/^[0-9\.\-]+$/i'))
		    ->addValidator('stringLength', false, array(10, 16))
		    ->setRequired(true)
            ->addFilter('StringTrim')
		    ->setValue(!$this->has_a_store && !$this->_request->isPost() ? $this->contact_phone : "");           
         		   
    	// Create and configure password element:
		$sd = $store_form->createElement('textarea', 'direction', array('label' => 'Special location directions:', 'maxlength' => 64, 'rows' => 2));
		$sd->removeDecorator('label')
           ->removeDecorator('HtmlTag')
           ->addValidator('StringLength', false, array(0, 64))
           ->setRequired(false)
		   ->addFilter('StringTrim');           
           
        $store_submit = $store_form->createElement('submit', 'save_store', array('label' => 'Save Your Store', 'class' => 'button'));
		$store_submit->removeDecorator('DtDdWrapper'); 

		// Add elements to form:
		$store_form->addElement($st)
				   ->addElement($sa)
		 		   ->addElement($sc)
			 	   ->addElement($sz)
			 	   ->addElement($ss)
			 	   ->addElement($sy)
			 	   ->addElement($sl)
			 	   ->addElement($so)
			 	   ->addElement($soh)
			 	   ->addElement($sch)
			 	   ->addElement($sp)
			 	   ->addElement($sd)
			 	   ->addElement($store_submit);

		$this->view->st = $st;
		$this->view->sa = $sa;
		$this->view->sc = $sc;
		$this->view->sz = $sz;
		$this->view->ss = $ss;
		$this->view->sy = $sy;
		$this->view->sl = $sl;
		$this->view->so = $so;
		$this->view->soh = $soh;
		$this->view->sch = $sch;
		$this->view->sp = $sp;
	    $this->view->sd = $sd;
		$this->view->store_submit = $store_submit;

		if ($this->_request->getParam('add') == 1)
		$this->view->save_error = true;
		
    	if ($this->_request->isPost() && $this->_request->getParam('save_store'))
		{
			if ($store_form->isValid($_POST))
			{
				
				$store_address = $store_form->getValue('store_address');
				$store_zip = $store_form->getValue('store_zip');
				$store_country = $store_form->getValue('store_country');
				$store_phone = $this->formatPhone($store_form->getValue('store_phone'));

				$gd = $this->getGoogleData($store_address,$store_zip,$store_country);

				if (is_array($gd))
				{
					$bootstrap = $this->getInvokeArg('bootstrap');
	    			$db = $bootstrap->getResource('db');
	    			
					$data['store_title'] = ucwords(strtolower($store_form->getValue('store_title')));
					$data['store_address'] = $gd['address'];
					$data['store_city'] = $gd['city'];
					$data['store_zip'] = $gd['zip'];
					$data['store_state'] = $gd['state'];
					$data['store_country'] = $gd['country'];
					$data['store_lat'] =  $gd['latitude']*10000000;
					$data['store_lon'] =  $gd['longitude']*10000000;
					$data['store_phone'] = $store_phone;
					$data['store_open'] = $store_form->getValue('store_open');
					$data['store_close'] = $store_form->getValue('store_close');
					$data['direction'] = $store_form->getValue('direction');
					$data['vid'] = $this->vendor;

					$db->beginTransaction();
					
					try {
					$db->insert('stores', $data);
					$id = $db->lastInsertId('stores', 'sid');
					$db->commit();
			    	$this->view->success = 'Your store location was successfully saved to the system.';
					} catch ( Exception $e ) {
					$db->rollback();
					$this->view->save_error = 'You are attempting to create a duplicate store location.';
					}
					$db->closeConnection();
				}
				
				else
				{
					$this->view->save_error = 'We were unable to successfully geocode your store location. Please verify the address, then try again.';
				}
			}
			
			else
			{
				$this->view->save_error = true;
			}
		}
		
    	if ($this->_request->getParam('upload_csv'))
    	{
    		if (!$csv_form->isValid($_POST))
    		{
    			$errors['upload'] = true;
    		}
    		
    		else
    		{
//    			var_dump($csv_form->csv->getHash());exit;

    			$ignore_first = $this->_request->getParam('ignore_first');
    			$params = $this->_request->getParams();
    			//var_dump($params);

    			$x = 0;
    			$y = 0;
    			$started = "";
    			$errors = array();
    			$cparams = $params;
    			

    			foreach ($params as $pkey => $param)
    			{ $x++; $y=0;			
    				foreach ($cparams as $ckey => $compare)
    				{ $y++;
						if ($y > $x)
	    					if (($param > 0) && ($compare > 0))
    							if ($param == $compare)
    							{
    								$errors['dupe'] = true;
    							}
    				}
    			}
    			
    			if (!array_key_exists('dupe',$errors))
    			{

    			$s_name = $this->_request->getParam('s_name') -1;
    			$s_address = $this->_request->getParam('s_address') -1;
    			$s_city = $this->_request->getParam('s_city') -1;
    			$s_state = $this->_request->getParam('s_state') -1;
    			$s_zip = $this->_request->getParam('s_zip') -1;
    			$s_country = $this->_request->getParam('s_country') -1;
    			$s_latitude = $this->_request->getParam('s_latitude') -1;
    			$s_longitude = $this->_request->getParam('s_longitude') -1;
    			$s_phone = $this->_request->getParam('s_phone') -1;
    			$s_open = $this->_request->getParam('s_open') -1;
    			$s_close = $this->_request->getParam('s_close') -1;
    			
    			$adapter = new Zend_File_Transfer_Adapter_Http();
				$adapter->setDestination('/var/www/temp');

    		 	try {
 				// upload received file(s)
 				$adapter->receive();
 				} catch (Zend_File_Transfer_Exception $e) {
 				$e->getMessage();
 				}

 				$mimetype = $adapter->getMimeType(); 
 				if (($mimetype != 'application/octet-stream') && ($mimetype != 'text/plain'))
 				{
					$errors['mime'] = true;
 				}
 				
 				else
 				{
 					$j = 0;
 					
 					$bootstrap = $this->getInvokeArg('bootstrap');
	    			$db = $bootstrap->getResource('db');
 					
 					$fls = file($adapter->getFileName());
 					unlink($adapter->getFileName());
 					
 					for ($i=0;$i<count($fls);$i++)
 					{ $j++;
 						if (eregi("^[ \r\n\t\f]+$",$fls[$i]))
 						continue;
 						
						if (!eregi("[^,]+,[^,]+",$fls[$i]))
						{
							$errors['format'] = true;
							break;
						}

						$fls_clean[$j] = split("[\"\']*,[ \n\"\']*",$fls[$i]);
						if (!isset($fls_clean_line_count))
						{
							$fls_clean_line_count = count($fls_clean[$j]);
						}
						
						else
						{
							$fls_clean_line_count_last = count($fls_clean[$j]);
							if ($fls_clean_line_count != $fls_clean_line_count_last)
							{
								$errors['count'] = true;
								break;	
							}
						}

						$store_phone = $this->formatPhone($fls_clean[$j][$s_phone]);

						if (!$store_phone)
						{
							$errors['phone'] = true;
							break;
						} 
						
						for ($k=0;$k<count($fls_clean[$j]);$k++)
						{
						$fls_clean[$j][$k] = rtrim(ltrim($fls_clean[$j][$k]));
						$fls_clean[$j][$k] = eregi_replace("[\"\']+","",$fls_clean[$j][$k]);
						}

						$store_address = ucfirst(strtolower($fls_clean[$j][$s_address]));

						if (isset($fls_clean[$j][$s_state]))
						$store_state = (strlen($fls_clean[$j][$s_state]) > 2 ? ucfirst(strtolower($fls_clean[$j][$s_state])) : strtoupper($fls_clean[$j][$s_state]));				
						$store_country = (strlen($fls_clean[$j][$s_country]) > 3 ? ucfirst(strtolower($fls_clean[$j][$s_country])) : strtoupper($fls_clean[$j][$s_country]));

						if (isset($fls_clean[$j][$s_open]))
						$store_open = eregi_replace("[^0-9\:APM]","",$fls_clean[$j][$s_open]);
						
						if (isset($fls_clean[$j][$s_close]))
						$store_close = eregi_replace("[^0-9\:APM]","",$fls_clean[$j][$s_close]);
																	
						if (strlen($store_country) > 3)
						{
							$sql = "select code from countries where name=\"$store_country\"";
							$result = $db->fetchAll($sql);
							if (count($result) > 0)
							$store_country = $result[0]['code'];
						}

						if (isset($store_state) && strlen($store_state) > 2)
						{
							$sql = "select code from states where name=\"$store_state\" and country=\"$store_country\"";
							$result = $db->fetchAll($sql);
							if (count($result) > 0)
							$store_state = $result[0]['code'];
						}
						
						$store_zip = strtoupper($fls_clean[$j][$s_zip]);

						$gd = $this->getGoogleData($store_address,$store_zip,$store_country);

						if ($gd == false)
						{						
							$errors['geocode'] = true;
			    			break;
						}
			    		
			    		$location = array();
			    		
			    		$location['vid'] = $this->vendor;
						$location['store_title'] = ucwords(strtolower($fls_clean[$j][$s_name]));
						$location['store_address'] = $gd['address'];
						$location['store_city'] = $gd['city'];
						$location['store_state'] = $gd['state'];
						$location['store_zip'] = $gd['zip'];
						$location['store_country'] = $gd['country'];
						$location['store_lat'] = $gd['latitude']*10000000;
						$location['store_lon'] = $gd['longitude']*10000000;
						$location['store_phone'] = $store_phone;
						
						$data[] = $location;
//						print_r($data);exit;
	 				}

	 				if (count($errors) == 0 && is_array($data))
		 			{
			 			$db->beginTransaction();
							
						try {
						foreach ($data as $location)
						$db->insert('stores', $location);
						$db->commit();
						$this->view->success = 'All of the store locations listed in your file have been saved to the system.';
						} catch ( Exception $e ) {
						$db->rollback(); echo $e; exit;
						$errors['insert'] = true;
						}
					}
				$db->closeConnection();
 				}
    			}    			
    		}
    			
    		if ($errors['upload'])	$this->view->file_error = 'An error occured during upload. Please select your file and attempt the upload again.';
    		if ($errors['dupe']) 	$this->view->file_error = 'Look below and make sure you have not entered any duplicate numbers for data mappings.';
    		if ($errors['mime']) 	$this->view->file_error = 'You are attempting to upload a file of the wrong format. Only *.TXT and *.CSV files are allowed at this time.';
    		if ($errors['format']) 	$this->view->file_error = 'The file you are attempting to upload appears to be in the wrong format. Only files containing comma seperated values are allowed at this time.';
    		if ($errors['count']) 	$this->view->file_error = 'One or more lines in your file does not contain the same number of data pieces as the other lines.';
    		if ($errors['phone']) 	$this->view->file_error = 'One or more lines in your file is either missing a phone number, or the phone number format is invalid.';
    		if ($errors['geocode']) $this->view->file_error = 'We were unable to successfully geocode, and verify the address, of one or more store locations submitted in your file.';
    		if ($errors['insert']) 	$this->view->file_error = 'An error occurred while saving your store locations to the system.';
  		
    	}
		
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select * from `stores` where vid = $this->vendor order by created desc";
    	$stores = $db->fetchAll($sql);

    	$db->closeConnection();

		$this->view->stores = $this->htmlEntityConvert($stores);   

		$this->view->toolPane = $this->view->render('console/stores.phtml');
    
    }
    
    
    
    
    
    
    
    
    
    
    
    
    public function distributionAction()
    {
    	
	    $VALID_REGEX = "/^[".implode("\\",self::$VALID_CHARACTERS)."]+$/i";
    		    	    
	    $bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql =
"
select
o.oid, o.offer_head
from offers `o`
where o.vid = $this->vendor
  and o.disable = 0
  and not exists(select oid from offers2stores where oid = o.oid)
  and not exists(select oid from payments where oid = o.oid)
order by o.created desc
limit 10
";

	    $offers = $db->fetchAll($sql);
	    $this->view->offer_count = count($offers);
	    
    	if ($this->view->offer_count == 0)
    	{
    		$offer_options = array('' => '- you do not have any offers available for distribution -');
    	}
    	
    	else
    	{
    		$offer_options[] = "- select an offer -";
    		foreach ($offers as $offer)
    		$offer_options[$offer['oid']] = $offer['offer_head'];
    	}
    	
	    $sql =
"
select
distinct(s.store_country),
c.code,
c.name
from stores `s`, countries `c`
where s.vid = $this->vendor
  and s.disable = 0
  and s.store_country = c.code
  and c.disable = 0
order by c.code
";
    	
    	$countries = $db->fetchAll($sql);
    	$this->view->country_count = count($countries);

    	if ($this->view->country_count == 1)
    	{
    		$store_country = $countries[0]['code'];
    		$this->view->store_country = $store_country;
    	}
    	
    	else if ($this->view->country_count > 1)
    	{
    		$country_options[''] = "- select a country -";
    		foreach ($countries as $country)
    		$country_options[$country['code']] = $country['name'];
    	}
    	
    	if ($this->_request->getParam('country'))
    	{
    		$store_country = $this->_request->getParam('country');
    	}

    	if ($store_country)
    	{
    	
	    $sql =
"
select
distinct(s.store_state),
p.code,
p.name
from stores `s`, states `p`
where s.vid = $this->vendor
  and s.store_country = \"$store_country\"
  and s.disable = 0
  and s.store_country = p.country
  and p.disable = 0
order by p.code
";

    	$states = $db->fetchAll($sql);
		$this->view->state_count = count($states);
		
		if ($this->view->state_count > 1)
		{
	    	if ($this->_request->getParam('country'))
    		{
    			foreach ($states as $state)
    			$state_options .= "<option value=\"$state[code]\">$state[name]</option>\n";

	    		echo $state_options;
    			exit;
	    	}
	    	foreach ($states as $state)
    		$state_options[$state['code']] = $state['name'];
		}
		
		else
		{
			$this->view->store_state = $states[0]['code'];
		}
		
    	}
    	
	    $sql =
"
select a.aid, a.display, a.cost
from augments `a`
where a.disable = 0
order by a.cost
";

	    $augments = $db->fetchAll($sql);
    	
    	$db->closeConnection();
    	
    	$form = new Zend_Form;
    	
    	// Create and configure offer title element:
		$oid = $form->createElement('select', 'oid', array('label' => 'Here is a list of your available undistributed offers:'));
		$oid->addValidator('alnum', false)
		    ->addValidator('stringLength', false, array(8, 8))
            ->addMultiOptions($offer_options);   

        // Create and configure offer title element:
		$sc = $form->createElement('select', 'store_country', array('label' => 'You have store locations in the following countries. Select a target country for offer distribution:', 'onchange' => 'getStates(country_states,this.options[this.selectedIndex].value);'));
		$sc->addValidator('alnum', false)
		   ->addValidator('stringLength', false, array(2, 4));

		if (is_array($country_options)) $sc->addMultiOptions($country_options);   
           
        // Create and configure offer title element:
		$cs = $form->createElement('select', 'country_states', array('label' => 'You have store locations in the states below. Clicking on a state will add that state, and all stores within that state, to the distribution list at right:', 'size' => 10, 'onchange' => 'addOpt(this.options[this.selectedIndex].text,this.value,store_state);'));
		$cs->addValidator('alnum', false)
		   ->addValidator('stringLength', false, array(2, 4));

        if (is_array($state_options)) $cs->addMultiOptions($state_options);

        // Create and configure offer title element:
		$ss = $form->createElement('multiselect', 'store_state', array('label' => 'To remove a selected state from the distribution list below, click on the name of the state you wish to remove:', 'size' => 10, 'onchange' => 'this.remove(this.selectedIndex);'));
		$ss->addValidator('alnum', false)
		   ->addValidator('stringLength', false, array(2, 4));
           
        // Create and configure augment checkboxes:
		$aug[0] = $form->createElement('checkbox', 'augment_'.$augments[0]['aid'], array('checkedValue' => 1, 'uncheckedValue' => 0, 'id' => 'p1'));
		$aug[0]->removeDecorator('label');
		$aug[1] = $form->createElement('checkbox', 'augment_'.$augments[1]['aid'], array('checkedValue' => 1, 'uncheckedValue' => 0, 'id' => 'p2'));
		$aug[1]->removeDecorator('label');
		$aug[2] = $form->createElement('checkbox', 'augment_'.$augments[2]['aid'], array('checkedValue' => 1, 'uncheckedValue' => 0, 'id' => 'p3'));
		$aug[2]->removeDecorator('label');

		$all = $form->createElement('checkbox', 'all_stores', array('checkedValue' => 1, 'uncheckedValue' => 0));
		$all->removeDecorator('label');

        $button = $form->createElement('submit', 'distribute', array('label' => 'Distribute Your Offer', 'class' => 'button', 'onclick' => 'selAll(store_state);'));
		$button->removeDecorator('Label');
		
		$this->view->augments = $augments;

        $form->addElement($oid)
        	 ->addElement($sc)
        	 ->addElement($cs)
        	 ->addElement($ss)
			 ->addElement($aug[0])
			 ->addElement($aug[1])
        	 ->addElement($aug[2])
        	 ->addElement($all)
        	 ->addElement($button);
        	 
        $this->view->oid = $oid;
        $this->view->sc = $sc;
        $this->view->cs = $cs;
        $this->view->ss = $ss;
        $this->view->a1 = $aug[0];
        $this->view->a2 = $aug[1];
        $this->view->a3 = $aug[2];
        $this->view->all = $all;
        $this->view->button = $button;
    	
        if ($this->_request->isPost('distribute'))
        {
        	if ($this->_request->getPost('oid') < 10000000)
        	{
				$this->view->oid_error = '<ul class="errors" style="margin:0px 0px 5px 0px;"><li>You have not selected an offer to distribute. Please make your selection from the menu above.</li></ul>';
        	}
        	
        	else
        	{
			
        		$oid = $this->_request->getPost('oid');
        		
	    		if ($this->_request->getPost('all_stores') < 1)
	    		{
	    			
	    			$country = $this->_request->getPost('store_country');
					$store_states = $this->_request->getPost('store_state');

	    			if (!is_array($store_states) || count($store_states) < 1)
	    			{
	    				$this->view->message = '<ul class="errors" style="margin:0px 0px 5px 0px;"><li>You have not selected any country or states for offer distribution.</li></ul>';
	    			}
	    		
	    			else
	    			{
	    				foreach ($store_states as $state)
	    				{
	    					$sql = "select sid from stores where vid = $this->vendor and store_state = \"$state\" and store_country = \"$country\" and disable = 0 order by sid";
	    					$state_stores[] = $db->fetchAll($sql);
	    				}			
	    			}
	    		}
	    		
	    		else
	    		{
	    			$sql = "select sid from stores where vid = $this->vendor and disable = 0 order by sid";
	    			$state_stores[] = $db->fetchAll($sql);
	    		}

	    		$params = $this->_request->getPost();
    			foreach ($params as $key => $value)
    			{
    				if ($value > 0)
    				if (eregi("augment_",$key))
    				{
    				$augment = explode("_",$key);
    				$aids[] = $augment[1];
    				}
    			}
        		    		
	    		if (is_array($state_stores))
	    		foreach ($state_stores as $state)
	    		{
					try {
						$db->beginTransaction();
    					foreach ($state as $store) {	
						$db->insert('offers2stores', array('oid' => $oid, 'sid' => $store['sid']));
    					} foreach ($aids as $aid) {
						$db->insert('offers2augments', array('oid' => $oid, 'aid' => $aid));
    					} $db->commit();
					} catch ( Exception $e ) {
						$db->rollback();
						$this->view->message = '<ul class="errors" style="margin:0px 0px 5px 0px;"><li>You are attempting to create a duplicate distribution record.</li></ul>';
					}
    			}
        	}
       	}		

       	$db->closeConnection();
       	
    	$this->view->toolPane = $this->view->render('console/distribution.phtml');

    }
    
    
    
    

    
    
	public function reportsAction()
	{
		
    	$this->view->toolPane = $this->view->render('console/reports.phtml');		
		
	}
    
    
    
    
    
    
    
	
	
	
	
	
    
    
	public function accountAction()
	{
		
    	$this->view->toolPane = $this->view->render('console/account.phtml');		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
    
	public function profileAction()
	{
		
    	$this->view->toolPane = $this->view->render('console/profile.phtml');		
		
	}
	
	
	
	
	
    
    
    
    
    
    public function logoutAction()
    {
    	$authorized = new Zend_Session_Namespace('authorized');
    	unset($authorized->vid);
    	unset($authorized->username);
    	unset($authorized->emailadd);
    	unset($authorized->contact_name);

    	$this->_redirect('/');
    }
    
    
    
    
    
    
    
    
    
    
    
    public function getCountryOptions()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select code,name from countries where disable = 0";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
	    
	    if (!is_array($results))
	    return array();
	    
	    foreach ($results as $result)
	    $options[$result['code']] = $result['name'];
	    
	    return $options;
	    
    }
    
    
    

    
    
    
    
    public function getStateOptions($c="US")
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select code,name from states where country = \"$c\" and disable = 0";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
	    
	    if (!is_array($results))
	    return array();
	    
	    foreach ($results as $result)
	    $options[$result['code']] = $result['name'];
	    
	    return $options;
	    
    }
    
    
    
    
    
    
    
    
    
    
    
    public function doesStoreExist()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select sid from stores where vid = $this->vendor limit 1";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
    	
	    if (is_array($results) && count($results) > 0)
	    return true;
	    
	    return false;
	    
    }
    
    
    
    
    
    
    
    
    
    
        
    
    
    public function doesOfferExist()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select oid from offers where vid = $this->vendor limit 1";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
    	
	    if (is_array($results) && count($results) > 0)
	    return true;
	    
	    return false;
	    
    }
    

    
    
    
    
    
    
    
    
    
    
    
        
    public function hasMultipleStoreCountries()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select distinct(`store_country`) from stores where vid = $this->vendor limit 2";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
    	
	    if (is_array($results) && count($results) > 1)
	    return true;
	    
	    return false;
	    
    }
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function hasMultipleStoreStates()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select distinct(`store_state`) from stores where vid = $this->vendor limit 2";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
    	
	    if (is_array($results) && count($results) > 1)
	    return true;
	    
	    return false;
	    
    }
    
        
    
    
    
    
    
    
    
    
    public function htmlEntityConvert($a)
    {
    	
  		foreach ($a as $key => $b)
   			foreach ($b as $col => $val)
   				$c[$key][$col] = htmlentities($val);
   		
   		return $c;
    	
    }
    
    
    
    
    
    
    
        
    public function hasMultipleStoreTitles()
    {
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
	    $db = $bootstrap->getResource('db');
	    
	    $sql = "select distinct(`store_title`) from stores where vid = $this->vendor limit 2";
	    $results = $db->fetchAll($sql);

	    $db->closeConnection();
    	
	    if (is_array($results) && count($results) > 1)
	    return true;
	    
	    return false;
	    
    }
    
    
    
    
    
    
    
    
    
    
    
    
    public function formatPhone($p="phone")
    {

   		$p = eregi_replace("[^0-9]","",$p);
   		if (strlen($p) < 10) return false;
   		if (substr($p,0,1) == "1") $p = "1-".substr($p,1,3)."-".substr($p,4,3)."-".substr($p,7,4);
   		else $p = "1-".substr($p,0,3)."-".substr($p,3,3)."-".substr($p,6,4);
   		return $p;
    	
    }
    
    
    
    
    
    
    
    
    
    
    public function getGoogleData($sa="address",$sz="zip",$sc="country")
    {
    	
    	$google_query = urlencode("$sa, $sz, $sc");
						 
    	if (!($json = file("http://maps.google.com/maps/geo?q=".
    	$google_query."&output=json&oe=utf8&sensor=false&key=ABQIAAAAV1NcPgE5cDOstmgzzUBsQxTKUo3oYoUno1SfFUXTE6giomHa7BQnPSs2jxW_CIucs9yw-gvEr7ya-A")))
    	{
			return false;
    	}

		if (!($google_data = json_decode(implode("",$json), true)))
   		{
   			return false;
   		}

   		if (!is_array($google_data))
		{
			return false;
		}
			    		
		if ($google_data['Status']['code'] != 200)
		{
			return false;
   		}
//print_r($google_data);exit;
   		$gd['country'] = $google_data['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];
   		$gd['state'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];

   		if ($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality'])
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
   		else if ($google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality'])
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
		}
		else
		{
			$gd['city'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
			$gd['address'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Thoroughfare']['ThoroughfareName'];
			$gd['zip'] = $google_data['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['PostalCode']['PostalCodeNumber'];
		}
						
		$gd['longitude'] = $google_data['Placemark'][0]['Point']['coordinates'][0];
		$gd['latitude'] = $google_data['Placemark'][0]['Point']['coordinates'][1];

		if (!is_array($gd) || count($gd) < 7)
		{
   			return false;
		}
		
		return $gd;
    	
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}

?>