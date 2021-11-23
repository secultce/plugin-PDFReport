<?php
namespace PDFReport\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\App;
use MapasCulturais\RegistrationMeta;

class Pdf extends \MapasCulturais\Entity{

    static public  function getValueField($id, $registration) {
        $app = App::i();
        $body = 'field_'.$id;
        return $app->repo('RegistrationMeta')->findBy([
            'key' => $body,
            'owner' => $registration
        ]);
              
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

    /**
     * Metodo que converte uma string de json em array
     *
     * @param [type] $value o valor que vem do campo $valueMetas
     * @param [type] $field string do nome do campo do indice array
     * @param [type] $nameField string do nome do campo do valor do array
     * @return void showDecode($valueMeta->value, 'title')
     */
    static public function showDecode($value, $field = null, $nameField) {
        $stringDecodeJson = json_decode($value, true);
        $arrayItens = [];
        foreach($stringDecodeJson as $item) {
            //$listLinks[] = $link['value'];
            if(!is_null($field)) {
                if(isset($item[$field])){
                    $arrayItens[] = "Titulo: ".$item[$field]." : ".$item[$nameField];
                }else{
                    $arrayItens[] = $item[$nameField];
                }
            }else{
                $arrayItens[] = $item[$nameField];
            }
            
        }
        echo implode(", ", $arrayItens);
    }

    //static public function getAgente
}

