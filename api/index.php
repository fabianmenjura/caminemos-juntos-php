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
            $controller->create();
        }
        exit;
    }

    // Rutas de voluntarios
    if ($segments[0] === 'voluntarios') {
        require_once __DIR__ . '/controllers/VoluntariosController.php';
        $controller = new VoluntariosController();

        if ($method === 'POST') {
            // POST /api/voluntarios
            $controller->create();
        }
        exit;
    }

    // Rutas de testimonios
    if ($segments[0] === 'testimonios') {
        require_once __DIR__ . '/controllers/TestimoniosController.php';
        $controller = new TestimoniosController();

        if ($method === 'GET') {
            // GET /api/testimonios
            $controller->index();
        } elseif ($method === 'POST') {
            // POST /api/testimonios
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

        if ($method === 'POST' && isset($segments[1])) {
            if ($segments[1] === 'login') {
                $controller->login();
            } elseif ($segments[1] === 'logout') {
                $controller->logout();
            }
        } elseif ($method === 'GET' && isset($segments[1])) {
            if ($segments[1] === 'verify') {
                $controller->verify();
            }
        }
        exit;
    }

    // Rutas de upload
    if ($segments[0] === 'upload') {
        require_once __DIR__ . '/controllers/UploadController.php';
        $controller = new UploadController();

        if ($method === 'POST' && $segments[1] === 'image') {
            // POST /api/upload/image
            $controller->uploadImage();
        } elseif ($method === 'DELETE' && $segments[1] === 'image' && isset($segments[2])) {
            // DELETE /api/upload/image/{filename}
            $controller->deleteImage($segments[2]);
        }
        exit;
    }

    // Rutas de administración
    if ($segments[0] === 'admin') {
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();

        if ($method === 'GET' && isset($segments[1])) {
            if ($segments[1] === 'dashboard') {
                $controller->getDashboard();
            } elseif ($segments[1] === 'abuelos' && !isset($segments[2])) {
                $controller->getAbuelos();
            } elseif ($segments[1] === 'donaciones') {
                $controller->getDonaciones();
            } elseif ($segments[1] === 'mensajes') {
                $controller->getMensajes();
            } elseif ($segments[1] === 'voluntarios') {
                $controller->getVoluntarios();
            } elseif ($segments[1] === 'testimonios') {
                $controller->getTestimonios();
            }
        } elseif ($method === 'POST' && $segments[1] === 'abuelos') {
            $controller->createAbuelo();
        } elseif ($method === 'PUT' && isset($segments[1]) && isset($segments[2])) {
            if ($segments[1] === 'abuelos') {
                $controller->updateAbuelo($segments[2]);
            } elseif ($segments[1] === 'donaciones' && isset($segments[3])) {
                // PUT /api/admin/donaciones/{tipo}/{id}
                $controller->updateDonacionEstado($segments[2], $segments[3]);
            } elseif ($segments[1] === 'mensajes' && isset($segments[3]) && $segments[3] === 'marcar-leido') {
                $controller->marcarMensajeLeido($segments[2]);
            } elseif ($segments[1] === 'voluntarios') {
                $controller->updateVoluntario($segments[2]);
            } elseif ($segments[1] === 'testimonios') {
                $controller->updateTestimonio($segments[2]);
            }
        } elseif ($method === 'DELETE' && isset($segments[1]) && isset($segments[2])) {
            if ($segments[1] === 'abuelos') {
                $controller->deleteAbuelo($segments[2]);
            } elseif ($segments[1] === 'mensajes') {
                $controller->deleteMensaje($segments[2]);
            } elseif ($segments[1] === 'voluntarios') {
                $controller->deleteVoluntario($segments[2]);
            } elseif ($segments[1] === 'testimonios') {
                $controller->deleteTestimonio($segments[2]);
            }
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
