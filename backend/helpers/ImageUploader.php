<?php
/**
 * Utilidad para subir y optimizar imágenes
 * Convierte y comprime imágenes automáticamente
 */

class ImageUploader {
    private $uploadDir;
    private $maxWidth = 800;  // Ancho máximo
    private $maxHeight = 800; // Alto máximo
    private $quality = 85;    // Calidad JPEG (0-100)
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    public function __construct($uploadDir = null) {
        $this->uploadDir = $uploadDir ?? __DIR__ . '/../../assets/images/';
        
        // Crear directorio si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Subir y optimizar imagen
     */
    public function upload($fileInput) {
        try {
            // Validar que se subió un archivo
            if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No se subió ningún archivo o hubo un error');
            }
            
            // Validar tamaño
            if ($fileInput['size'] > $this->maxFileSize) {
                throw new Exception('El archivo es demasiado grande (máximo 5MB)');
            }
            
            // Validar tipo MIME
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileInput['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG, GIF, WEBP');
            }
            
            // Generar nombre único
            $extension = 'jpg'; // Siempre convertir a JPG
            $filename = 'abuelo-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
            $filepath = $this->uploadDir . $filename;
            
            // Cargar imagen según tipo
            $image = $this->loadImage($fileInput['tmp_name'], $mimeType);
            
            if (!$image) {
                throw new Exception('No se pudo procesar la imagen');
            }
            
            // Optimizar (redimensionar si es necesario)
            $optimized = $this->optimizeImage($image);
            
            // Guardar como JPEG optimizado
            imagejpeg($optimized, $filepath, $this->quality);
            
            // Liberar memoria
            imagedestroy($image);
            imagedestroy($optimized);
            
            // Establecer permisos
            chmod($filepath, 0644);
            
            // Retornar ruta relativa
            $relativePath = 'assets/images/' . $filename;
            
            Logger::log("Imagen subida y optimizada", [
                'filename' => $filename,
                'original_size' => $fileInput['size'],
                'optimized_size' => filesize($filepath),
                'path' => $relativePath
            ]);
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $relativePath,
                'url' => $relativePath,
                'size' => filesize($filepath)
            ];
            
        } catch (Exception $e) {
            Logger::error("Error al subir imagen", [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cargar imagen según tipo
     */
    private function loadImage($filepath, $mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($filepath);
            
            case 'image/png':
                return imagecreatefrompng($filepath);
            
            case 'image/gif':
                return imagecreatefromgif($filepath);
            
            case 'image/webp':
                return imagecreatefromwebp($filepath);
            
            default:
                return false;
        }
    }
    
    /**
     * Optimizar imagen (redimensionar y comprimir)
     */
    private function optimizeImage($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Calcular nuevas dimensiones manteniendo proporción
        $newWidth = $width;
        $newHeight = $height;
        
        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $ratio = $width / $height;
            
            if ($width > $height) {
                $newWidth = $this->maxWidth;
                $newHeight = $this->maxWidth / $ratio;
            } else {
                $newHeight = $this->maxHeight;
                $newWidth = $this->maxHeight * $ratio;
            }
        }
        
        // Crear imagen redimensionada
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Mantener transparencia si es PNG
        imagealphablending($optimized, false);
        imagesavealpha($optimized, true);
        
        // Redimensionar con alta calidad
        imagecopyresampled(
            $optimized, $image,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );
        
        return $optimized;
    }
    
    /**
     * Eliminar imagen
     */
    public function delete($filename) {
        try {
            $filepath = $this->uploadDir . basename($filename);
            
            if (file_exists($filepath)) {
                unlink($filepath);
                Logger::log("Imagen eliminada", ['filename' => $filename]);
                return ['success' => true];
            }
            
            return ['success' => false, 'error' => 'Archivo no encontrado'];
        } catch (Exception $e) {
            Logger::error("Error al eliminar imagen", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>

