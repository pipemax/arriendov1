<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <body>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
        <!-- preloader area start -->
        <div id="preloader">
            <div class="loader"></div>
        </div>
        <!-- preloader area end -->
        <!-- login area start -->
        <div class="login-area login-bg">
            <div class="container">
                <div class="login-box">
                    <form id="form_admin" method="post">
                        <div class="login-form-head">
                            <h4>Área de administración de Constru OK</h4>
                            <p>Inicia sesión para comenzar</p>
                        </div>
                        <div class="login-form-body">
                            <div class="form-gp">
                                <label for="rut_admin">Rut Ej: 18385648-1</label>
                                <input type="text" id="rut_admin" name="rut_admin" maxlength="10">
                                <i class="ti-user"></i>
                            </div>
                            <div class="form-gp">
                                <label for="password_admin">Contraseña</label>
                                <input type="password" id="password_admin" name="password_admin" maxlength="20">
                                <i class="ti-lock"></i>
                            </div>
                            <div class="row mb-4 rmber-area">
                                <div class="col-6"></div>
                                <div class="col-6 text-right">
                                    <a href="#">¿Olvidó su contraseña?</a>
                                </div>
                            </div>
                            <div class="submit-btn-area">
                                <button type="submit">Ingresar <i class="ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- login area end -->
        