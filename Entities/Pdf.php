<?php
namespace PDFReport\Entities;

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
    static public function showDecode($valueStr, $field = null, $nameField) {
        $stringDecodeJson = json_decode($valueStr, true);
        $arrayItens = [];
        foreach($stringDecodeJson as $item) {
            if(!is_null($field)) {
                if(isset($item[$field])){
                    $arrayItens[] = "<strong>Titulo: </strong>".$item[$field]." - ".$item[$nameField];
                }else{
                    $arrayItens[] = $item[$nameField];
                }
            }else{
                $arrayItens[] = $item[$nameField];
            }
            
        }
        echo implode(", ", $arrayItens);
    }

    static public function showAddress($metaData) {
        $endereco = json_decode($metaData, true);

            $additional     = ( isset($endereco['En_Complemento'] ) && $endereco['En_Complemento'] != '' ) ? ", " . $endereco['En_Complemento']: "" ;
            $neighborhood   = ( isset($endereco['En_Bairro'] ) && $endereco['En_Bairro'] != '' ) ? ", " . $endereco['En_Bairro']: "" ;
            $city           = ( isset($endereco['En_Municipio'] ) && $endereco['En_Municipio'] != '' ) ? ", " . $endereco['En_Municipio']: "" ;
            $state          = ( isset($endereco['En_Estado'] ) && $endereco['En_Estado'] != '' ) ? ", " . $endereco['En_Estado']: "" ;
            $cep            = ( isset($endereco['En_CEP'] ) && $endereco['En_CEP'] != '' ) ? ", " . $endereco['En_CEP']: "" ;
            $address_number = ( isset($endereco['En_Num'] ) && $endereco['En_Num'] != '' ) ? ", " . $endereco['En_Num']: "" ;
            $street         = ( isset($endereco['En_Nome_Logradouro'] ) && $endereco['En_Nome_Logradouro'] != '' ) ? $endereco['En_Nome_Logradouro']: "" ;
            //montando endereço caso o $endereco == null
            $address = $street .  $address_number . $additional . $neighborhood . $cep . $city . $state;
            echo $address;
    }
    static public function showItensCheckboxes($str) {
        $strToarray = explode(',', $str);
        $items = "";
        foreach($strToarray as $options){
            $item = trim(preg_replace('/\PL/u', ' ', $options)).",";
            $items .= ' '.$item;            
        }
        echo substr($items, 0 ,-1);
    }
    
    static public function showAgenteOwnerField($field, $metaData, $owner) {

        if ($field == '@location') {
            self::showAddress($metaData);
        }else
        if( $field == '@terms:area' || $field == "genero" ||
            $field == 'longDescription' ||
            $field == 'telefone1' || $field == 'telefone2'){
            echo trim(preg_replace('/\PL/u', ' ', $metaData));          
        }elseif( $field == 'name' ) {

           echo $owner['name'];

        }elseif( $field == 'nomeCompleto' ) {

           echo $owner['nomeCompleto'];

        }elseif($field == "facebook" || $field == "intagram" || 
                $field == "twitter" || $field == "site" || 
                $field == "googleplus"){
            echo str_replace(array('\\', '"'), '', $metaData); 

        }elseif( $field == 'shortDescription') {
            echo $owner['shortDescription'];
        }
        else{

            echo trim(preg_replace('/\PL/u', ' ', $metaData));
            
        }

    }


    static public function showAgentCollectiveField($field,$metaData ) {
        if ($field == '@location') {
            self::showAddress($metaData);
        }else 
        if( $field == 'name' || $field == '@terms:area' || 
            $field == 'shortDescription' || $field == 'longDescription' ||
            $field == 'telefone1' || $field == 'telefone2') {
            echo trim(preg_replace('/\PL/u', ' ', $metaData))."";
        }else 
        if($field == '@links') {
            self::showDecode($metaData, 'title', 'value');
        }
        else
        if($field == "facebook" || $field == "intagram" || $field == "twitter" || $field == "site"){
            echo str_replace(array('\\', '"'), '', $metaData); 
        }
    }

    static public function showSpaceField($field, $metaData) {
        if($field == '@location') {
            self::showAddress($metaData);
        }else
        if( $field == 'name' || $field == '@terms:area' || 
            $field == 'shortDescription' || 
            $field == 'longDescription') {
            echo trim(preg_replace('/\PL/u', ' ', $metaData));
        }else
        if($field == '@links') {
            self::showDecode($metaData, 'title', 'value');
        }else
        if( $field == 'telefone1' || $field == 'telefone2' )
        {
            echo str_replace(array('\'', '"'), '', $metaData); 
        }
        else
        if($field == "facebook" || $field == "intagram" || $field == "twitter" || $field == "site"){
            echo str_replace(array('\\', '"'), '', $metaData); 
        }else{
            echo trim(preg_replace('/\PL/u', ' ', $metaData));
        }
    }
    //static public function getAgente
}

