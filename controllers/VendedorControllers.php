<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;
// use Intervention\Image\ImageManagerStatic as Image;

class VendedorControllers {
    public static function crear(Router $router) {

        $vendedor = new Vendedor;

        // Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();

        // Ejecutar el código después de que el usuario envia el formulario
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);

            // Validar que no haya campos vacios
            $errores = $vendedor->validar();

            // No hay errores
            if(empty($errores)){
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);

    }

    public static function actualizar(Router $router) {
       
        // Obtener el arreglo del vendedor
        $id = validarORedireccionar('/admin');

        // Obtener datos del vendedor a actualizar
        $vendedor = Vendedor::find($id);

        // Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();

        // Ejecutar el código después de que el usuario envia el formulario
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los valores
            $args = $_POST['vendedor'];

            // Sincronizar objeto en memoria con lo que el usuario escribió
            $vendedor->sincronizar($args);

            // Validaciones
            $errores = $vendedor->validar();

            if(empty($errores)){
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);

    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Valida el ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            
            if($id) {
                // Valida el tipo a eliminar
                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }
}