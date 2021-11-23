<?php
namespace PDFReport\Controllers;

require PLUGINS_PATH.'PDFReport/vendor/autoload.php';
require PLUGINS_PATH.'PDFReport/vendor/dompdf/dompdf/src/FontMetrics.php';
use DateTime;
use \MapasCulturais\App;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDFReport\Entities\Pdf as EntitiesPdf;
use Mpdf\Mpdf;

const NO_SELECTION = 0;
const LIST_SUBSCRIBED = 1;
const LIST_PRELIMINARY = 2;
const LIST_DEFINITIVE = 3;
const LIST_CONTACTS = 4;

class Pdf extends \MapasCulturais\Controller{

    function GET_gerarPdf() {
        $app = App::i();

        $array = [
            'regs' => '',
            'title' => '',
            'template' => '',
            'claimDisabled' => null,
            'pluginConf' => ['tempDir' => dirname(__DIR__) . '/vendor/mpdf/mpdf/tmp','mode' => 'utf-8',
            'format' => 'A4']
        ];
        if($this->getData['selectRel'] == NO_SELECTION) $this->handleRedirect('Ops! Selecione uma opção', 401);
        else if($this->getData['selectRel'] == LIST_SUBSCRIBED) $array = $this->listSubscribedHandle($app, $array);
        else if($this->getData['selectRel'] == LIST_PRELIMINARY) $array = $this->listPreliminaryHandle($app, $array);
        else if($this->getData['selectRel'] == LIST_DEFINITIVE) $array = $this->listDefinitiveHandle($app, $array);
        else if($this->getData['selectRel'] == LIST_CONTACTS) $array = $this->listContactsHandle($app, $array);
        else $app->redirect($app->createUrl('oportunidade/'.$this->getData['idopportunityReport']), 401);

        $mpdf = new Mpdf($array['pluginConf']);
        ob_start();
        
        $app->view->jsObject['subscribers'] = $array['regs']['regs'];
        $app->view->jsObject['opp'] = $array['regs']['opp'];
        $app->view->jsObject['claimDisabled'] = $array['claimDisabled'];
        $app->view->jsObject['title'] = $array['title'];

        $content = $app->view->fetch($array['template']);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetTitle('Mapa da Saúde - Relatório');
        $stylesheet = file_get_contents(PLUGINS_PATH.'PDFReport/assets/css/stylePdfReport.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($content,2);
        $mpdf->Output();
        exit;
    }
    
    function listSubscribedHandle($app, $array){
        $array['regs'] = $this->oportunityRegistrationAproved($this->getData['idopportunityReport'], 'ALL');
        if(empty($array['regs']['regs'])){
            $this->handleRedirect('Ops! Não tem inscrito nessa oportunidade.', 401);
        }
        $array['title'] = 'Relatório de inscritos na oportunidade';
        $array['template'] = 'pdf/subscribers';
        return $array;
    }

    function listPreliminaryHandle($app, $array){

        $array['regs'] = $this->oportunityAllRegistration($this->getData['idopportunityReport']);
        if(empty($array['regs']['regs'])){
            $this->handleRedirect('Ops! A oportunidade deve estar publicada.', 401);
        }

        $verifyResource = $this->verifyResource($this->getData['idopportunityReport']);

        if(isset($verifyResource[0])){
            $array['claimDisabled'] = $verifyResource[0]->value;
        }
        $array['title'] = 'Resultado Preliminar do Certame';
        $array['template'] = 'pdf/preliminary';
        $array['pluginConf'] = ['tempDir' => dirname(__DIR__) . '/vendor/mpdf/mpdf/tmp','mode' => 'utf-8',
        'format' => 'A4', 'orientation' => 'L'];
        return $array;
    }

    function listDefinitiveHandle($app, $array, $period = false){
        $id = $this->getData['idopportunityReport'];

        $dqlOpMeta = "SELECT op FROM 
            MapasCulturais\Entities\OpportunityMeta op
            WHERE op.owner = {$id}";

        $resultOpMeta = $app->em->createQuery($dqlOpMeta)->getResult();

        $dateInit = $dateEnd = $hourInit = $hourEnd = "";

        foreach ($resultOpMeta as $key => $valueOpMeta) {
            if($valueOpMeta->key == 'date-initial'){
                $dateInit = $valueOpMeta->value;
            }
            if($valueOpMeta->key == 'hour-initial'){
                $hourInit = $valueOpMeta->value;
            }
            if($valueOpMeta->key == 'date-final'){
                $dateEnd = $valueOpMeta->value;
            }
            if($valueOpMeta->key == 'hour-final'){
                $hourEnd = $valueOpMeta->value;
            }
        }
        $dateHourNow = new DateTime;
        
        $dateAndHourInit = $dateInit.' '.$hourInit;

        $dateVerifyPeriod = DateTime::createFromFormat('d/m/Y H:i:s', $dateAndHourInit);

        if($dateHourNow > $dateVerifyPeriod){
            $period = true;
        }

        if($period) {
            $array['regs'] = $this->oportunityAllRegistration($this->getData['idopportunityReport'], 10);
            if(empty($array['regs']['regs'])){
                $this->handleRedirect('Ops! Para gerar o relatório definitivo a oportunidade deve estar publicada.', 401);
            }
            
            //SELECT AOS RECURSOS
            $dql = "SELECT r
            FROM 
            Saude\Entities\Resources r
            WHERE r.opportunityId = {$id}";
            $resource = $app->em->createQuery($dql)->getResult();
            $countPublish = 0;//INICIANDO VARIAVEL COM 0
            foreach ($resource as $key => $value) {
                if($value->replyPublish == 1 && $value->opportunityId->publishedRegistrations == 1) {
                    $countPublish++;//SE ENTRAR INCREMENTA A VARIAVEL
                }
            }
            if($countPublish == count($resource) && $countPublish > 0 && count($resource) > 0) {
                $array['regs'] = $this->oportunityAllRegistration($this->getData['idopportunityReport'], 10);
                $array['title'] = 'Resultado Definitivo do Certame';
                $array['template'] = 'pdf/definitive';
               
            }else if($countPublish == count($resource) && $countPublish == 0 && count($resource) == 0){
               
                $array['regs'] = $this->oportunityAllRegistration($this->getData['idopportunityReport'], 10);
                
                if(empty($array['regs']['regs'])) {
                    $this->handleRedirect('Ops! Você deve publicar a oportunidade para esse relatório', 401);
                }

                $verifyResource = $this->verifyResource($this->getData['idopportunityReport']);
                
                if(isset($verifyResource[0])){
                    $array['claimDisabled'] = $verifyResource[0]->value;
                }
                
                if(isset($regs['regs'][0]) && empty($verifyResource) || $array['claimDisabled'] == 1 ){
                    $array['title'] = 'Resultado Definitivo do Certame';
                    $array['template'] = 'pdf/definitive';
                }else if(isset($regs['regs'][0]) && empty($verifyResource) || $array['claimDisabled'] == 0){
                    $array['title'] = 'Resultado Definitivo do Certame';
                    $array['template'] = 'pdf/definitive';
                }else{
                    $app->redirect($app->createUrl('oportunidade/'.$this->getData['idopportunityReport'].'#/tab=inscritos'), 401);
                }
            }else{
                $array['regs'] = $this->oportunityAllRegistration($this->getData['idopportunityReport'], 10);
                $array['title'] = 'Resultado Definitivo do Certame';
                $array['template'] = 'pdf/definitive';
            }
        }else{
            $this->handleRedirect('Ops! Ocorreu um erro inesperado.', 401);
        }
        return $array;
    }

    function listContactsHandle($app, $array){
        $array['regs'] = $this->oportunityRegistrationAproved($this->getData['idopportunityReport'], 10);

        if(empty($regs['regs']['regs'])){
            $this->handleRedirect('', 401);
        }
        $array['title'] = 'Relatório de contato';
        $array['template'] = 'pdf/contact';
        return $array;
    }

    function handleRedirect($error_message, $status_code){
        $app = App::i();
        $_SESSION['error'] = $error_message;
        $app->redirect($app->createUrl('oportunidade/'.$this->getData['idopportunityReport'].'#/tab=inscritos'), $status_code);
    }
    
    /**
     * Busca a oportunidade e todos os aprovados da inscrição 
     *
     * @param [integer] $idopportunity
     * @return void array
     */
    function oportunityRegistrationAproved($idopportunity, $status) 
    {
        $app = App::i();
        $opp = $app->repo('Opportunity')->find($idopportunity);
        
        if($status == 10) {
            $dql = "SELECT r
                    FROM 
                    MapasCulturais\Entities\Registration r
                    WHERE r.opportunity = {$idopportunity}
                    AND r.status = 10 ORDER BY r.consolidatedResult DESC";
            $query = $app->em->createQuery($dql);
            $regs = $query->getResult();
        }else{
            $regs = $app->repo('Registration')->findBy(
                [
                'opportunity' => $idopportunity
                ]
            );
        }
        
        return ['opp' => $opp, 'regs' => $regs];
    }

    function oportunityAllRegistration($idopportunity) 
    {
        $app = App::i();
        $opp = $app->repo('Opportunity')->find($idopportunity);

        $regs = $app->repo('Registration')->findBy(
            [
            'opportunity' => $idopportunity
            ]
        );
        
        return ['opp' => $opp, 'regs' => $regs];
    }

    function verifyResource($idOportunidade) {
        $app = App::i();
        $opp = $app->repo('OpportunityMeta')->findBy(['owner'=>$idOportunidade,'key'=>'claimDisabled']);
        return $opp;
    }

    function GET_minha_inscricao() {
        ini_set('display_errors', 1);
        $app = App::i();
        //SOMENTE AUTENTICADO
        if($app->user->is('guest')){
            $app->auth->requireAuthentication();
        }

        $mpdf = new Mpdf(['tempDir' => dirname(__DIR__) . '/vendor/mpdf/mpdf/tmp','mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'L']);
        
        $reg = $app->repo('Registration')->find($this->data['id']);

        //SE O DONO DA INSCRIÇÃO NAO FOR O MESMO LOGADO, ENTÃO NÃO TEM PERMISSÃO DE ACESSAR.
        if($reg->owner->userId != $app->user->id) {
           //SE OS IDS FOREM DIFERENTE, VERIRICA SE ELE NAO É UM ADMIN PARA RETORNAR A PÁGINA ANTERIOR           
            if(!$reg->opportunity->owner->canUser('@control')){                
                $_SESSION['error'] = "Ops! Você não tem permissão";               
                $app->redirect($app->request()->getReferer(), 403);            
            }
        }
       
        //INSTANCIA DO TIPO ARRAY OBJETO
        $app->view->regObject = new \ArrayObject;
        $app->view->regObject['ins'] = $reg;
        $fields = [];
        //CRIANDO UM ARRAY COM SOMENTE ALGUNS ITENS DO OBJETO
        foreach ($reg->opportunity->registrationFieldConfigurations as $field) {
         //   dump($fields);
            array_push($fields , [
                        'displayOrder' => $field->displayOrder,
                        'id' => $field->id,
                        'title' => $field->title,
                        'description' => $field->description,
                        'fieldType' => $field->fieldType,
                        'config' => $field->config,
                        'owner' => $field->owner                        
                    ]);
        }
       // die;
        
        //ORDENANDO O ARRAY EM ORDEM DE ID
        sort($fields);

        $registrationFieldConfigurations = $fields;
        $app->view->regObject['fieldsOpportunity'] = $registrationFieldConfigurations;

        $template   = 'pdf/my-registration';
        //$app->render($template);
        ob_start();
        $content = $app->view->fetch($template);

        $footer = '<div style="border-top: 1px solid #c5c5c5;">
        <p style="text-align: center; font-size: 10px;"><span>Escola de Saúde Pública do Ceará Paulo Marcelo Martins Rodrigues</span></p>
        <p style="text-align: center; font-size: 10px;"><span>Av. Antônio Justa, 3161 - Meireles - CEP: 60.165-090</span></p>
        <p style="text-align: center; font-size: 10px;"><span>Fortaleza / CE - Fone: (85) 3101.1398</span></p>
        </div>';
                
        $mpdf->SetHTMLFooter($footer);
        $mpdf->SetHTMLFooter($footer, 'E');
        $mpdf->writingHTMLfooter = true;
        //$mpdf->SetDisplayMode('fullpage');
        $mpdf->SetTitle('Mapa da Saúde - Relatório');
        $stylesheet = file_get_contents(PLUGINS_PATH.'PDFReport/assets/css/stylePdfReport.css');
        $mpdf->WriteHTML(ob_get_clean());
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($content,2);
        $mpdf->Output();
        exit;
    }

    
}