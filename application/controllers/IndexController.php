<?php

class IndexController extends Zend_Controller_Action
{

	private static $SIGNIN_CHARACTERS = array("A-Z0-9",",",".","@","#","^","'","`","~","&","_","+","-");
	private static $SIGNUP_CHARACTERS = array("A-Z0-9",",",".","@","#","^","'","`","~","&","_","+","-","s","/");
	
	
    public function init()
    {
    	$VALID_REGEX = "/^[".implode("\\",self::$SIGNIN_CHARACTERS)."]+$/i";

    	$signin_form = new Zend_Form;

    	// Create and configure username element:
		$username = $signin_form->createElement('text', 'username', array('label' => 'Username:','maxlength' => 48));
		$username->removeDecorator('label')
           		 ->removeDecorator('HtmlTag')
           		 ->addValidator('regex', false, array($VALID_REGEX))
				 ->addValidator('stringLength', false, array(8, 48))
				 ->setRequired(true)
				 ->addFilter('StringToLower')
				 ->addFilter('StringTrim');

    	// Create and configure password element:
		$password = $signin_form->createElement('password', 'password', array('label' => 'Password:','maxlength' => 32));
		$password->removeDecorator('label')
           		 ->removeDecorator('HtmlTag')
           		 ->addValidator('StringLength', false, array(8, 32))
        		 ->setRequired(true)
        		 ->addFilter('StringTrim');
        		 
        $button = $signin_form->createElement('submit', 'signin', array('label' => 'Sign-in'));
        $button->removeDecorator('DtDdWrapper');

        $this->signin_form = $signin_form;
        
		// Add elements to form:
		$this->signin_form->addElement($username)
					->addElement($password)
	    // use addElement() as a factory to create 'Login' button:
		   			->addElement($button);
    		 
    	$this->view->signin_username = $username;
    	$this->view->signin_password = $password;
    	

    	
    	
    	
        $VALID_REGEX = "/^[".implode("\\",self::$SIGNUP_CHARACTERS)."]+$/i";
    	$PHONE_REGEX = "/^[0-9\x\.\-]+$/i";

    	$signup_form = new Zend_Form;

    	// Create and configure username element:
		$emailadd = $signup_form->createElement('text', 'emailadd', array('label' => 'eMail Address:', 'maxlength' => 48));
		$emailadd->removeDecorator('label')
           		 ->removeDecorator('HtmlTag')
           		 ->addValidator('regex', false, array($VALID_REGEX))
				 ->addValidator('emailAddress', false)
				 ->addValidator('stringLength', false, array(8, 48))
//    ->addValidator('StringLength', array(5,12, 'message' => array(
//	Zend_Validate_StringLength::TOO_SHORT => '%s must be at least %min% characters.',
//	Zend_Validate_StringLength::TOO_LONG => '%s must be shorter than %max% characters.'
//    )));
				 ->setRequired(true)
				 ->addFilter('StringToLower')
         		 ->addFilter('StringTrim');

    	// Create and configure password element:
		$password = $signup_form->createElement('password', 'password', array('label' => 'Password:', 'maxlength' => 32));
		$password->removeDecorator('label')
           		 ->removeDecorator('HtmlTag')
           		 ->addValidator('StringLength', false, array(8, 32))
        		 ->setRequired(true)
				 ->addFilter('StringTrim');

    	// Create and configure confirmation element:
		$confirm = $signup_form->createElement('password', 'confirm', array('label' => 'Confirm:', 'maxlength' => 32));
		$confirm->removeDecorator('label')
           		->removeDecorator('HtmlTag')
           		->addValidator('StringLength', false, array(8, 32))
        		->setRequired(true)
				->addFilter('StringTrim');

        $register = $signup_form->createElement('submit', 'signup', array('label' => 'Quick Sign-up'));
		$register->removeDecorator('DtDdWrapper');

    	// Create and configure username element:
		$vendor_name = $signup_form->createElement('text', 'vendor_name', array('label' => 'Your Business Name:', 'maxlength' => 32));
		$vendor_name->removeDecorator('label')
           		    ->removeDecorator('HtmlTag')
                    ->addValidator('regex', false, array($VALID_REGEX))
				 	->addValidator('stringLength', false, array(4, 32))
				 	->setRequired(false)
         		 	->addFilter('StringTrim');

    	// Create and configure username element:
		$contact_name = $signup_form->createElement('text', 'contact_name', array('label' => 'Who could we talk to?', 'maxlength' => 32));
		$contact_name->removeDecorator('label')
           		     ->removeDecorator('HtmlTag')
           		     ->addValidator('regex', false, array($VALID_REGEX))
				 	 ->addValidator('stringLength', false, array(4, 32))
				 	 ->setRequired(false)
         		 	 ->addFilter('StringTrim');

    	// Create and configure username element:
		$contact_phone = $signup_form->createElement('text', 'contact_phone', array('label' => 'At what phone number?', 'id' => 'storePhone', 'maxlength' => 16));
		$contact_phone->removeDecorator('label')
           		 	  ->removeDecorator('HtmlTag')
           		 	  ->addValidator('regex', false, array($PHONE_REGEX))
				 	  ->addValidator('stringLength', false, array(10, 16))
				 	  ->setRequired(false)
         		 	  ->addFilter('StringTrim');

        // Create and configure username element:
		$primary_address = $signup_form->createElement('text', 'primary_address', array('label' => 'Primary Business Address:', 'maxlength' => 48));
		$primary_address->removeDecorator('label')
           			    ->removeDecorator('HtmlTag')
           			    ->addValidator('regex', false, array($VALID_REGEX))
				 		->addValidator('stringLength', false, array(6, 48))
				 		->setRequired(false)
         		 		->addFilter('StringTrim');

        // Create and configure username element:
		$primary_city = $signup_form->createElement('text', 'primary_city', array('label' => 'Business City:', 'id' => 'storeCity', 'maxlength' => 48));
		$primary_city->removeDecorator('label')
           		 	 ->removeDecorator('HtmlTag')
           		 	 ->addValidator('regex', false, array($VALID_REGEX))
				 	 ->addValidator('stringLength', false, array(2, 32))
				 	 ->setRequired(false)
         		 	 ->addFilter('StringTrim');

        // Create and configure username element:
		$primary_state = $signup_form->createElement('select', 'primary_state', array('id' => 'storeState', 'maxlength' => 4));
		$primary_state->removeDecorator('label')
           			  ->removeDecorator('HtmlTag')
           			  ->addValidator('regex', false, array($VALID_REGEX))
				 	  ->addValidator('stringLength', false, array(2, 4))
				 	  ->setRequired(false)
         		 	  ->addFilter('StringTrim')
         		 	  ->addMultiOptions($this->getStateOptions('US'));

        // Create and configure username element:
		$primary_zip = $signup_form->createElement('text', 'primary_zip', array('label' => 'Business Zip/Postal:', 'id' => 'storeState', 'maxlength' => 8));
		$primary_zip->removeDecorator('label')
           		    ->removeDecorator('HtmlTag')
           		    ->addValidator('regex', false, array($VALID_REGEX))
				 	->addValidator('stringLength', false, array(4, 8))
				 	->setRequired(false)
         		 	->addFilter('StringTrim');
         		 	 
        // Create and configure username element:
		$primary_country = $signup_form->createElement('select', 'primary_country', array('id' => 'storeCountry', 'maxlength' => 4));
		$primary_country->removeDecorator('label')
           		 	    ->removeDecorator('HtmlTag')
           		 	    ->addValidator('regex', false, array($VALID_REGEX))
				 	    ->addValidator('stringLength', false, array(2, 4))
				 	    ->setRequired(false)
         		 	    ->addFilter('StringTrim')
         		 	    ->addMultiOptions($this->getCountryOptions());
		
        $profile = $signup_form->createElement('submit', 'profile', array('label' => 'Register and Profile'));
		$profile->removeDecorator('DtDdWrapper');

		$this->signup_form = $signup_form;
		
		// Add elements to form:
		$this->signup_form->addElement($emailadd)
			 		->addElement($password)
			 		->addElement($confirm)
			 		->addElement($vendor_name)
			 		->addElement($contact_name)
			 		->addElement($contact_phone)
			 		->addElement($primary_address)
			 		->addElement($primary_city)
			 		->addElement($primary_zip)
			 		->addElement($primary_state)
			 		->addElement($primary_country);

    	$this->view->emailadd = $emailadd;
    	$this->view->signup_password = $password;
    	$this->view->confirm = $confirm;
    	$this->view->vendor_name = $vendor_name;
    	$this->view->contact_name = $contact_name;
    	$this->view->contact_phone = $contact_phone;
    	$this->view->primary_address = $primary_address;
    	$this->view->primary_city = $primary_city;
    	$this->view->primary_zip = $primary_zip;
    	$this->view->primary_state = $primary_state;
    	$this->view->primary_country = $primary_country;
    	
    }
    
    
    

    
    
    
    
    
    
