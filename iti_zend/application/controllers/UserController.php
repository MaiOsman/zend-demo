<?php

class UserController extends Zend_Controller_Action
{
    public $fpS;
    public function init()
    {

          $authorization = Zend_Auth::getInstance();
          $this->fpS = new Zend_Session_Namespace('facebook');

          $request=$this->getRequest();
          $actionName=$request->getActionName();

          if ((!$authorization->hasIdentity() && !isset($this->fpS->fname)) && ($actionName != 'login' && $actionName != 'fblogin' && $actionName !='fbcallback'))
          {
          $this->redirect('/user/login');
          }


          if (($authorization->hasIdentity() || isset($this->fpS->fname)) && ($actionName == 'login' || $actionName == 'fblogin'))
          {
          $this->redirect('/user/list');
          }

    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
        $user_model = new Application_Model_User();
        $this->view->users = $user_model->listUsers();

        $track_form = new Application_Form_TrackForm();
        $this->view->track_form = $track_form;
        $track_model = new Application_Model_Track();
        $request = $this->getRequest();
        if($request->isPost())
        {
            if($track_form->isValid($request->getPost())){
            $track_model-> addNewTrack($request->getParams());
            $this->redirect('/user/list');
            }
        }
    }

    public function deleteAction()
    {
        $user_model = new Application_Model_User();
        $user_id = $this->_request->getParam('uid');
        $user_model->deleteUser($user_id);
        $this->redirect('/User/list');
    }

    public function detailsAction()
    {
         $user_model = new Application_Model_User();
        $user_id = $this->_request->getParam('uid');
        $this->view->user = $user_model->userDetails($user_id);
        //$this->view->user = $user[0];
    }

    public function addAction()
    {
        // action body
        $form = new Application_Form_UserForm();
        $this->view->user_form = $form ;
        $request = $this->getRequest();
        if($request->isPost()){
            if($form->isValid($request->getPost())){
                $userdata['fname']=$form->getValue('fname');
                $userdata['lname']=$form->getValue('lname');
                $userdata['gender']=$form->getValue('gender');
                $userdata['email']=$form->getValue('email');
                $userdata['track']=$form->getValue('track');
                $user_model = new Application_Model_User();
                $user_model->addNewUser($userdata);
                $this->redirect('/user/list');
            }
        }

    }

    public function editAction()
    {
        $form = new Application_Form_UserForm();
        $user_model = new Application_Model_User();
        $user_id = $this->_request->getParam('uid');
        $user_data = $user_model->userDetails($user_id);
        $form->populate($user_data);
        $this->view->user_form = $form ;
        $request = $this->getRequest();
        if($request->isPost()){
            if($form->isValid($request->getPost())){
                $user_model->updateUser($user_id,$_POST);
                $this->redirect('/user/list');
            }
        }
    }

    public function loginAction()
    {
        // get login form and check for validation
        $login_form = new Application_Form_LoginForm( );
        $request = $this->getRequest();
        if($request->isPost()){
          if ($login_form->isValid($request->getPost( ))){
              $email=$request->getParam('email');
              $password=$request->getParam('passwd');
              $db= Zend_Db_table::getDefaultAdapter();
              $adapter=new Zend_Auth_Adapter_DbTable($db,'users','email','passwd');
              $adapter->setIdentity($email);
              $adapter->setCredential($password);

              $result = $adapter->authenticate();

            if($result->isValid()){
              echo "data is valid";
            }
            else {
              echo "data is not valid";
            }


        if($result->isValid()){

          $sessionDataObj= $adapter->getResultRowObject();
          $auth=Zend_Auth::getInstance();

          $storage = $auth->getStorage();

          $storage->write($sessionDataObj);
          return $this->redirect('/user/list');
        }
        else {
          echo "not work";

        }

    }

  }
  $this->view->login_form=$login_form;
  // facebook
      $fb = new Facebook\Facebook([
      'app_id' => '741290646030979',
      'app_secret' => 'be3472835afbb8092839234dd309f1af',
      'default_graph_version' => 'v2.2',
      ]);
      $helper = $fb->getRedirectLoginHelper();

      $loginUrl = $helper->getLoginUrl($this->view->serverUrl() .'/user/fbcallback');
      $this->view->facebookUrl = $loginUrl;
    }

    public function logoutAction()
    {
        $auth=Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::namespaceUnset('facebook');
        $this->redirect('/user/login');
    }


    public function fbcallbackAction()
    {
          $fb = new Facebook\Facebook([
          'app_id' => '741290646030979', // Replace {app-id} with your app id
          'app_secret' => 'be3472835afbb8092839234dd309f1af',
          'default_graph_version' => 'v2.2',
          ]);
          $helper = $fb->getRedirectLoginHelper();
          try {
          $accessToken = $helper->getAccessToken();
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
          }
          if (! isset($accessToken)) {
          if ($helper->getError()) {
          header('HTTP/1.0 401 Unauthorized');
          echo "Error: " . $helper->getError() . "\n";
          echo "Error Code: " . $helper->getErrorCode() . "\n";
          echo "Error Reason: " . $helper->getErrorReason() . "\n";
          echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
          header('HTTP/1.0 400 Bad Request');
          echo 'Bad request';
          }
          exit;
          }
          // The OAuth 2.0 client handler helps us manage access tokens
          $oAuth2Client = $fb->getOAuth2Client();
          if (! $accessToken->isLongLived()) {
          // Exchanges a short-lived access token for a long-lived one
          try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
          } catch (Facebook\Exceptions\FacebookSDKException $e) {
          echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
          exit;
          }
          echo '<h3>Long-lived</h3>';
          }
          $fb->setDefaultAccessToken($accessToken);
          try {
          $response = $fb->get('/me');
          $userNode = $response->getGraphUser();
          }
          catch (Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          Exit;
          }
          catch (Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          Exit;
          }
          $this->fpS->fname = $userNode['name'];
    }


}
