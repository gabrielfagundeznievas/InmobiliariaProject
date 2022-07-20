<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasControllers {
    public static function index(Router $router) {

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros', []);
    }

    public static function propiedades(Router $router) {

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {

        $id = validarORedireccionar('/propiedades');
        // Buscar la propiedad por su ID
    
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog(Router $router) {
        $router->render('paginas/blog', []);
    }

    public static function entrada(Router $router) {
        $router->render('paginas/entrada', []);
    }

    public static function contacto(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $mensaje = null;
            $respuestas = $_POST['contacto'];
            

            // Crear una instancia de PHPMailer
            $mail = new PHPMailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '3db945e8ed7c8e';
            $mail->Password = 'e7a7faf1699851';
            $mail->SMTPSecure = 'tls';

            // Configurar el contenido del mail
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo Mensaje';

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            // Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p> Tienes un nuevo mensaje </p>';
            $contenido .= '<p> Nombre: ' . $respuestas['nombre'] . '</p>';

            // Enviar de forma condicional email o telefone
            if($respuestas['contacto'] === 'telefono'){
                $contenido .= '<p>Eligió ser contactado por teléfono<p>';
                $contenido .= '<p> Teléfono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p> Fecha de contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p> Hora de contacto: ' . $respuestas['hora'] . '</p>';
            } else {
                $contenido .= '<p>Eligió ser contactado por email<p>';
                $contenido .= '<p> E-mail: ' . $respuestas['email'] . '</p>';
            }

            $contenido .= '<p> Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p> Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p> Precio o presupuesto: $' . $respuestas['precio'] . '</p>';
            $contenido .= '<p> Prefiere ser contactado por: ' . $respuestas['contacto'] . '</p>';
            
            $contenido .= '<html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // Enviar el email
            if($mail->send()){
                $mensaje = "Mensaje Enviado Correctamente";
            } else {
                $mensaje = "El Mensaje no se Pudo Enviar";
            }

        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }


}