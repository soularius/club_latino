<?php
if (isset($_GET['registro']) && $_GET['registro'] == 'failed') {
?>
    <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round me-1">report</span>
        <strong class="title-p">Registro a fallado</strong><span class="title-p">, todos los datos son requeridos.</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (isset($_GET['registro']) && $_GET['registro'] == 'failed_data' && isset($_GET['errors'])) {
    $encoded_errors = $_GET['errors'];
    $decoded_errors = unserialize(base64_decode(urldecode($encoded_errors)));
?>
    <div class="alert-binnacle alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex flex-column align-items-start justify-content-center w-100">
            <span class="d-flex flex-row">
                <span class="material-icons material-icons-round me-1">report</span>
                <strong class="title-p">Registro a fallado</strong>, todos los datos son requeridos.<br>
            </span>
            <strong class="title-p">Se encontraron los siguientes errores:</strong><br>
            <ul class="list-group list-group-flush mt-3 ms-0 w-100">
                <?php
                foreach ($decoded_errors as $key => $error) {
                ?>
                    <li class="list-group-item title-p"><?= $error ?></li>
                <?php
                }
                ?>
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (isset($_GET['registro']) && $_GET['registro'] == 'success') {
?>
    <div class="alert-binnacle alert alert-success alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round me-1">verified</span>
        <strong>Usuario Registrado</strong>, con exito.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}

$invitado = get_role('invitado');
$visitante = get_role('visitante');

$roles = array(
    array(
        'name' => $invitado->name,
    ),
    array(
        'name' => $visitante->name,
    )
);
?>

<div class="card card-register px-4 py-2">
    <div class="card-body">
        <div class="register-form">
            <h1 class="text-center mb-4 title-h1-registre">Registrar</h1>
            <form name="registerform" id="registerform" class="d-flex flex-column justify-content-center align-items-center" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Maria">
                    <label for="first_name" class="title-p">Nombre</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Perez">
                    <label for="last_name" class="title-p">Apellido</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cedula_de_ciudadania" name="cedula_de_ciudadania" placeholder="xx.xxx.xxx">
                    <label for="cedula_de_ciudadania" class="title-p">Cedula de Identidad</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                    <label for="email" class="title-p">Correo Electrónico</label>
                </div>
                <div class="d-flex justify-content-center align-items-center mb-4 input-group flex-column box-select2-rol">
                    <?php
                    if ($roles) {
                    ?>
                        <div class="box-select-reg mt-2">
                            <select id="rol" name="rol" class="select2 w-100 js-states form-control">
                                <option value="" class="title-p">Selecciona el rol</option>
                                <?php
                                foreach ($roles as $rol) {
                                ?>
                                    <option value="<?= $rol['name'] ?>" class="title-p">
                                        <?= $rol['name'] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    } else {
                        echo 'No se encontraron roles.';
                    }
                    ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            $('#rol').select2({
                                minimumInputLength: 1
                            });
                        });
                    </script>
                </div>
                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-secondary btn-lg btn-very" value="Registrar" />
                <input type="hidden" name="action" value="custom_register_user_post">
            </form>
        </div>
    </div>
</div>