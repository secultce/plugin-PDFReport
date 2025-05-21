<?php

namespace PDFReport\Controllers;

use MapasCulturais\Entities\Registration;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use \MapasCulturais\App;
use PDFReport\Entities\Pdf as EntitiesPdf;

class Pdf extends \MapasCulturais\Controller
{
    const LIST_SUBSCRIBED =  [
        'id' => 1,
        'title' => 'Relação de Inscritos',
        'enabled' => true,
    ];
    const LIST_PRELIMINARY = [
        'id' => 2,
        'title' => 'Resultados preliminares',
        'enabled' => true,
    ];
    const LIST_DEFINITIVE = [
        'id' => 3,
        'title' => 'Resultados definitivos',
        'enabled' => true,
    ];
    const LIST_CONTACTS = [
        'id' => 4,
        'title' => 'Relação de contatos',
        'enabled' => false,
    ];

    public static function getReports($id = null)
    {
        $reports = [
            self::LIST_SUBSCRIBED,
            self::LIST_PRELIMINARY,
            self::LIST_DEFINITIVE,
            self::LIST_CONTACTS
        ];

        if ($id) {
            return $reports[$id];
        }

        return $reports;
    }

    public static function getReportsEnabled()
    {
        $reportsEnabled = [];
        foreach (self::getReports() as $report) {
            if ($report['enabled']) {
                $reportsEnabled[] = $report;
            }
        }

        return $reportsEnabled;
    }