    public function indexAction()
    {
    	
    	$this->signinAction();
    	$this->signupAction();
    	
    }
    
    
    
    
    
    
    
    public function signinAction()
    {
    	if ($this->_request->isPost() && $this->_request->getParam('signin'))
		{
			if ($this->signin_form->isValid($_POST))
			{
				$bootstrap = $this->getInvokeArg('bootstrap');
	    		$db = $bootstrap->getResource('db');

				$username = $this->signin_form->getValue('username');
				$password = md5($this->signin_form->getValue('password'));
    			
    	    	$sql =
    	    	"
    	    	select
    	    	vid,
    	    	emailadd,
    	    	vendor_name,
    	    	contact_name,
    	    	contact_phone,
    	    	primary_address,
    	    	primary_country,
    	    	primary_zip
    	    	from `vendors`
    	    	where username = '".$username."'
    	    	  and password = '".$password."'
    	    	";

    	    	$result = $db->fetchAll($sql);

    	    	$db->closeConnection();

    	    	if (!is_array($result) || count($result) < 1)
    	    	{
					$this->view->signin_error = 'You have entered the wrong Username and/or Password.';
    	    	}
					
				else
				{
					$authorized = new Zend_Session_Namespace('authorized');

					$authorized->vid = $result[0]['vid'];
					$authorized->username = $username;
					$authorized->emailadd = $result[0]['emailadd'];
					$authorized->vendor_name = $result[0]['vendor_name'];
					$authorized->contact_name = $result[0]['contact_name'];
					$authorized->contact_phone = $result[0]['contact_phone'];
					$authorized->primary_address = $result[0]['primary_address'];
					$authorized->primary_country = $result[0]['primary_country'];
					$authorized->primary_zip = $result[0]['primary_zip'];

					$this->_redirect('../console/');
					//exit;
				}
			}
			
			else
			{
				$this->view->signin_error = true;
			}
		}
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function signupAction()
    {
      	if ($this->_request->isPost() && $this->_request->getParam('signup'))
		{
			if ($this->signup_form->isValid($_POST))
			{
				$data['emailadd'] = $this->signup_form->getValue('emailadd');
			    $data['username'] = $data['emailadd'];
				$data['password'] = md5($this->signup_form->getValue('password'));
				
				if ($data['password'] != md5($this->signup_form->getValue('confirm')))
				{
					$this->view->signup_error = 'The Password and Confirmation do not match.';
				}

				else
				{
					$bootstrap = $this->getInvokeArg('bootstrap');
	    			$db = $bootstrap->getResource('db');

					$data['vendor_name'] = ucwords(strtolower(mysqli_escape_string($this->signup_form->getValue('vendor_name'))));
					$data['contact_name'] = ucwords(strtolower(mysqli_escape_string($this->signup_form->getValue('contact_name'))));
					$data['contact_phone'] = $this->formatPhone(mysqli_escape_string($this->signup_form->getValue('contact_phone')));
					$data['primary_country'] = $this->signup_form->getValue('primary_country');
					$data['primary_address'] = ucwords(mysqli_escape_string(strtolower($this->signup_form->getValue('primary_address'))));
					$data['primary_state'] = $this->signup_form->getValue('primary_state');
					$data['primary_city'] = ucwords(strtolower(mysqli_escape_string($this->signup_form->getValue('primary_city'))));
					$data['primary_zip'] = strtoupper(mysqli_escape_string($this->signup_form->getValue('primary_zip')));
					
					try {
						$db->beginTransaction();
						$db->insert('vendors', $data);
						$id = $db->lastInsertId('vendors', 'vid');
						$db->commit();
						$this->SendLoginInfo();
						$this->view->success = 'Your information has been saved, and your membership created.';
					} catch ( Exception $e ) {
						$db->rollback();
						$this->view->signup_error = 'You are attempting to create a duplicate membership.';
					}
					$db->closeConnection();
				}
			}
			
			else
			{
				$this->view->signup_error = true;
			}
		}
    }
    
    
    
    
    
    
    
    
    
    private function getRealIpAddr()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	    {
    		$ip=$_SERVER['HTTP_CLIENT_IP'];
    	}
    	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    	{
      		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    	else
    	{
      		$ip=$_SERVER['REMOTE_ADDR'];
    	}
    	return $ip;
	}
	
	
	
	
	
	
	
	
	

    
    private function SendLoginInfo()
	{
		$carrier = new Zend_Mail();

		$contact = $this->_request->getPost('contact_name');
		$emailadd = $this->_request->getPost('emailadd');
		$password = $this->_request->getPost('password');
		
		$body =
"
Hello $contact

Here is the 'Username' and 'Password' for your retail offer distribution membership area. Once you log into your membership, you will be able to change your Username and Password.

Username: $emailadd 
Password: $password

To log into your membership area, follow the link below and enter your login information in the form provided. If you have any trouble logging into your membership, please send a message to cs@maratgleyzer.com. Thank you!

http://www.armadealo.com/index.php

";

		$carrier->setBodyText($body);
		$carrier->setFrom('cs@maratgleyzer.com', 'Customer Service');
		$carrier->addTo($emailadd, ($contact ? $contact : 'Member'));
		$carrier->setSubject('Your Member Login!');
		
		$carrier->send();
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
	
	
	
	
	
	
    
    
    
    
    
        
    public function formatPhone($p="phone")
    {

   		$p = eregi_replace("[^0-9]","",$p);
   		if (strlen($p) < 10) return false;
   		if (substr($p,0,1) == "1") $p = "1-".substr($p,1,3)."-".substr($p,4,3)."-".substr($p,7,4);
   		else $p = "1-".substr($p,0,3)."-".substr($p,3,3)."-".substr($p,6,4);
   		return $p;
    	
    }
    
    
	
    
    
    
    
    

}