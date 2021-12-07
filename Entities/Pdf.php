<?php
namespace PDFReport\Entities;

use MapasCulturais\App;
use MapasCulturais\RegistrationMeta;
use DateTime;

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

    static public function clearCPF_CNPJ($valor){
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
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
            if($owner['En_Complemento'] !== '') {
                print_r("CEP: ".$owner['En_CEP'].', 
                Logradouro: '.$owner['En_Nome_Logradouro'].', 
                Nº: '.$owner['En_Num'].', Comp: '.$owner['En_Complemento'].', 
                Bairro: '.$owner['En_Bairro'].', 
                Cidade: '.$owner['En_Municipio'].', 
                UF: '.$owner['En_Estado']);
            }else{
                print_r("CEP: ".$owner['En_CEP'].', 
                Logradouro: '.$owner['En_Nome_Logradouro'].', 
                Nº: '.$owner['En_Num'].', 
                Bairro: '.$owner['En_Bairro'].', 
                Cidade: '.$owner['En_Municipio'].', 
                UF: '.$owner['En_Estado']);
            }
            
        }else
        if( $field == '@terms:area' ||
            $field == 'longDescription'){
            echo trim(preg_replace('/\PL/u', ' ', $metaData));          
        }elseif($field == 'name' || $field == 'nomeCompleto' || $field == 'shortDescription' ||
                $field == "genero" || $field == 'telefone1' || $field == 'telefone2' || $field == 'emailPrivado' || $field == 'emailPublico') {

           echo $owner[$field];

        }elseif($field == "facebook" || $field == "intagram" || 
                $field == "twitter" || $field == "site" || 
                $field == "googleplus"){
            echo str_replace(array('\\', '"'), '', $metaData); 

        }
        elseif( $field == 'dataDeNascimento') {
            
            $date = DateTime::createFromFormat('Y-m-d', $owner['dataDeNascimento']);
            echo $date->format('d/m/Y');

        }elseif($field == 'documento') { // PARA FORMATAR CPF OU CNPJ
            $doc =  self::clearCPF_CNPJ($owner[$field]); // retirando formatação caso venha
            $str = strlen($doc); // total de carecteres
            if($str == 11) {
                echo self::mask($doc,'###.###.###-##');
            }else{
                echo self::mask($doc,'##.###.###/####-##');
            }
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

    static public function getDependenciesField($registration, $fields) {
        //$field é o ID DO FIELD
        $app = App::i();
       
        $show = true;
        $fieldRegMeta = '';
        $valueRegMeta = '';
        if(is_array($fields['config'])) {    
            foreach ($fields['config'] as $keyConf => $valConf) {
                if(isset($valConf['value'])) {
                   $valueRegMeta = $valConf['value'];                   
                   $fieldRegMeta = $valConf['field'];                   
                }
            }
        }
      
        $regField = $app->repo('RegistrationMeta')->findBy([
            'owner' =>$registration,
            'key' => $fieldRegMeta
        ]);

        foreach ($regField as $key => $valregField) {
            if($valueRegMeta !== $valregField->value) {
                $show = false;
            }
        }
        //dump($show);
       return $show;
    }

    /**
     * Metodo para verificação dos arquivos enviados na oportunidade
     *
     * @param [type] $registration
     * @param [type] $fileGroup
     * @return void
     */
    static public function getFileRegistration($registration, $fileGroup) {
        $app = App::i();
        //dump($fileGroup);
        $file = $app->repo('RegistrationFile')->findBy([
            'owner' => $registration,
            'group' => $fileGroup
        ]);
        //dump($file);
        if(count($file) > 0) {
            //dump(count($file));
            return $file;
        }
    }
    
    static public function showAllFieldAndFile($registrationOpportunity) {
        $fields = [];
        foreach ($registrationOpportunity->registrationFieldConfigurations as $field) {
   
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

        if($registrationOpportunity->registrationFileConfigurations->count() > 0) {
            // echo '<br/><span class="span-section"><i>Arquivos</i></span><br>';
            foreach ($registrationOpportunity->registrationFileConfigurations as $key => $file) {
            //     // dump($key);
           // dump($file->multiple);
            array_push($fields , [
                'displayOrder' => $file->displayOrder,
                'id' => $file->id,
                'title' => $file->title,
                'description' => $file->description,
                'fieldType' => 'file',
                'config' => $field->metadata,
                'owner' => $field->owner,
                'multiple' => $field->multiple     
            ]);
            }
        }
        sort($fields);
        return  $fields;

    }
}

