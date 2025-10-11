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
                $controller->createPayment();
            } elseif ($segments[1] === 'response') {
                $controller->response();
            } elseif ($segments[1] === 'confirmation') {
                $controller->confirmation();
            }
        } elseif ($method === 'GET' && $segments[1] === 'status' && isset($segments[2])) {
            $controller->getStatus($segments[2]);
        }
        exit;
    }

    // Rutas de autenticación
    if ($segments[0] === 'auth') {
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();

        if ($method === 'POST' && $segments[1] === 'login') {
            // POST /api/auth/login
            $controller->login();
        } elseif ($method === 'POST' && $segments[1] === 'logout') {
            // POST /api/auth/logout
            $controller->logout();
        } elseif ($method === 'GET' && $segments[1] === 'verify') {
            // GET /api/auth/verify
            $controller->verify();
        } elseif ($method === 'GET' && $segments[1] === 'me') {
            // GET /api/auth/me
            $controller->me();
        } elseif ($method === 'POST' && $segments[1] === 'change-password') {
            // POST /api/auth/change-password
            $controller->changePassword();
        }
        exit;
    }

    // Rutas de administración
    if ($segments[0] === 'admin') {
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();

        // Dashboard
        if ($method === 'GET' && $segments[1] === 'dashboard') {
            // GET /api/admin/dashboard
            $controller->getDashboard();
        }
        
        // Abuelos
        elseif ($segments[1] === 'abuelos') {
            if ($method === 'GET' && !isset($segments[2])) {
                // GET /api/admin/abuelos
                $controller->getAbuelos();
            } elseif ($method === 'POST') {
                // POST /api/admin/abuelos
                $controller->createAbuelo();
            } elseif ($method === 'PUT' && isset($segments[2])) {
                // PUT /api/admin/abuelos/{id}
                $controller->updateAbuelo($segments[2]);
            } elseif ($method === 'DELETE' && isset($segments[2])) {
                // DELETE /api/admin/abuelos/{id}
                $controller->deleteAbuelo($segments[2]);
            }
        }
        
        // Donaciones
        elseif ($segments[1] === 'donaciones') {
            if ($method === 'GET') {
                // GET /api/admin/donaciones
                $controller->getDonaciones();
            } elseif ($method === 'PUT' && isset($segments[2]) && isset($segments[3])) {
                // PUT /api/admin/donaciones/{tipo}/{id}
                $controller->updateDonacion($segments[2], $segments[3]);
            }
        }
        
        // Mensajes
        elseif ($segments[1] === 'mensajes') {
            if ($method === 'GET' && !isset($segments[2])) {
                // GET /api/admin/mensajes
                $controller->getMensajes();
            } elseif ($method === 'PUT' && isset($segments[2]) && $segments[3] === 'marcar-leido') {
                // PUT /api/admin/mensajes/{id}/marcar-leido
                $controller->marcarMensajeLeido($segments[2]);
            } elseif ($method === 'DELETE' && isset($segments[2])) {
                // DELETE /api/admin/mensajes/{id}
                $controller->deleteMensaje($segments[2]);
            }
        }
        
        // Logs
        elseif ($method === 'GET' && $segments[1] === 'logs') {
            // GET /api/admin/logs
            $controller->getLogs();
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
