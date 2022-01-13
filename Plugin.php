<?php
namespace PDFReport;

use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
    public function _init() {
        // enqueue scripts and styles

        // add hooks
        $app = App::i();
        
        //
        $app->hook('template(opportunity.single.header-inscritos):end', function () use ($app) {
            $app->view->enqueueScript('app', 'pdfreport', 'js/pdfreport.js');
            $entity = $this->controller->requestedEntity;
            $resource = false;
            //VERIFICANDO SE TEM A INDICAÇÃO DE RECURSO
            $isResource = array_key_exists('claimDisabled', $entity->metadata);
            //SE HOUVER O CAMPO FAZ O FOREACH
            if($isResource) {
                foreach ($entity->metadata as $key => $value) {
                    //SE O CAMPO EXISTIR E TIVER RECURSO HABILITADO
                    if($key == 'claimDisabled' && $value == 0) {
                        $resource = true;
                    }
                }
            }
            $this->part('reports/buttons-report',['resource' => $resource]);
        });

        $plugin = $this;
        $app->hook('template(registration.<<*>>.form):end', function () use ($app, $plugin) {
            $app->view->enqueueStyle('app', 'pdfreport', 'css/styleButtonPrint.css');
            $id = $this->data['entity']->id;
            $plugin->showButtonPrint($id);
        });
        

        $app->hook('template(registration.view.registration-single-header):end', function () use ($app, $plugin) {
            $app->view->enqueueStyle('app', 'pdfreport', 'css/styleButtonPrint.css');
            $id = $this->data['entity']->id;
            $plugin->showButtonPrint($id);
        });
       
    }

    public function showButtonPrint($id)
    {
        $app = App::i();
        $registration = $app->repo('Registration')->find($id);
        if(!is_null($registration) && $registration->status <> 0) {
            $app->view->part('reports/button-print', ['id' => $id]);
        }
    }

    public function register() {
        // register metadata, taxonomies
        $app = App::i();
        $app->registerController('pdf', 'PDFReport\Controllers\Pdf');
    }
}