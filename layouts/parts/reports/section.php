<?php
use PDFReport\Entities\Pdf;

?>
<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                <div  style="border: 1px solid #E8E8E8; border-radius: 8px; width:  100%;; height: 400px;">
                    <h4 style="margin-left: 10px;
    color: rgba(0, 0, 0, 0.87);
    font-family: Arial !important;">
                        <?php 
                        echo $reg->opportunity->name;
                        // foreach ($field as $value) {
                        //     if($value['fieldType'] == "section") {
                        //         echo $value['title']; 
                        //     }
                        // }
                        ?>
                    </h4>

                    <?php 
                        $check = 'Não Confirmo';
                        foreach ($field as $fields) :
                           
                    ?>
                        <label class="mt-4">
                        <?php echo $fields['title']; ?> :
                        </label>
                        <span style="width: 20px; background: red;  text-align: justify-all;"><?php 
                            $valueMeta = Pdf::getValueField($fields['id']); 
                            foreach ($valueMeta as $keyMeta => $valueMeta) {
                                if($fields['fieldType'] == 'checkbox' && $valueMeta->value == true) {
                                    $check = ' (Sim, Confirmo)';
                                    echo $fields['description'].$check;
                                }else{
                                    echo $valueMeta->value;
                                }
                                
                            }
                            ?></label><br>
                    <?php
                       endforeach;
                    ?>
                    
                </div>
            </td>
        </tr>
    </tbody>
</table>

