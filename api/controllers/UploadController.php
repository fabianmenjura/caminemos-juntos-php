<?php
/**
 * Controlador de Upload de Archivos
 * Maneja la subida y optimización de imágenes
 */

require_once __DIR__ . '/../helpers/ImageUploader.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/AuthController.php';

class UploadController {
    private $uploader;
    private $user;
    
    public function __construct() {
        // Requiere autenticación
        $this->user = AuthController::requireAuth();
        $this->uploader = new ImageUploader();
    }
    
    /**
     * POST /api/upload/image
     */
    public function uploadImage() {
        try {
            Logger::debug("uploadImage: Iniciando upload");
            
            // Verificar que se envió un archivo
            if (!isset($_FILES['image'])) {
                Logger::error("uploadImage: No se encontró el archivo 'image'");
                return Response::error('No se envió ningún archivo', null, 400);
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
                
                return Response::success([
                    'url' => $result['path'],
                    'filename' => $result['filename'],
                    'size' => $result['size']
                ], 'Imagen subida y optimizada exitosamente');
            } else {
                Logger::error("uploadImage: Error", ['error' => $result['error']]);
                return Response::error($result['error'], null, 400);
            }
            
        } catch (Exception $e) {
            Logger::error("uploadImage: Excepción", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return Response::error('Error al subir la imagen: ' . $e->getMessage(), null, 500);
        }
    }
    
    /**
     * DELETE /api/upload/image/{filename}
     */
    public function deleteImage($filename) {
        try {
            // Solo admin puede eliminar
            if ($this->user['rol'] !== 'admin' && $this->user['rol'] !== 'super_admin') {
                return Response::error('Permisos insuficientes', null, 403);
            }
            
            $result = $this->uploader->delete($filename);
            
            if ($result['success']) {
                return Response::success(null, 'Imagen eliminada');
            } else {
                return Response::error($result['error'], null, 400);
            }
            
        } catch (Exception $e) {
            return Response::error('Error al eliminar imagen', null, 500);
        }
    }
}
?>

