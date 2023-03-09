<?php
if (isset($_GET['id'])) {
    $actividad_id = $_GET['id'];
}
$post_ = get_post($actividad_id);

if (!$post_ || get_post_type($actividad_id) != "actividades") {
?>
    <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">report</span>
        <strong>Actividad no encontrada</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
} else {
?>
    <div class="card card-binnacle p-0 card-activity" style="width: 500px;">
        <?php
        if (has_post_thumbnail($actividad_id)) {
            $image_url =  get_the_post_thumbnail_url($actividad_id, 'medium');
        ?>
            <div class="box-img">
                <span class="badge-p badge bgn-secondary"><?= strtoupper(get_post_type($actividad_id)) ?></span>
                <img src="<?= $image_url ?>" class="card-img-top" alt="...">
            </div>
        <?php
        }
        ?>
        <div class="card-body">
            <h1 class="text-center mb-1 title-h1 card-title"><?= get_the_title($actividad_id) ?></h1>
            <h4 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Descripción:</h4>
            <p class="title-p">
                <?= get_the_excerpt($actividad_id) ?>
            </p>
            <h4 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Hora Inicio:</h4>
            <p class="title-p">
                <?= get_field("hora_inicio", $actividad_id) ?>
            </p>
            <h4 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Hora Fin:</h4>
            <p class="title-p">
                <?= get_field("hora_fin", $actividad_id) ?>
            </p>
        </div>
        <div class="card-footer text-muted">
            <?php
            $terms = wp_get_post_terms($actividad_id, 'fechas', array('fields' => 'all'));
            $newDate = date("d/m/Y", strtotime($terms[0]->name));
            ?>
            <small class="title-p d-flex justify-content-end"><strong class="me-1">Fecha: </strong> <?= $newDate ?></small>
        </div>
    </div>
<?php
}
?>