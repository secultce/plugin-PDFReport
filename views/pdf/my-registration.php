<?php
$this->layout = 'nolayout';
$reg = $app->view->regObject['ins'];

include_once('header.php');  



?>

<table width="100%" style="height: 100px; margin-bottom: 24px; margin-top: 24px; width: 100%;">
    <thead>
        <tr>
            <td>
                <?php if (!empty($reg->opportunity->getFile('header'))) : ?>

                <div>
                    <img src="<?php echo $reg->opportunity->getFile('header')->path; ?>" alt="">
                </div>
                </header>
                <?php else : ?>
                <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/backgroud_header_report_opp.png'; ?>"
                    style="float:left;  width: 650px" />
                <!-- <img src="<?php //$this->asset('img/backgroud_header_report_opp.png') ?>" alt=""> -->
                <?php endif; ?>
            </td>
        </tr>
        <thead>
</table>

<main>
    <div class="row">
        <div class="container">
            <div class="col-md-12" class="table-info-ins">
                <div class="col-md-6" style="width: 40%; float: left;">
                    <label class="title-ins-label">Inscrição</label> <br>
                    <label class="title-ins-sublabel"><?php echo $reg->id; ?></label>
                </div>
                <div class="col-md-6  title-ins-sublabel-right" style="width: 50%;float: left;">
                    <label class="title-ins-sublabel">
                        Registrada no dia: <?php echo $reg->sentTimestamp->format('d/m/Y'); ?>
                    </label> <br>
                </div>
            </div>
        </div>
    </div>

    <table width="100%" style="height: 100px; margin-top: 16px">
        <thead>
            <tr class="">
                <td style="width: 10%;">

                    <!-- <img src="<?php $this->asset('img/logo-saude.png') ?>"  class="pull-left" > -->
                    <?php if (!empty($reg->opportunity->files['avatar'])) : ?>
                    <img src="<?php echo $reg->opportunity->files['avatar']->path; ?>"
                        style="width: 80px; height: 80px; border: 1px solid black; margin-right: 8px">
                    <?php else : ?>
                    <img src="<?php echo THEMES_PATH . 'BaseV1/assets/img/avatar--opportunity.png'; ?>"
                        style="width: 80px; height: 80px;">
                    <!-- <label for=""><?php echo THEMES_PATH . 'BaseV1/assets/img/avatar--opportunity.png'; ?></label> -->
                    <?php endif; ?>

                </td>
                <td style="width: 90%;">
                    <!-- <img src="<?php $this->asset('img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png') ?>" class="pull-right" alt=""> -->
                    <label for=""><strong>Edital</strong></label><br>
                    <label class="sub-title-edital"><?php echo $reg->opportunity->ownerEntity->name; ?></label>
                    <br>
                    <label for="" class="title-edital">Oportunidade</label><br>
                    <label class="sub-title-edital"><?php echo $reg->opportunity->name; ?></label>
                </td>
            </tr>
        </thead>
    </table>

    <table style="margin-top: 24px; margin-bottom: 24px">
        <thead>
            <tr>
                <td>
                    <p class="my-registration-email-confirm">
                        <!-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum similique pariatur obcaecati tempora 
                    recusandae delectus, id ipsa totam exercitationem impedit libero blanditiis ullam quae sit labore quod optio 
                    laudantium? -->
                    </p>
                </td>
            </tr>
        </thead>
    </table>

    <div style="border: 1px solid #E8E8E8; border-radius: 8px; width:  100%;; height: 400px; padding: 8px;">
        <h4 class="my-registration-fields">
            Agente responsável pela inscrição
        </h4>
        <br>
        <?php if(!empty($reg->owner->avatar)): ?>
        <img src="<?php echo $reg->owner->avatar->transform('avatarSmall')->url ?>" alt="">
        <?php else: ?>
            <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/avatar--agent.png'; ?>" alt="" style="width: 50px; height: 50px;flex: none;order: 0;flex-grow: 0;margin: 8px 8px;background: #CCCCCC;">
            <label style="font-size: 12px;line-height: 9px;color: rgba(0, 0, 0, 0.87);
                        font-style: normal;font-weight: normal;letter-spacing: 0.5px;
                        align-items: center;">
            <?php echo $reg->number; ?>
            
                
        </label>

        
        <!-- <img src="<?php $this->asset('img/avatar--agent.png') ?>" alt=""
                        style="width: 24px;height: 24px;flex: none;order: 0;flex-grow: 0;margin: 0px 8px;"> -->
        <?php endif; ?>
        <br> <br>
        <label class="my-registration-fields">Site: </label>
        <span><?php echo !empty($reg->owner->metadata['site']) ? $reg->owner->metadata['site']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Nome completo: </label>
        <span><?php echo $reg->owner->name ? $reg->owner->name : ""; ?>
        </span><br>
        <label class="my-registration-fields">Data de Nascimento/Fundação: </label>
        <span>
            <?php echo !empty($reg->owner->metadata['dataDeNascimento'])? date("d/m/Y", strtotime($reg->owner->metadata['dataDeNascimento'])) : ""; ?>
        </span><br>
        <label class="my-registration-fields">Gênero: </label>
        <span><?php echo !empty($reg->owner->metadata['genero']) ? $reg->owner->metadata['genero']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Orientação Sexua: </label>
        <span>
            <?php !empty($reg->owner->metadata['orientacaoSexual']) ? $reg->owner->metadata['orientacaoSexual']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Raça/Cor: </label>
        <span>
            <?php !empty($reg->owner->metadata['raca']) ? $reg->owner->metadata['raca']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Email Privado: </label>
        <span><?php !empty($reg->owner->metadata['emailPrivado']) ? $reg->owner->metadata['emailPrivado']: ""; ?>
        </span><br>
        <label class="my-registration-fields">E-mail: </label>
        <span>
            <?php !empty($reg->owner->metadata['emailPublico']) ? $reg->owner->metadata['emailPublico']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Telefone Público: </label>
        <span>
            <?php !empty($reg->owner->metadata['telefonePublico']) ? $reg->owner->metadata['telefonePublico']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Telefone 1: </label>
        <span><?php !empty($reg->owner->metadata['telefone1']) ? $reg->owner->metadata['telefone1']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Telefone 2: </label>
        <span><?php !empty($reg->owner->metadata['telefone2']) ? $reg->owner->metadata['telefone2']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Currículo Lattes: </label>
        <span>
            <?php !empty($reg->owner->metadata['curriculoLattes']) ? $reg->owner->metadata['curriculoLattes']: ""; ?>
        </span><br>
        <label class="my-registration-fields">Grau acadêmico: </label>
        <span>
            <?php !empty($reg->owner->metadata['profissionais_graus_academicos']) ? $reg->owner->metadata['profissionais_graus_academicos']: ""; ?>
        </span><br>
    </div>
    <?php
$fieldOp = $app->view->regObject['fieldsOpportunity'];
$this->part('reports/section', ['field' => $fieldOp, 'reg' => $reg]);


//include_once('footerPdf.php');