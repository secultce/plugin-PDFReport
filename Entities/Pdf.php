<?php
namespace PDFReport\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\App;
use MapasCulturais\RegistrationMeta;

class Pdf extends \MapasCulturais\Entity{

    static public  function getValueField($id) {
        $app = App::i();
        $body = 'field_'.$id;
        return $app->repo('RegistrationMeta')->findBy(['key' => $body]);
              
    }

    static public function getNameField($id) {
        $app = App::i();
        $body = 'field_'.$id;
        return $app->repo('RegistrationFieldConfiguration')->findBy(['owner' => $id]);
    }

    static public function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    //static public function getAgente
}

