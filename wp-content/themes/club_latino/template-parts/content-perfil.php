    <?php
    // Verifica si el usuario ya inició sesión
    if (is_user_logged_in()) {
        $user_id = get_current_user_id(); // Si no ha iniciado sesión, muestra el formulario de inicio de sesión
        $taxs = get_all_term($user_id);
        if (isset($_GET['envio']) && $_GET['envio'] == 'failed') {
    ?>
            <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
                <span class="material-icons material-icons-round me-1">report</span>
                <strong class="title-p">Fallo el envio</strong><span class="title-p">, todos los datos son necesarios.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        if (isset($_GET['envio']) && $_GET['envio'] == 'success') {
        ?>
            <div class="alert-binnacle alert alert-success alert-dismissible fade show" role="alert">
                <span class="material-icons material-icons-round">verified</span>
                <strong>Solicitud enviada</strong>, pronto le contactaremos.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <div class="card card-profile px-4 py-2">
            <div class="card-body">
                <div class="profile-form">
                    <h1 class="text-center mb-4 title-h1 title-profile">Perfil de Usuario</h1>
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="btn-profile btn-secondary-light title-p nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Inicio</button>
                            <button class="btn-profile btn-secondary-light title-p nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Perfil</button>
                            <button class="btn-profile btn-secondary-light title-p nav-link" id="v-pills-events-tab" data-bs-toggle="pill" data-bs-target="#v-pills-events" type="button" role="tab" aria-controls="v-pills-events" aria-selected="false">Tus Eventos</button>
                            <?php if (!empty($taxs)) { ?>
                                <button class="btn-profile btn-secondary-light title-p nav-link" id="v-pills-create-event-tab" data-bs-toggle="pill" data-bs-target="#v-pills-create-event" type="button" role="tab" aria-controls="v-pills-create-event" aria-selected="false">Crear Evento</button>
                            <?php } ?>
                        </div>
                        <div class="tab-content w-100" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <h2 class="text-center mb-4 title-h2">Bienvenid@ ha Club Latino</h2>
                                <?php show_status($user_id) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <?php show_profile($user_id) ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-events" role="tabpanel" aria-labelledby="v-pills-events-tab">
                                <?php show_events($user_id) ?>
                            </div>
                            <?php if (!empty($taxs)) { ?>
                                <div class="tab-pane fade" id="v-pills-create-event" role="tabpanel" aria-labelledby="v-pills-create-event-tab">
                                    <?php form_create_event($user_id, $taxs) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }

    function show_profile($user_id)
    {

        if ($user_id) {
            $user_data = get_userdata($user_id);
            $user_meta = get_user_meta($user_id);
            $acf_data = search_data_acf_user($user_id);
            $group = search_data_group_user($user_id);
            $team = search_data_team_user($user_id);
        ?>
            <table class="table table-dark table-hover">
                <tbody>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Nombre de usuario</th>
                        <td colspan="2" class="title-p"><?= $user_data->user_login ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Nombre completo</th>
                        <td colspan="2" class="title-p"><?= $user_data->display_name ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Email</th>
                        <td colspan="2" class="title-p"><?= $user_data->user_email ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Teléfono</th>
                        <td colspan="2" class="title-p"><?= $acf_data["telefono"] ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Cedula de identidad</th>
                        <td colspan="2" class="title-p"><?= $acf_data["cedula_de_ciudadania"] ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Membresía - Fecha Inicio</th>
                        <td colspan="2" class="title-p"><?= $acf_data["fecha_inicio"] ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Membresía - Fecha Vencimiento</th>
                        <td colspan="2" class="title-p"><?= $acf_data["fecha_vencimiento"] ?></td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Membresía - Costo Mensual</th>
                        <td colspan="2" class="title-p"><?= $acf_data["costo_mensual"] ?> $</td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="title-p color-primary">Grupo</th>
                        <td colspan="2" class="title-p"><?= $group["group_name"] ?></td>
                    </tr>
                    <?php
                    foreach ($team as $key => $t) {
                    ?>
                        <tr class="">
                            <th scope="row" class="title-p color-primary">Equipo #<?= $key + 1 ?></th>
                            <td colspan="2" class="title-p"><?= $t["team_name"] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
    }

    function show_status($user_id)
    {
        $acf_data = search_data_acf_user($user_id);
        $current_date = strtotime(date('Y-m-d'));
        $fecha_vencimiento = strtotime(str_replace('/', '-', $acf_data["fecha_vencimiento"]));

        $vencimiento = false;
        if ($fecha_vencimiento < $current_date) {
            $vencimiento = true;
        }

        if (!$vencimiento) {
        ?>
            <div class="alert alert-success bgn-primary title-p fw-bold" role="alert">
                Tu membresia esta activa
            </div>
        <?php
        } else {
        ?>
            <div class="alert alert-danger bgn-danger title-p fw-bold" role="alert">
                Tu membresia esta vencida, por favor comunicate con nosotros
            </div>
        <?php

        }
    }

    function show_events($user_id)
    {
        $group = search_data_group_user($user_id);
        $team = search_data_team_user($user_id);
        $events = search_events_by_user($user_id, $group, $team, '>=');

        if (!empty($events)) {
        ?>
            <h2 class="text-center mb-4 mt-4 title-h1 title-binnacle">Listado de tus eventos</h2>
            <?php
            foreach ($events as $key => $e) {
            ?>
                <h2 class="text-center mb-4 title-h1-v2 title-events">Evento: <?= $e["name"] ?></h2>
                <p class="text-left mb-1 title-p">
                    <strong>Organizador:</strong>
                    <?= $e["organization"]["name"] ?>
                </p>
                <p class="text-left mb-1 title-p">
                    <strong>Tipo Organizador:</strong>
                    <?= strtoupper($e["organization"]["type"]) ?>
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
                <?php
                if (isset($e["activity"]["activities_recomend"]) && !empty($e["activity"]["activities_recomend"])) {
                ?>
                    <p class="text-left mb-1 mt-3 title-p">
                        <strong>Actividades recomendadas</strong>
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
    }

    function form_create_event($user_id, $taxs)
    {
        ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="eventform" class="d-flex flex-column justify-content-center align-items-center">
            <div class="input-group mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="event_name" name="event_name" placeholder="xx.xxx.xxx">
                    <label for="event_name" class="title-p">Nombre de Evento</label>
                </div>
            </div>
            <div id="box-obs" class="input-group mb-3">
                <div class="form-floating">
                    <textarea class="form-control" name="description" placeholder="Agrega tu descripción" id="description" style="height: 100px"></textarea>
                    <label for="description" class="title-p">Descripción</label>
                </div>
            </div>
            <div id="box-inv" class="input-group mb-3">
                <div class="d-flex input-group flex-column">
                    <?php
                    if ($taxs) {
                    ?>
                        <div class="box-select-inv mt-2">
                            <select id="team" name="team" class="select2 w-100 js-states form-control">
                                <option value="" class="title-p">Seleccionar el equipo/grupo</option>
                                <?php
                                foreach ($taxs as $tax) {
                                ?>
                                    <option value="<?= $tax->term_id ?>" class="title-p">
                                        <?= $tax->name ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    } else {
                        echo 'No se encontraron grupos o equipos.';
                    }
                    ?>
                    <script>
                        jQuery(document).ready(function($) {
                            $('#team').select2({
                                minimumInputLength: 1
                            });
                        });
                    </script>
                </div>
            </div>

            <input type="hidden" name="action" value="custom_event_post">
            <input type="submit" name="submit" id="submit" class="btn btn-very btn-secondary btn-lg" value="Enviar Solicitud" />
        </form>
    <?php
    }

    function get_all_term($user_id)
    {
        $args = array(
            'taxonomy' => array('grupo', 'equipo'),
            'meta_query' => array(
                array(
                    'key' => 'persona_responsable',
                    'value' => $user_id,
                    'compare' => '='
                )
            )
        );

        $taxonomies = get_terms($args);

        return $taxonomies;
    }
