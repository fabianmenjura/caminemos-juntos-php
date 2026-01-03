<?php
// Router principal de la API
// Capturar cualquier salida inesperada
ob_start();

// Desactivar mostrar errores como HTML, solo log
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Establecer zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// Establecer headers JSON por defecto
header('Content-Type: application/json; charset=utf-8');

// Asegurar que el header Authorization esté disponible
if (!isset($_SERVER['HTTP_AUTHORIZATION']) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

// También desde getallheaders() si está disponible
if (!isset($_SERVER['HTTP_AUTHORIZATION']) && function_exists('getallheaders')) {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $headers['authorization'];
    }
}

// Limpiar cualquier salida previa
ob_clean();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Auth-Token');

// Manejar OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener la ruta
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Si viene como parámetro GET (desde .htaccess con ?route=)
if (isset($_GET['route']) && !empty($_GET['route'])) {
    $path = $_GET['route'];
} else {
    // Extraer la ruta de la URI
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Si la ruta viene de /api/, extraer la parte después de /api/
    if (strpos($path, '/api/') !== false) {
        $path = substr($path, strpos($path, '/api/') + 5);
    } elseif (strpos($path, '/backend/index.php/') !== false) {
        // Si viene redirigido desde .htaccess como backend/index.php/...
        $path = substr($path, strpos($path, '/backend/index.php/') + 19);
    } elseif (strpos($path, 'index.php/') !== false) {
        // Si viene como index.php/...
        $path = substr($path, strpos($path, 'index.php/') + 10);
    } else {
        // Intentar extraer desde el script name
        $basePath = dirname($scriptName);
        if ($basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
    }
}

// Limpiar la ruta
$path = trim($path, '/');

// Separar ruta y parámetros
$segments = explode('/', $path);
$method = $_SERVER['REQUEST_METHOD'];

// Debug (solo en desarrollo)
if (defined('APP_DEBUG') && APP_DEBUG === 'true') {
    error_log("API Request Debug:");
    error_log("  REQUEST_URI: $requestUri");
    error_log("  SCRIPT_NAME: $scriptName");
    error_log("  Path extracted: $path");
    error_log("  Segments: " . json_encode($segments));
}

try {
    // Rutas de la API
    if (empty($segments[0]) || $segments[0] === 'index.php') {
        Response::json([
            'message' => 'API Caminemos Juntos Colombia', 
            'version' => '1.0',
            'debug' => [
                'request_uri' => $requestUri,
                'script_name' => $scriptName,
                'path' => $path,
                'segments' => $segments
            ]
        ]);
    }

    // Health check
    if ($segments[0] === 'health') {
        Response::success([
            'status' => 'OK',
            'message' => 'API funcionando correctamente',
            'timestamp' => date('c'),
            'debug' => [
                'request_uri' => $requestUri,
                'script_name' => $scriptName,
                'path' => $path,
                'segments' => $segments
            ]
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

    // Rutas de hero slider
    if ($segments[0] === 'hero-slider') {
        require_once __DIR__ . '/controllers/HeroSliderController.php';
        $controller = new HeroSliderController();

        if ($method === 'GET') {
            // GET /api/hero-slider
            $controller->index();
        }
        exit;
    }

    // Rutas de patrocinadores
    if ($segments[0] === 'patrocinadores') {
        require_once __DIR__ . '/controllers/PatrocinadoresController.php';
        $controller = new PatrocinadoresController();

        if ($method === 'GET') {
            // GET /api/patrocinadores
            $controller->index();
        }
        exit;
    }

    // Rutas de galería (público)
    if ($segments[0] === 'galeria') {
        require_once __DIR__ . '/controllers/GaleriaController.php';
        $controller = new GaleriaController();

        if ($method === 'GET') {
            // GET /api/galeria
            $controller->index();
        }
        exit;
    }

    // Rutas de mensajes de cumpleaños
    if ($segments[0] === 'cumpleanos') {
        require_once __DIR__ . '/controllers/MensajesCumpleanosController.php';
        $controller = new MensajesCumpleanosController();

        if ($method === 'POST') {
            // POST /api/cumpleanos (enviar mensaje)
            $controller->create();
        } elseif ($method === 'GET' && isset($segments[1]) && $segments[1] === 'proximos') {
            // GET /api/cumpleanos/proximos
            $controller->proximosCumpleanos();
        }
        exit;
    }

    // Rutas de categorías de voluntariado (público)
    if ($segments[0] === 'categorias-voluntariado') {
        require_once __DIR__ . '/controllers/CategoriasVoluntariadoController.php';
        $controller = new CategoriasVoluntariadoController();

        if ($method === 'GET') {
            // GET /api/categorias-voluntariado
            $controller->index();
        }
        exit;
    }

    // Rutas de "Acerca de" (público)
    if ($segments[0] === 'acerca-de') {
        require_once __DIR__ . '/controllers/AcercaDeController.php';
        $controller = new AcercaDeController();

        if ($method === 'GET') {
            // GET /api/acerca-de
            $controller->index();
        }
        exit;
    }

    // Rutas de "Trabajo Social" (público)
    if ($segments[0] === 'trabajo-social') {
        require_once __DIR__ . '/controllers/TrabajoSocialController.php';
        $controller = new TrabajoSocialController();

        if ($method === 'GET') {
            // GET /api/trabajo-social
            $controller->index();
        }
        exit;
    }

    // Rutas de "Información Institucional" (público)
    if ($segments[0] === 'informacion-institucional') {
        require_once __DIR__ . '/controllers/InformacionInstitucionalController.php';
        $controller = new InformacionInstitucionalController();

        if ($method === 'GET') {
            // GET /api/informacion-institucional
            $controller->index();
        }
        exit;
    }

    // Rutas de configuración general (público)
    if ($segments[0] === 'configuracion') {
        require_once __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();

        if ($method === 'GET') {
            // GET /api/configuracion
            $controller->index();
        }
        exit;
    }

    // Rutas de QR Donaciones (público)
    if ($segments[0] === 'qr-donaciones') {
        require_once __DIR__ . '/controllers/QRDonacionesController.php';
        $controller = new QRDonacionesController(false); // No requiere auth

        if ($method === 'GET') {
            // GET /api/qr-donaciones
            $controller->index();
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
        try {
            require_once __DIR__ . '/controllers/UploadController.php';
            $controller = new UploadController();

            if ($method === 'POST' && isset($segments[1]) && $segments[1] === 'image') {
                // POST /api/upload/image
                $controller->uploadImage();
            } elseif ($method === 'DELETE' && isset($segments[1]) && $segments[1] === 'image' && isset($segments[2])) {
                // DELETE /api/upload/image/{filename}
                $controller->deleteImage($segments[2]);
            } else {
                Response::error('Ruta no válida', 404);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            error_log("Upload route error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            Response::error('Error al procesar la solicitud de upload: ' . $e->getMessage(), 500);
        } catch (Error $e) {
            header('Content-Type: application/json');
            error_log("Upload route fatal error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            Response::error('Error fatal al procesar la solicitud de upload', 500);
        }
        exit;
    }

    // Rutas de notificaciones (admin)
    if ($segments[0] === 'admin' && isset($segments[1]) && $segments[1] === 'notificaciones') {
        require_once __DIR__ . '/controllers/NotificacionesController.php';
        $notifController = new NotificacionesController();

        if ($method === 'GET') {
            if (isset($segments[2]) && $segments[2] === 'count') {
                // GET /api/admin/notificaciones/count
                $notifController->getCount();
            } else {
                // GET /api/admin/notificaciones
                $notifController->index();
            }
        } elseif ($method === 'PUT') {
            if (isset($segments[2]) && $segments[2] === 'mark-all-read') {
                // PUT /api/admin/notificaciones/mark-all-read
                $notifController->markAllAsRead();
            } elseif (isset($segments[2])) {
                // PUT /api/admin/notificaciones/{id}
                $notifController->markAsRead($segments[2]);
            }
        } elseif ($method === 'DELETE' && isset($segments[2])) {
            // DELETE /api/admin/notificaciones/{id}
            $notifController->delete($segments[2]);
        } elseif ($method === 'POST' && isset($segments[2]) && $segments[2] === 'generar-cumpleanos') {
            // POST /api/admin/notificaciones/generar-cumpleanos
            $notifController->generarCumpleanos();
        }
        exit;
    }

    // Rutas de administración QR Donaciones (separado para evitar instanciar AdminController)
    if ($segments[0] === 'admin' && isset($segments[1]) && $segments[1] === 'qr-donaciones') {
        require_once __DIR__ . '/controllers/QRDonacionesController.php';
        
        try {
            $qrController = new QRDonacionesController(true); // Requiere auth

            if ($method === 'GET') {
                $qrController->getAll();
            } elseif ($method === 'POST') {
                $qrController->create();
            } elseif ($method === 'PUT' && isset($segments[2])) {
                $qrController->update($segments[2]);
            } elseif ($method === 'DELETE' && isset($segments[2])) {
                $qrController->delete($segments[2]);
            }
        } catch (Exception $e) {
            error_log("QR Route Error: " . $e->getMessage());
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No autorizado', 'message' => $e->getMessage()]);
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
            } elseif ($segments[1] === 'hero-slider') {
                $controller->getHeroSlider();
            } elseif ($segments[1] === 'patrocinadores') {
                $controller->getPatrocinadores();
            } elseif ($segments[1] === 'mensajes-cumpleanos') {
                $controller->getMensajesCumpleanos();
            } elseif ($segments[1] === 'categorias-voluntariado') {
                $controller->getCategoriasVoluntariado();
            } elseif ($segments[1] === 'acerca-de') {
                $controller->getAcercaDe();
            } elseif ($segments[1] === 'trabajo-social') {
                $controller->getTrabajoSocial();
            } elseif ($segments[1] === 'informacion-institucional') {
                $controller->getInformacionInstitucional();
            } elseif ($segments[1] === 'galeria') {
                $controller->getGaleria();
            } elseif ($segments[1] === 'configuracion') {
                $controller->getConfiguracion();
            }
        } elseif ($method === 'POST' && isset($segments[1])) {
            if ($segments[1] === 'abuelos') {
                $controller->createAbuelo();
            } elseif ($segments[1] === 'hero-slider') {
                $controller->createHeroSlide();
            } elseif ($segments[1] === 'patrocinadores') {
                $controller->createPatrocinador();
            } elseif ($segments[1] === 'galeria') {
                $controller->createImagenGaleria();
            } elseif ($segments[1] === 'categorias-voluntariado') {
                $controller->createCategoriaVoluntariado();
            } elseif ($segments[1] === 'acerca-de' && isset($segments[2])) {
                if ($segments[2] === 'contenido') {
                    $controller->updateAcercaDeContenido();
                } elseif ($segments[2] === 'caracteristicas') {
                    $controller->createCaracteristicaAcercaDe();
                }
            } elseif ($segments[1] === 'trabajo-social' && isset($segments[2]) && $segments[2] === 'contenido') {
                $controller->updateTrabajoSocialContenido();
            } elseif ($segments[1] === 'informacion-institucional' && isset($segments[2]) && $segments[2] === 'contenido') {
                $controller->updateInformacionInstitucionalContenido();
            } elseif ($segments[1] === 'configuracion') {
                if (isset($segments[2]) && $segments[2] === 'redes-sociales') {
                    $controller->createRedSocial();
                } else {
                    $controller->updateConfiguracion();
                }
            }
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
            } elseif ($segments[1] === 'hero-slider') {
                $controller->updateHeroSlide($segments[2]);
            } elseif ($segments[1] === 'patrocinadores') {
                $controller->updatePatrocinador($segments[2]);
            } elseif ($segments[1] === 'galeria') {
                $controller->updateImagenGaleria($segments[2]);
            } elseif ($segments[1] === 'mensajes-cumpleanos' && isset($segments[3]) && $segments[3] === 'aprobar') {
                // PUT /api/admin/mensajes-cumpleanos/{id}/aprobar
                $controller->aprobarMensajeCumpleanos($segments[2]);
            } elseif ($segments[1] === 'categorias-voluntariado') {
                $controller->updateCategoriaVoluntariado($segments[2]);
            } elseif ($segments[1] === 'acerca-de' && $segments[2] === 'caracteristicas' && isset($segments[3])) {
                $controller->updateCaracteristicaAcercaDe($segments[3]);
            } elseif ($segments[1] === 'configuracion' && $segments[2] === 'redes-sociales' && isset($segments[3])) {
                $controller->updateRedSocial($segments[3]);
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
            } elseif ($segments[1] === 'hero-slider') {
                $controller->deleteHeroSlide($segments[2]);
            } elseif ($segments[1] === 'patrocinadores') {
                $controller->deletePatrocinador($segments[2]);
            } elseif ($segments[1] === 'galeria') {
                $controller->deleteImagenGaleria($segments[2]);
            } elseif ($segments[1] === 'mensajes-cumpleanos') {
                $controller->deleteMensajeCumpleanos($segments[2]);
            } elseif ($segments[1] === 'categorias-voluntariado') {
                $controller->deleteCategoriaVoluntariado($segments[2]);
            } elseif ($segments[1] === 'acerca-de' && $segments[2] === 'caracteristicas' && isset($segments[3])) {
                $controller->deleteCaracteristicaAcercaDe($segments[3]);
            } elseif ($segments[1] === 'configuracion' && $segments[2] === 'redes-sociales' && isset($segments[3])) {
                $controller->deleteRedSocial($segments[3]);
            }
        }
        exit;
    }

    // Ruta no encontrada
    Response::notFound('Ruta no encontrada');

} catch (Exception $e) {
    error_log("Error en router: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // En modo debug, mostrar más información
    if (defined('APP_DEBUG') && APP_DEBUG === 'true') {
        Response::json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString())
        ], 500);
    } else {
        Response::serverError('Error interno del servidor');
    }
}
?>
