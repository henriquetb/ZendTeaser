<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Teaser();
        $message = array(
            'confirm'   => 1,
            'message'   => "",
        );
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $teaser = new Application_Model_Teaser($form->getValues());
                $mapper  = new Application_Model_TeaserMapper();
                $teaserRegistered = new Application_Model_Teaser();
                $teaserRegistered = $mapper->find($teaser->getEmail(), $teaser);
                
                if ( !isset($teaserRegistered) ){
                    // if didn't find the email in the table, inserts
                    $validation = md5(rand(0, 100000000000));
                    $teaser->setValidation($validation);
                    $mapper->save($teaser);
                    //$msg = "Um e-mail foi enviado para confirmar o registro.";
                    $message = array(
                        'confirm'   => 1,
                        'message'   => "Verifique a sua caixa de entrada e siga as instruções para validar o cadastro.",
                    );
                    $this->sendTeaserEmail($teaser->getEmail(), $teaser->getValidation());
                    
                } else if ($teaserRegistered->getActive() == 0){
                    //if found the e-mail, but is not active, resends the e-mail
                    $message = array(
                        'confirm'   => 1,
                        'message'   => "Verifique a sua caixa de entrada e siga as instruções para validar o cadastro.",
                    );
                    $this->sendTeaserEmail($teaser->getEmail(), $teaser->getValidation());
                    
                }else{
                    //if found the e-mail and is already active, just shows a message.
                    $message = array(
                        'confirm'   => 0,
                        'message'   => "E-mail já cadastrado.",
                    );
                }
                
                
                /*if (!$mapper->exists($teaser->getEmail())){
                    $validation = md5(rand(0, 100000000000));
                    $teaser->setValidation($validation);
                    $mapper->save($teaser);
                    //$msg = "Um e-mail foi enviado para confirmar o registro.";
                    $message = array(
                        'confirm'   => 1,
                        'message'   => "Verifique a sua caixa de entrada e siga as instruções para validar o cadastro.",
                    );
                    $this->sendTeaserEmail($teaser->getEmail(), $teaser->getValidation());
                }else{
                    //$msg = "E-mail já cadastrado";
                    $message = array(
                        'confirm'   => 0,
                        'message'   => "E-mail já cadastrado.",
                    );
                }*/
                //return $this->_helper->redirector('index');
            }else{
                //$msg = "E-mail inserido é inválido";
                $message = array(
                    'confirm'   => 0,
                    'message'   => "E-mail inserido é inválido.",
                );
            }
        }
        $this->view->form = $form;
        $this->view->confirmation = $message;
        
        //$teaser = new Application_Model_TeaserMapper();
        //$this->view->entries = $teaser->fetchAll();
       
    }

    public function addAction()
    {
        
    }

    public function sendTeaserEmail($email, $confirmation_code)
    {
        $mail = new Zend_Mail("utf-8");
        $html = "";
        $html = $html . "<html><head><meta http-equiv='content-type' content='text/html; charset=UTF-8' />";
        $html = $html . "<style type='text/css'>body{font-family: Verdana, sans-serif; font-weight: 400; text-align: justify; width: 655px;}</style>";
        $html = $html . "</head><body>";
        $html = $html . "<img src='http://www.meucampeonato.com.br/images/mailtopo.png'><br>";
        $html = $html . "<h1>O campeonato é você quem faz</h1><br><br>";
        $html = $html . "Bem vindo ao MeuCampeonato. A partir de agora, quando o assunto for a pelada do fim de semana, o campeonato da sua empresa, faculdade ou grupo de amigos, você tem a melhor ferramenta para fazer de modo organizado e completo. <br><br>";
        $html = $html . "Você acabou de se inscrever para receber as novidades sobre o andamento das obras para tornar este projeto possível.<br><br>";
        $html = $html . "Para confirmar o seu e-mail, por favor, clique no link abaixo (ou copie e cole no seu navegador).<br><br>";
        $html = $html . "<a href='http://www.meucampeonato.com.br/ZendMC/public/index/confirm?email=".$email."&confirmation_code=".$confirmation_code."'>http://www.meucampeonato.com.br/ZendMC/public/index/confirm?email=".$email."&confirmation_code=".$confirmation_code."</a><br><br>";
        //$html = $html . "<a href='http://localhost/ZendMC/public/index.php/index/confirm?email=".$email."&confirmation_code=".$confirmation_code."'>http://localhost/ZendMC/public/index.php/index/confirm?email=".$email."&confirmation_code=".$confirmation_code."</a><br><br>";
        $html = $html . "Chame seus amigos e faça um campeonato completo e bem organizado. Procure pessoas para jogar uma pelada. Explore ao máximo o MeuCampeonato.<br><br>";
        $html = $html . "Bom divertimento,<br>";
        $html = $html . "Equipe MeuCampeonato<br>";
        $html = $html . "contato@meucampeonato.com.br<br><br>";
        $html = $html . "<img src='http://www.meucampeonato.com.br/images/mailrodape.png'></body></html>";
        
        
        $mail->addTo($email)
            ->setFrom("contato@meucampeonato.com.br")
            ->setSubject("Bem vindo ao MeuCampeonato.com.br")
            ->setBodyHtml($html);
        $resul = $mail->send();
        return;
    }

    public function confirmAction()
    {
        // action body
        if ($this->getRequest()->isGet()){
            $email = $this->getRequest()->getParam('email');
            $validation = $this->getRequest()->getParam('confirmation_code');
            
            $teaser = new Application_Model_Teaser();
            $mapper  = new Application_Model_TeaserMapper();
            $teaser = $mapper->find($email, $teaser);
            if ($email === $teaser->getEmail() && $validation === $teaser->getValidation()){
                if ($teaser->getActive() == 0){
                    $teaser->setActive(1);
                    $mapper->update($teaser);
                    //$this->view->campos = $teaser;
                    $this->view->message = "Parabéns, você acabou de confirmar seu e-mail. Durante o desenvolvimento do MeuCampeonato você receberá informações sobre o andamento e novidades.";
                } else {
                    $this->view->message = "Você já confirmou seu cadatro. Durante o desenvolvimento do MeuCampeonato você receberá informações sobre o andamento e novidades.";
                }
            } else {
                $this->view->message = "Código de verificação inválido.<br> Tente se cadastrar novamente para receber informações sobre o andamento e novidades do MeuCampeonato.";
            }
        }
    }


}









