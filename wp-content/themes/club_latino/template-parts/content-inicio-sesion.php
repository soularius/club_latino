<?php

// Muestra los errores de inicio de sesión
if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
    ?>
    <div class="alert-login alert alert-danger alert-dismissible fade show" role="alert">
        <span class="material-icons material-icons-round">report</span>
        <strong>Credenciales inválidas</strong>, por favor intentelo de nuevo.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}

// Verifica si el usuario ya inició sesión
if ( ! is_user_logged_in() ) { // Si no ha iniciado sesión, muestra el formulario de inicio de sesión
?>
    <div class="card card-login px-4 py-2">
        <div class="card-body">
            <div class="login-form">
                <h1 class="text-center mb-4 title-h1 title-login">Iniciar sesión</h1>
                <form name="loginform" id="loginform" class="d-flex flex-column justify-content-center align-items-center" action="<?php echo esc_url( site_url( '/wp-login.php', 'login_post' ) ); ?>" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="user_login" name="log" placeholder="name@example.com">
                        <label for="user_login">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="user_pass" name="pwd" placeholder="Password">
                        <label for="user_pass">Contraseña</label>
                    </div>
                    <div class="form-floating mb-4">
                        <small class="small">¿Olvidaste tu contraseña? Cambiala aquí</small>
                    </div>
                    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-secondary btn-lg"  value="Iniciar sesión" />
                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr( home_url() ); ?>" />
                </form>
            </div>
        </div>
    </div>

<?php 
} else { // Si ya inició sesión, muestra un mensaje de bienvenida y un enlace para cerrar sesión 
?>
    <p>¡Bienvenido, <?php echo wp_get_current_user()->user_login; ?>!</p>
    <a href="<?php echo wp_logout_url( home_url() ); ?>">Cerrar sesión</a>
<?php } ?>
