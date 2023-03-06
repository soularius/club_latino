<?php
if (isset($_GET['cedula_found']) && $_GET['cedula_found'] == 'failed') {
?>
    <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">report</span>
        <strong>Usuario no encontrado</strong>, por favor verifique la cedula.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (isset($_GET['cedula_found']) && $_GET['cedula_found'] == 'found') {
?>
    <div class="alert-binnacle alert alert-success alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">verified</span>
        <strong>Usuario Encontrado</strong>, registrado en bitacora.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (isset($_GET['cedula_found']) && $_GET['cedula_found'] == 'found_defeated') {
?>
    <div class="alert-binnacle alert alert-warning alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">warning</span>
        <strong>Usuario Encontrado</strong>, membresía vencida.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (isset($_GET['cedula_found']) && $_GET['cedula_found'] == 'found_fail') {
?>
    <div class="alert-binnacle alert alert-warning alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">warning</span>
        <strong>Usuario Encontrado</strong>, pero algun dato fallo.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
$args = array(
    'role__in' => array('socio', 'particular')
);
$users = get_users($args);
$page_id = $args["page_id"];
?>
<div class="card card-binnacle px-4 py-2">
    <div class="card-body">
        <div class="binnacle-form">
            <h1 class="text-center mb-4 title-h1 title-binnacle"><?= get_the_title($page_id) ?></h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="binnacleform" class="d-flex flex-column justify-content-center align-items-center">
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="user_binnacle" name="user_binnacle" placeholder="xx.xxx.xxx">
                        <label for="user_binnacle" class="title-p">Cédula de ciudadanía</label>
                    </div>
                    <input type="hidden" name="action" value="custom_binnacle_post">
                    <input type="submit" name="submit" id="submit" class="btn btn-very btn-secondary btn-lg" value="Registrar" />
                </div>
                <div id="box-obs" class="input-group mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" name="observacion" placeholder="Agrega tu observación" id="observacion" style="height: 100px"></textarea>
                        <label for="observacion" class="title-p">Observación</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_guest" id="is_guest" value="1">
                        <label class="form-check-label title-p" for="is_guest">
                            ¿Es invitado?
                        </label>
                    </div>
                </div>
                <div id="box-inv" class="w-100" style="display: none;">
                    <div class="d-flex input-group flex-column">
                        <?php
                        if ($users) {
                        ?>
                            <label class="mb-2" for="invitado" class="title-p">
                                ¿Quien lo invita?
                            </label>
                            <div class="box-select-inv mt-2">
                                <select id="invitado" name="invitado" class="select2 w-100 js-states form-control">
                                    <option value="" class="title-p">Seleccionar usuario</option>
                                    <?php
                                    foreach ($users as $user) {
                                        $first_name = $user->first_name;
                                        $last_name = $user->last_name;
                                    ?>
                                        <option value="<?= $user->ID ?>" class="title-p">
                                            <?= $first_name . ' ' . $last_name ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php
                        } else {
                            echo 'No se encontraron usuarios.';
                        }
                        ?>
                        <script>
                            jQuery(document).ready(function($) {
                                $('#invitado').select2({
                                    minimumInputLength: 1
                                });
                                $('#is_guest').change(function() {
                                    if ($(this).is(':checked')) {
                                        $('#box-inv').show();
                                    } else {
                                        $('#box-inv').hide();
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </form>
        </div>
        <?php
        if (isset($_GET['all_data'])) {
            $encoded_data = $_GET['all_data'];
            $decoded_data = unserialize(base64_decode(urldecode($encoded_data)));
            $basic = $decoded_data["basic"];
            $group = $decoded_data["group"];
            $team = $decoded_data["team"];
            $other_data = $decoded_data["other_data"];

            $current_date = strtotime(date('Y-m-d'));
            $fecha_vencimiento = strtotime(str_replace('/', '-', $other_data["fecha_vencimiento"]));

            $vencimiento = false;
            if ($fecha_vencimiento < $current_date) {
                $vencimiento = true;
            }
        ?>
            <div class="box-events">
                <h2 class="text-center mb-4 mt-4 title-h1 title-binnacle">Datos básicos</h2>
                <div class="box-data-user p-2">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-action" aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 font-primary"><strong>Información Básica</strong></h5>
                                <small>
                                    <span class="badge <?= $vencimiento ? 'bgn-danger' : 'bgn-primary' ?>"><?= strtoupper($basic["role"]) ?></span>
                                </small>
                            </div>
                            <p class="mb-1 font-primary">
                                <strong>Nombre:</strong>
                                <?= $basic["first_name"] . " " . $basic["last_name"] ?>
                            </p>
                            <p class="mb-1 font-primary">
                                <strong>Cedula de identidad:</strong>
                                <?= $other_data["cedula_de_ciudadania"] ?>
                            </p>
                            <small class="mb-1 font-primary">
                                <strong>GRUPO:</strong>
                                <?= $group["group_name"] ?>
                            </small>
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 font-primary"><strong>Membresía</strong></h5>
                                <small class="text-muted">
                                    <span class="badge <?= $vencimiento ? 'bgn-danger' : 'bgn-primary' ?>">
                                        <?= $vencimiento ? 'VENCIDO' : 'ACTIVO' ?>
                                    </span>
                                </small>
                            </div>
                            <p class="mb-1 font-primary">
                                <strong>Fecha Inicio:</strong>
                                <?= $other_data["fecha_inicio"] ?>
                            </p>
                            <p class="mb-1 font-primary">
                                <strong>Fecha Vencimiento:</strong>
                                <?= $other_data["fecha_vencimiento"] ?>
                            </p>
                            <p class="mb-1 font-primary">
                                <strong>Costo Mensual:</strong>
                                <?= $other_data["costo_mensual"] ?>
                            </p>
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 font-primary"><strong>Equipos</strong></h5>
                            </div>
                            <?php
                            if (!empty($team)) {
                            ?>
                                <ul class="list-group list-group-flush">
                                    <?php
                                    foreach ($team as $key => $t) {
                                    ?>
                                        <li class="list-group-item">
                                            <p class="mb-1 font-primary">
                                                <?= $t["team_name"] ?>
                                            </p>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $events = $decoded_data["events"];
                if (!empty($events)) {
                ?>
                    <h2 class="text-center mb-4 mt-4 title-h1 title-binnacle">Listado de eventos</h2>
                    <?php
                    foreach ($events as $key => $e) {
                    ?>
                        <h2 class="text-center mb-4 title-h1-v2 title-events">
                            Evento: <?= $e["name"] ?>

                            <a href="<?= home_url('/evento/?id=' . $e["id"]) ?>" target="_blank" class="color-primary url-event">
                                <span class="material-symbols-outlined material-icons color-primary">
                                    visibility
                                </span>
                            </a>
                        </h2>
                        <p class="text-left mb-1 title-p">
                            <strong>Organizador:</strong>
                            <?= $e["organization"]["name"] ?>
                        </p>
                        <p class="text-left mb-1 title-p">
                            <strong>Tipo Organizador:</strong>
                            <?= strtoupper($e["organization"]["type"]) ?>
                        </p>
                        <p class="text-left mb-1 title-p">
                            <strong>Fecha:</strong>
                            <?php
                            $newDate = date("d/m/Y", strtotime($e["fecha"]));
                            ?>
                            <?= $newDate ?>
                        </p>
                        <?php
                        if (isset($e["activity"]["activities_subscribe"]) && !empty($e["activity"]["activities_subscribe"])) {
                        ?>
                            <p class="text-left mb-1 mt-3 title-p">
                                <strong>Actividades a las que esta subscrito</strong>
                            </p>
                            <table class="table table-dark table-hover mt-4">
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
                                    foreach ($e["activity"]["activities_subscribe"] as $key => $activities_subscribe) {
                                    ?>
                                        <tr class="table-active">
                                            <th scope="row">
                                                <span class="title-p"><?= $activities_subscribe["id"] ?></span>
                                            </th>
                                            <td>
                                                <span class="title-p"><?= $activities_subscribe["title"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_subscribe["description"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_subscribe["hora_inicio"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_subscribe["hora_fin"] ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= home_url('/actividad/?id=' . $activities_subscribe["id"]) ?>" target="_blank" class="link-light">
                                                    <span class="title-p">Detalle</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                        <p class="text-left mb-1 mt-3 title-p">
                            <strong>Actividades recomendadas</strong>
                        </p>
                        <?php
                        if (isset($e["activity"]["activities_recomend"]) && !empty($e["activity"]["activities_recomend"])) {
                        ?>
                            <table class="table table-dark table-hover mt-4">
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
                                    foreach ($e["activity"]["activities_recomend"] as $key => $activities_recomend) {
                                    ?>
                                        <tr class="table-active">
                                            <th scope="row">
                                                <span class="title-p"><?= $activities_recomend["id"] ?></span>
                                            </th>
                                            <td>
                                                <span class="title-p"><?= $activities_recomend["title"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_recomend["description"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_recomend["hora_inicio"] ?></span>
                                            </td>
                                            <td>
                                                <span class="title-p"><?= $activities_recomend["hora_fin"] ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= home_url('/actividad/?id=' . $activities_recomend["id"]) ?>" target="_blank" class="link-light">
                                                    <span class="title-p">Detalle</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                <?php
                    }
                }
                ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>