<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas() {
        session_start();

        $auth = $_SESSION['login'] ?? null;

        // Arreglos de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];
        
        // $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'];
        $urlActual = explode('?', $_SERVER['REQUEST_URI'], 2) ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual[0]] ?? null ;
        } else {
            $fn = $this->rutasPOST[$urlActual[0]] ?? null ;   
        }

        // Proteger las rutas
        if(in_array($urlActual[0], $rutas_protegidas) && !$auth){
            header('Location: /');
        }
        
        // debuguear($_SERVER);

        if($fn) {
            // La URL existe y hay una función asociada
            call_user_func($fn, $this);

        } else {
            echo "Pagina no encontrada";
        }
    }

    //Muestra una vista
    public function render($view, $datos = []) {

        foreach($datos as $key => $value) {
            $$key = $value;
        }

        ob_start(); // Almacenamiento en memoria durante un momento...
        include __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpiar el Buffer

        include __DIR__ . "/views/layout.php";
    }
}