    function GET_gerarPdf()
    {
        $app = App::i();


        if ($app->user->is('guest')) {
            $app->auth->requireAuthentication();
        }

        $array = [
            'regs' => '',
            'title' => '',
            'template' => '',
            'claimDisabled' => null,
            'pluginConf' => [
                'tempDir' => dirname(__DIR__) . '/vendor/mpdf/mpdf/tmp', 'mode' => 'utf-8',
                'format' => 'A4',
                'pagenumPrefix' => 'Página ',
                'pagenumSuffix' => '  ',
                'nbpgPrefix' => ' de ',
                'nbpgSuffix' => ''
            ]
        ];
        // dump($this->getData['selectRel']);
        if ($this->getData['selectRel'] == 0) EntitiesPdf::handleRedirect('Ops! Selecione uma opção', 401, $this->getData['idopportunityReport']);
        else if ($this->getData['selectRel'] == self::LIST_SUBSCRIBED['id']) $array = EntitiesPdf::listSubscribedHandle($app, $array, $this->getData);
        else if ($this->getData['selectRel'] == self::LIST_PRELIMINARY['id']) $array = EntitiesPdf::listPreliminaryHandle($app, $array, $this->getData);
        else if ($this->getData['selectRel'] == self::LIST_DEFINITIVE['id']) $array = EntitiesPdf::listDefinitiveHandle($app, $array, false, $this->getData);
        else if ($this->getData['selectRel'] == self::LIST_CONTACTS['id']) $array = EntitiesPdf::listContactsHandle($app, $array, $this->getData);
        else $app->redirect($app->createUrl('oportunidade/' . $this->getData['idopportunityReport']), 401);
        
        $mpdf = new Mpdf($array['pluginConf']);

        $app->view->jsObject['subscribers'] = $array['regs']['regs'];

        $app->view->jsObject['opp'] = $array['regs']['opp'];
        $app->view->jsObject['claimDisabled'] = $array['claimDisabled'];
        $app->view->jsObject['title'] = $array['title'];

        $content = $app->view->fetch($array['template']);

        $footerPage = $app->view->fetch('pdf/footer-page-pdf');
        $footerDocumentPage = $app->view->fetch('pdf/footer-document-pdf');

        $mpdf->SetHTMLFooter($footerPage);
        $mpdf->SetHTMLFooter($footerPage, 'E');
        $mpdf->writingHTMLfooter = true;

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetTitle('Mapas Culturais - Relatório');
        $stylesheet = file_get_contents(PLUGINS_PATH . 'PDFReport/assets/css/stylePdfReport.css');
        $mpdf->AddPage(
            '', // L - landscape, P - portrait 
            '',
            '',
            '',
            '',
            5, // margin_left
            5, // margin right
            10, // margin top
            20, // margin bottom
            0, // margin header
            3
        ); // margin footer
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content, 2);
        $mpdf->SetHTMLFooter($footerPage . $footerDocumentPage);
        $mpdf->Output('MapaDaSaude_Relatorio.pdf', 'I');
        exit;
    }

    public function GET_minha_inscricao(): void
    {
        $app = App::i();
        $reg = $app->repo('Registration')->find($this->data['id']);

        //CRIANDO UM ARRAY COM SOMENTE ALGUNS ITENS DO OBJETO
        $fields = EntitiesPdf::showAllFieldAndFile($reg);

        $this->renderMyRegistrationPDF($fields);
    }

    public function renderMyRegistrationPDF($registrationFieldConfigurations): void
    {
        ini_set('display_errors', 1);
        $app = App::i();
        //SOMENTE AUTENTICADO
        if ($app->user->is('guest')) {
            $app->auth->requireAuthentication();
        }

        $mpdf = new Mpdf([
            'tempDir' => '/tmp',
            'mode' => 'utf-8',
            'format' => 'A4',
            'pagenumPrefix' => 'Página ',
            'pagenumSuffix' => '  ',
            'nbpgPrefix' => ' de ',
            'nbpgSuffix' => '',
            'margin_top' => 45,
            'margin_bottom' => 30,
        ]);

        $reg = $app->repo('Registration')->find($this->data['id']);

        //SE O DONO DA INSCRIÇÃO NAO FOR O MESMO LOGADO, ENTÃO NÃO TEM PERMISSÃO DE ACESSAR.
        if ($reg->owner->userId != $app->user->id) {
            $userAdm = false;
            //Checkando para saber se o usuário está no grupo de adm
            foreach($reg->opportunity->agentRelations as $agent){
                if($agent->agent->user->id == $app->user->id){
                    $userAdm = true;
                }
            }
            //SE OS IDS FOREM DIFERENTE, VERIRICA SE ELE NAO É UM ADMIN PARA RETORNAR A PÁGINA ANTERIOR
            if (!$userAdm && !$reg->opportunity->owner->canUser('@control')) {
                $_SESSION['error'] = "Ops! Você não tem permissão";
                $app->redirect($app->request()->getReferer(), 403);
            }
        }

        //INSTANCIA DO TIPO ARRAY OBJETO
        $app->view->regObject = new \ArrayObject;
        $app->view->regObject['ins'] = $reg;
        $app->view->regObject['fieldsOpportunity'] = $registrationFieldConfigurations;

        ob_start();

        $content = $app->view->fetch('pdf/my-registration');

        $mpdf->SetTitle('Mapas Culturais - Relatório');
        $stylesheet = file_get_contents(PLUGINS_PATH . 'PDFReport/assets/css/stylePdfReport.css');
        $mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($content, 2);
        $mpdf->WriteHTML(ob_get_clean());
        $mpdf->Output();
        exit;
    }

    public function GET_inscricaoCompleta(): void
    {
        $app = App::i();
        /** @var Registration $reg */
        $reg = $app->repo('Registration')->find($this->data['id']);
        $regs = $this->getAllPhasesRegistration($reg);

        $fields = [];

        foreach ($regs as $reg) {
            $fields = array_merge($fields, EntitiesPdf::showAllFieldAndFile($reg));
        }

        $this->renderMyRegistrationPDF($fields);
    }

    private function getAllPhasesRegistration(Registration $reg): array
    {
        $regs = [$reg];

        while(true) {
            if (null === $reg->opportunity->parent) {
                return $regs;
            }

            $app = App::i();
            $reg = $app->repo('Registration')->findOneBy([
                'opportunity' => $reg->opportunity->parent,
                'number' => $reg->number,
            ]);
            $regs[] = $reg;
        }
    }
}
