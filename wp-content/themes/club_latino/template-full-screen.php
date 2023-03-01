<?php

/*
Template Name: Full Page Screen
*/

$page_data = get_post($page_id);
$page_slug = $page_data->post_name;

switch ($page_slug) {
    case "registro-de-bitacora":
        redirect_permision();
        break;
    case "registrar":
        redirect_permision();
        break;
}

get_header();
$page_id = get_the_ID();
$src_url = get_field("background_image", $page_id);
?>

<main id="page-init" style="background-image: url('<?= $src_url["url"] ?>');">
    <div class="container">
        <div class="row flex-column justify-content-center align-items-center">
            <?php

            switch ($page_slug) {
                case "inicio":
                    get_template_part('template-parts/content', 'inicio', array('page_id' => $page_id));
                    break;
                case "iniciar-sesion":
                    get_template_part('template-parts/content', 'inicio-sesion', array('page_id' => $page_id));
                    break;
                case "registro-de-bitacora":
                    get_template_part('template-parts/content', 'bitacora', array('page_id' => $page_id));
                    break;
                case "registrar":
                    get_template_part('template-parts/content', 'registrar', array('page_id' => $page_id));
                    break;
            }
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>