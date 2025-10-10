<?php
// Router principal de la API
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener la ruta
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($scriptName, '', $requestUri);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

// Separar ruta y parámetros
$segments = explode('/', $path);
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Rutas de la API
    if (empty($segments[0])) {
        Response::json(['message' => 'API Adopta un Abuelo Colombia', 'version' => '1.0']);
    }

    // Health check
    if ($segments[0] === 'health') {
        Response::success([
            'status' => 'OK',
            'message' => 'API funcionando correctamente',
            'timestamp' => date('c')
        ]);
    }

    // Rutas de abuelos
    if ($segments[0] === 'abuelos') {
        require_once __DIR__ . '/controllers/AbuelosController.php';
        $controller = new AbuelosController();

        if (!isset($segments[1])) {
            // GET /api/abuelos
            if ($method === 'GET') {
                $controller->index();
            }
        } elseif ($segments[1] === 'cumpleanos' && $segments[2] === 'mes-actual') {
            // GET /api/abuelos/cumpleanos/mes-actual
            $controller->cumpleanosMesActual();
        } elseif ($segments[1] === 'buscar' && isset($segments[2])) {
            // GET /api/abuelos/buscar/{termino}
            $controller->buscar($segments[2]);
        } elseif (is_numeric($segments[1])) {
            // GET /api/abuelos/{id}
            $controller->show($segments[1]);
        }
        exit;
    }

    // Rutas de donaciones
    if ($segments[0] === 'donaciones') {
        require_once __DIR__ . '/controllers/DonacionesController.php';
        $controller = new DonacionesController();

        if ($method === 'POST') {
            if ($segments[1] === 'personas') {
                // POST /api/donaciones/personas
                $controller->createPersonal();
            } elseif ($segments[1] === 'empresas') {
                // POST /api/donaciones/empresas
                $controller->createEmpresarial();
            }
        }
        exit;
    }

    // Rutas de contacto
    if ($segments[0] === 'contacto') {
        require_once __DIR__ . '/controllers/ContactoController.php';
        $controller = new ContactoController();

        if ($method === 'POST') {
            // POST /api/contacto
            $controller->create();
        }
        exit;
    }

    // Rutas de PayU
    if ($segments[0] === 'payu') {
        require_once __DIR__ . '/controllers/PayUController.php';
        $controller = new PayUController();

        if ($method === 'POST') {
            if ($segments[1] === 'create-payment') {
                // POST /api/payu/create-payment
                $controller->createPayment();
            } elseif ($segments[1] === 'response') {
                // POST /api/payu/response
                $controller->response();
            } elseif ($segments[1] === 'confirmation') {
                // POST /api/payu/confirmation
                $controller->confirmation();
            }
        } elseif ($method === 'GET' && $segments[1] === 'status' && isset($segments[2])) {
            // GET /api/payu/status/{referenceCode}
            $controller->getStatus($segments[2]);
        }
        exit;
    }

    // Ruta no encontrada
    Response::notFound('Ruta no encontrada');

} catch (Exception $e) {
    error_log("Error en router: " . $e->getMessage());
    Response::serverError('Error interno del servidor');
}
?>
