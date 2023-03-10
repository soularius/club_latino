<?php
$page_id = $args["page_id"];
?>
<div id="box-tittle" class="d-flex flex-column mb-5">
    <h2 class="title-homeh2 subtitle-main-init"><?= get_field("titulo", $page_id) ?></h2>
    <h1 class="title-homeh1 title-main-init"><?= get_field("subtitulo", $page_id) ?></h1>
</div>
<div id="box-buttons" class="d-flex flex-row justify-content-center">
    <?php
    $btn_yellow = get_field("texto_boton_amarillo", $page_id);
    $btn_green = get_field("texto_boton_verde", $page_id);
    ?>
    <?php
    if (!is_user_logged_in()) {
    ?>
        <a type="button" class="btn-inicio btn btn-secondary btn-lg me-2" href="<?= $btn_yellow["url"] ?>">
            <?= $btn_yellow["title"] ?>
        </a>
        <?php
    } else {
        $user_id = get_current_user_id();
        if (user_can($user_id, 'seguridad') || user_can($user_id, 'manage_options') || is_admin()) {
            $page_url_login = '';
            $page_slug_login = 'registro-de-bitacora'; // Reemplazar con el slug de la página deseada
            $page = get_page_by_path($page_slug_login);
            if ($page) {
                $page_url_login = get_permalink($page->ID);
            }
        ?>
            <a type="button" class="btn-inicio btn btn-secondary btn-lg me-2" href="<?= $page_url_login ?>">
                Bitácora
            </a>
        <?php
        } else {
        ?>
            <a type="button" class="btn-inicio btn btn-secondary btn-lg me-2" href="<?= home_url('/perfil/') ?>">
                Mi cuenta
            </a>
    <?php
        }
    }
    ?>
    <a type="button" class="btn-inicio btn btn-primary btn-lg" href="<?= $btn_green["url"] ?>">
        <?= $btn_green["title"] ?>
    </a>
</div>