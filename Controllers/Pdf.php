<?php
namespace PDFReport\Controllers;

require PLUGINS_PATH.'PDFReport/vendor/autoload.php';
require PLUGINS_PATH.'PDFReport/vendor/dompdf/dompdf/src/FontMetrics.php';

use DateTime;
use Mpdf\Mpdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use \MapasCulturais\App;
use PDFReport\Entities\Pdf as EntitiesPdf;

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
        if($this->getData['selectRel'] == NO_SELECTION) EntitiesPdf::handleRedirect('Ops! Selecione uma opção', 401, $this->getData['idopportunityReport']);
        else if($this->getData['selectRel'] == LIST_SUBSCRIBED) $array = EntitiesPdf::listSubscribedHandle($app, $array, $this->getData);
        else if($this->getData['selectRel'] == LIST_PRELIMINARY) $array = EntitiesPdf::listPreliminaryHandle($app, $array, $this->getData);
        else if($this->getData['selectRel'] == LIST_DEFINITIVE) $array = EntitiesPdf::listDefinitiveHandle($app, $array, false, $this->getData);
        else if($this->getData['selectRel'] == LIST_CONTACTS) $array = EntitiesPdf::listContactsHandle($app, $array, $this->getData);
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
        $mpdf->AddPage('', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                10, // margin top
                0, // margin bottom
                0, // margin header
                0
            ); // margin footer
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($content,2);
        $mpdf->Output('MapaDaSaude_Relatorio.pdf', 'I');
        exit;
    }
    
    function GET_minha_inscricao() {
        ini_set('display_errors', 1);
        $app = App::i();
        //SOMENTE AUTENTICADO
        if($app->user->is('guest')){
            $app->auth->requireAuthentication();
        }

        $mpdf = new Mpdf(['tempDir' => dirname(__DIR__) . '/vendor/mpdf/mpdf/tmp','mode' => 'utf-8','format' => 'A4']);
        
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
        //CRIANDO UM ARRAY COM SOMENTE ALGUNS ITENS DO OBJETO
        $fields = EntitiesPdf::showAllFieldAndFile($reg);
       
        
        //ORDENANDO O ARRAY EM ORDEM DE ID
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
        $file_name = 'Ficha_de_inscricao.pdf';
        $mpdf->Output();
        exit;
    }

    
}