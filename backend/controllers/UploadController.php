<?php
/**
 * Controlador de Upload de Archivos
 * Maneja la subida y optimización de imágenes
 */

require_once __DIR__ . '/../helpers/ImageUploader.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/AuthController.php';

// Asegurar que Logger esté disponible para ImageUploader
if (!class_exists('Logger')) {
    require_once __DIR__ . '/../helpers/Logger.php';
}

class UploadController {
    private $uploader;
    private $user;
    
    public function __construct() {
        // Asegurar headers JSON desde el inicio y limpiar cualquier output previo
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Requiere autenticación
            $this->user = AuthController::requireAuth();
            $this->uploader = new ImageUploader();
        } catch (Exception $e) {
            // Limpiar cualquier output previo
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            
            Logger::error("UploadController construct error", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            Response::error('Error de autenticación: ' . $e->getMessage(), 401);
        } catch (Error $e) {
            // Limpiar cualquier output previo
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            error_log("UploadController fatal error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            Response::error('Error fatal de autenticación', 500);
        }
    }
    
    /**
     * POST /api/upload/image
     */
    public function uploadImage() {
        try {
            // Limpiar cualquier output previo y asegurar JSON
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            
            Logger::debug("uploadImage: Iniciando upload");
            
            // Verificar que se envió un archivo
            if (!isset($_FILES['image'])) {
                Logger::error("uploadImage: No se encontró el archivo 'image'");
                Response::error('No se envió ningún archivo', 400);
                return;
            }
            
            $file = $_FILES['image'];
            
            Logger::debug("uploadImage: Archivo recibido", [
                'name' => $file['name'],
                'type' => $file['type'],
                'size' => $file['size']
            ]);
            
            // Subir y optimizar
            $result = $this->uploader->upload($file);
            
            if ($result['success']) {
                Logger::log("Imagen subida exitosamente", [
                    'user_id' => $this->user['id'],
                    'filename' => $result['filename'],
                    'path' => $result['path']
                ]);
                
                Response::success([
                    'url' => $result['path'],
                    'filename' => $result['filename'],
                    'size' => $result['size']
                ], 'Imagen subida y optimizada exitosamente');
                return;
            } else {
                Logger::error("uploadImage: Error", ['error' => $result['error']]);
                Response::error($result['error'], 400);
                return;
            }
            
        } catch (Exception $e) {
            Logger::error("uploadImage: Excepción", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Response::error('Error al subir la imagen: ' . $e->getMessage(), 500);
            return;
        }
    }
    
    /**
     * DELETE /api/upload/image/{filename}
     */
    public function deleteImage($filename) {
        try {
            header('Content-Type: application/json');
            
            // Solo admin puede eliminar
            if ($this->user['rol'] !== 'admin' && $this->user['rol'] !== 'super_admin') {
                Response::error('Permisos insuficientes', 403);
                return;
            }
            
            $result = $this->uploader->delete($filename);
            
            if ($result['success']) {
                Response::success(null, 'Imagen eliminada');
                return;
            } else {
                Response::error($result['error'], 400);
                return;
            }
            
        } catch (Exception $e) {
            Logger::error("deleteImage: Excepción", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            Response::error('Error al eliminar imagen: ' . $e->getMessage(), 500);
            return;
        }
    }
}
?>

