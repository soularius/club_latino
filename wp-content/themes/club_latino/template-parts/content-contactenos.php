<?php
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
<div class="card card-contact px-4 py-2">
    <div class="card-body">
        <div class="contact-form">
            <h1 class="text-center mb-4 title-h1 title-contact">Contáctenos</h1>
            <form name="contactform" id="contactform" class="d-flex flex-column justify-content-center align-items-center" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <div class="form-floating mb-3 w-100">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="000-0000-000">
                    <label for="nombre" class="title-p">Nombre</label>
                </div>
                <div class="form-floating mb-3 w-100">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                    <label for="email" class="title-p">Correo Electrónico</label>
                </div>
                <div class="form-floating mb-3 w-100">
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="000-0000-000">
                    <label for="telefono" class="title-p">Teléfono</label>
                </div>
                <div id="box-obs" class="input-group mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" name="comentario" placeholder="Agrega tu observación" id="comentario" style="height: 100px"></textarea>
                        <label for="comentario" class="title-p">Comentario</label>
                    </div>
                </div>
                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-secondary btn-lg" value="Enviar" />
                <input type="hidden" name="action" value="custom_contact_post">
            </form>
        </div>
    </div>
</div>