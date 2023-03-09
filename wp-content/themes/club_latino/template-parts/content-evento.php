<?php
if (isset($_GET['id'])) {
    $evento_id = $_GET['id'];
}
$post_ = get_post($evento_id);

if (!$post_ || get_post_type($evento_id) != "eventos") {
?>
    <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">report</span>
        <strong>Evento no encontrado</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
} else {
?>
    <div class="card card-binnacle p-0 card-event" style="width: 800px;">
        <?php
        if (has_post_thumbnail($evento_id)) {
            $image_url =  get_the_post_thumbnail_url($evento_id, 'large');
        ?>
            <div class="box-img">
                <span class="badge-p badge bgn-secondary"><?= strtoupper(get_post_type($evento_id)) ?></span>
                <img src="<?= $image_url ?>" class="card-img-top">
            </div>
        <?php
        }
        $tipo_de_organizador = get_field("tipo_de_organizador", $evento_id);
        $organizacion = get_information_event($tipo_de_organizador['value'], $evento_id);
        ?>
        <div class="card-body">
            <h1 class="text-center mb-1 title-h1-registre  card-title"><?= get_the_title($evento_id) ?></h1>
            <h2 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Hora Inicio</h2>
            <p class="title-p">
                <?= get_field("hora_inicio", $evento_id) ?>
            </p>
            <h2 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Hora Fin</h2>
            <p class="title-p">
                <?= get_field("hora_fin", $evento_id) ?>
            </p>
            <h2 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Organizador</h2>
            <p class="title-p">
                <strong>Tipo: </strong> <?= strtoupper($organizacion["type"]) ?>
                <br>
                <strong>Nombre: </strong> <?= $organizacion["name"] ?>
            </p>
            <h2 class="text-left mb-2 title-h2 fw-bold fs-5 subtitle-activity">Actividades</h2>
            <?php $all_activity = get_all_activitys($evento_id) ?>
            <table class="table table-light table-hover mt-4">
                <thead>
                    <tr>
                        <th scope="col"># Id</th>
                        <th scope="col">Actividad</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($all_activity as $key => $activity_event) {
                    ?>
                        <tr class="table-active">
                            <th scope="row">
                                <span class="title-p"><?= $activity_event["id"] ?></span>
                            </th>
                            <td>
                                <span class="title-p"><?= $activity_event["title"] ?></span>
                            </td>
                            <td>
                                <span class="title-p"><?= $activity_event["description"] ?></span>
                            </td>
                            <td>
                                <span class="title-p"><?= $activity_event["hora_inicio"] ?></span>
                            </td>
                            <td>
                                <span class="title-p"><?= $activity_event["hora_fin"] ?></span>
                            </td>
                            <td>
                                <a href="<?= home_url('/actividad/?id=' . $activity_event["id"]) ?>" target="_blank" class="link-light">
                                    <span class="title-detalle">Detalle</span>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">
            <?php
            $terms = get_field("fecha_del_evento", $evento_id);
            $newDate = date("d/m/Y", strtotime($terms));
            ?>
            <small class="title-p d-flex justify-content-end"><strong class="me-1">Fecha: </strong> <?= $newDate ?></small>
        </div>
    </div>
<?php
}

function get_all_activitys($event_id)
{
    $list_activity = get_field("listado_de_actividades", $event_id);
    return list_activities_by_avents($list_activity);
}


function list_activities_by_avents($list_activity)
{
    $activities = [];
    foreach ($list_activity as $key => $activity) {
        $activity_temp = [];
        $activity_temp["id"] = $activity->ID;
        $activity_temp["title"] = $activity->post_title;
        $activity_temp["description"] = $activity->post_content;
        $activity_temp["guid"] = $activity->guid;
        $activity_temp["link"] = get_permalink($activity->ID);
        $activity_temp["hora_inicio"] = get_field("hora_inicio", $activity->ID);
        $activity_temp["hora_fin"] = get_field("hora_fin", $activity->ID);

        array_push($activities, $activity_temp);
    }
    return $activities;
}
