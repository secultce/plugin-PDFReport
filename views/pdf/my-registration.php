<?php
$this->layout = 'nolayout';
$reg = $app->view->regObject['ins'];

// dump($reg->opportunity); die;
include_once('header.php');  ?>

<table width="100%" style="height: 100px;">
    <thead>
        <tr>
            <td>
                <?php if (empty($reg->opportunity->getFile('header')->path)) : ?>
                    <!-- <img src="<?php echo $reg->opportunity->getFile('header')->path; ?>" alt=""> -->
                    <div>
                        <br><br><br>
                    </div>
                    <header class="main-content-header" style="border-radius: 8px;">
                        <div
                            <?php if ($header = $reg->opportunity->getFile('header')) : ?>
                                class="header-image"
                                style="background-image: url(<?php echo $header->transform('header')->url; ?>);"
                            <?php endif; ?>
                        >
                        </div>
                    <!-- <div>
                        <img src="<?php echo $reg->opportunity->getFile('header')->path; ?>" alt="">
                    </div> -->
                    </header>
                <?php else : ?>
                    <img src="<?php $this->asset('img/backgroud_header_report_opp.png') ?>" alt="">
                <?php endif; ?>
            </td>
        </tr>
        <thead>
</table>

<table style="width: 100%;" class="table-info-ins">
    <thead>
        <tr>
            <td style="width: 50%;" style=" background: #480dd1;">
                <label class="title-ins-label">Inscrição</label> <br>
                <label class="title-ins-sublabel"><?php echo $reg->id; ?></label>
            </td>
            <td  style="width: 50%;" style="background: #f0c505;">
                <label class="title-ins-sublabel">Registrada no dia: <?php echo $reg->sentTimestamp->format('d/m/Y'); ?> </label> <br>
            </td>
        </tr>
    </thead>
</table>

<table width="100%" style="height: 100px; margin-bottom:40px;">
    <thead>
        <tr class="">
            <td style="width: 10%;">

                <!-- <img src="<?php $this->asset('img/logo-saude.png') ?>"  class="pull-left" > -->
                <?php if (!empty($reg->opportunity->files['avatar'])) : ?>
                    <img src="<?php echo $reg->opportunity->files['avatar']->path; ?>" style="width: 80px; height: 80px;">
                <?php else : ?>
                    <img src="<?php echo THEMES_PATH . 'BaseV1/assets/img/avatar--opportunity.png'; ?>" style="width: 80px; height: 80px;">
                    <!-- <label for=""><?php echo THEMES_PATH . 'BaseV1/assets/img/avatar--opportunity.png'; ?></label> -->
                <?php endif; ?>

            </td>
            <td style="width: 90%;">
                <!-- <img src="<?php $this->asset('img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png') ?>" class="pull-right" alt=""> -->
                <label for="" class="title-edital">Edital</label><br>
                <label class="sub-title-edital"><?php echo $reg->opportunity->ownerEntity->name; ?></label>
                <br>
                <label for="" class="title-edital">Oportunidade</label><br>
                <label class="sub-title-edital"><?php echo $reg->opportunity->name; ?></label>
            </td>
        </tr>
    </thead>
</table>

<?php
include_once('footerPdf.php');