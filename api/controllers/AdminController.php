<?php
/**
 * Controlador del Panel de Administración
 * CRUD de abuelos, donaciones, mensajes, etc.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/AuthController.php';

class AdminController {
    private $db;
    private $user;
    
    public function __construct() {
        $this->db = Database::getInstance();
        // Verificar autenticación en todos los métodos
        $this->user = AuthController::requireAuth();
    }
    
    // ============================================
    // DASHBOARD Y ESTADÍSTICAS
    // ============================================
    
    /**
     * GET /api/admin/dashboard
     */
    public function getDashboard() {
        try {
            $stats = [
                'abuelos' => [
                    'total' => $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible'")['count'],
                    'hombres' => $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible' AND genero = 'M'")['count'],
                    'mujeres' => $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible' AND genero = 'F'")['count'],
                ],
                'donaciones' => [
                    'total_personas' => $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_personas")['count'],
                    'total_empresas' => $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_empresas")['count'],
                    'monto_total_personas' => $this->db->fetchOne("SELECT COALESCE(SUM(monto), 0) as total FROM donaciones_personas WHERE estado = 'completada'")['total'],
                    'monto_total_empresas' => $this->db->fetchOne("SELECT COALESCE(SUM(monto), 0) as total FROM donaciones_empresas WHERE estado = 'completada'")['total'],
                ],
                'mensajes' => [
                    'total' => $this->db->fetchOne("SELECT COUNT(*) as count FROM mensajes_contacto")['count'],
                    'no_leidos' => $this->db->fetchOne("SELECT COUNT(*) as count FROM mensajes_contacto WHERE leido = FALSE")['count'],
                ],
                'transacciones' => [
                    'exitosas' => $this->db->fetchOne("SELECT COUNT(*) as count FROM transacciones_payu WHERE estado_pol = '4'")['count'],
                    'pendientes' => $this->db->fetchOne("SELECT COUNT(*) as count FROM transacciones_payu WHERE estado_pol = '7'")['count'],
                    'rechazadas' => $this->db->fetchOne("SELECT COUNT(*) as count FROM transacciones_payu WHERE estado_pol = '6'")['count'],
                ]
            ];
            
            // Donaciones recientes
            $donacionesRecientes = $this->db->fetchAll(
                "SELECT 'persona' as tipo, nombre_donante as nombre, monto, fecha_donacion as fecha, estado
                 FROM donaciones_personas
                 UNION ALL
                 SELECT 'empresa' as tipo, nombre_empresa as nombre, monto, fecha_donacion as fecha, estado
                 FROM donaciones_empresas
                 ORDER BY fecha DESC
                 LIMIT 10"
            );
            
            $stats['donaciones_recientes'] = $donacionesRecientes;
            
            return Response::success($stats);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::getDashboard - " . $e->getMessage());
            return Response::error('Error al obtener estadísticas', null, 500);
        }
    }
    
    // ============================================
    // GESTIÓN DE ABUELOS
    // ============================================
    
    /**
     * GET /api/admin/abuelos
     */
    public function getAbuelos() {
        try {
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $limite = isset($_GET['limite']) ? min(100, max(1, intval($_GET['limite']))) : 20;
            $estado = $_GET['estado'] ?? null;
            
            $offset = ($pagina - 1) * $limite;
            
            $where = $estado ? "WHERE estado = '$estado'" : "";
            
            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM abuelos $where"
            )['count'];
            
            $abuelos = $this->db->fetchAll(
                "SELECT * FROM abuelos $where ORDER BY created_at DESC LIMIT $limite OFFSET $offset"
            );
            
            return Response::success([
                'abuelos' => $abuelos,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'totalPaginas' => ceil($total / $limite)
            ]);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::getAbuelos - " . $e->getMessage());
            return Response::error('Error al obtener abuelos', null, 500);
        }
    }
    
    /**
     * POST /api/admin/abuelos
     */
    public function createAbuelo() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validar datos
            $errors = Validator::validateAbuelo($data);
            
            if (!empty($errors)) {
                return Response::error('Datos inválidos', $errors, 400);
            }
            
            // Insertar
            $result = $this->db->execute(
                "INSERT INTO abuelos (nombre, edad, ciudad, descripcion, genero, foto_url, fecha_nacimiento, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['nombre'],
                    $data['edad'],
                    $data['ciudad'],
                    $data['descripcion'],
                    $data['genero'] ?? 'M',
                    $data['foto_url'] ?? 'assets/images/placeholder.jpg',
                    $data['fecha_nacimiento'] ?? null,
                    $data['estado'] ?? 'disponible'
                ]
            );
            
            $newId = $this->db->lastInsertId();
            
            // Log
            $this->authService->logActivity(
                $this->user['id'],
                'CREAR_ABUELO',
                'abuelos',
                $newId,
                "Abuelo creado: {$data['nombre']}"
            );
            
            return Response::success(['id' => $newId], 'Abuelo creado exitosamente', 201);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::createAbuelo - " . $e->getMessage());
            return Response::error('Error al crear abuelo', null, 500);
        }
    }
    
    /**
     * PUT /api/admin/abuelos/{id}
     */
    public function updateAbuelo($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Verificar que existe
            $abuelo = $this->db->fetchOne("SELECT id FROM abuelos WHERE id = ?", [$id]);
            
            if (!$abuelo) {
                return Response::error('Abuelo no encontrado', null, 404);
            }
            
            // Construir query dinámicamente
            $fields = [];
            $values = [];
            
            $allowedFields = ['nombre', 'edad', 'ciudad', 'descripcion', 'genero', 'foto_url', 'fecha_nacimiento', 'estado'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return Response::error('No hay datos para actualizar', null, 400);
            }
            
            $values[] = $id;
            
            $this->db->execute(
                "UPDATE abuelos SET " . implode(', ', $fields) . " WHERE id = ?",
                $values
            );
            
            // Log
            $this->authService->logActivity(
                $this->user['id'],
                'ACTUALIZAR_ABUELO',
                'abuelos',
                $id,
                "Abuelo actualizado"
            );
            
            return Response::success(null, 'Abuelo actualizado exitosamente');
            
        } catch (Exception $e) {
            error_log("Error en AdminController::updateAbuelo - " . $e->getMessage());
            return Response::error('Error al actualizar abuelo', null, 500);
        }
    }
    
    /**
     * DELETE /api/admin/abuelos/{id}
     */
    public function deleteAbuelo($id) {
        try {
            // Verificar permisos (solo admin o super_admin)
            AuthController::requireRole('admin');
            
            // Verificar que existe
            $abuelo = $this->db->fetchOne("SELECT nombre FROM abuelos WHERE id = ?", [$id]);
            
            if (!$abuelo) {
                return Response::error('Abuelo no encontrado', null, 404);
            }
            
            // Soft delete (cambiar estado a 'eliminado')
            $this->db->execute(
                "UPDATE abuelos SET estado = 'eliminado' WHERE id = ?",
                [$id]
            );
            
            // Log
            $this->authService->logActivity(
                $this->user['id'],
                'ELIMINAR_ABUELO',
                'abuelos',
                $id,
                "Abuelo eliminado: {$abuelo['nombre']}"
            );
            
            return Response::success(null, 'Abuelo eliminado exitosamente');
            
        } catch (Exception $e) {
            error_log("Error en AdminController::deleteAbuelo - " . $e->getMessage());
            return Response::error('Error al eliminar abuelo', null, 500);
        }
    }
    
    // ============================================
    // GESTIÓN DE DONACIONES
    // ============================================
    
    /**
     * GET /api/admin/donaciones
     */
    public function getDonaciones() {
        try {
            $tipo = $_GET['tipo'] ?? 'todas'; // personas, empresas, todas
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $limite = isset($_GET['limite']) ? min(100, max(1, intval($_GET['limite']))) : 20;
            
            $offset = ($pagina - 1) * $limite;
            
            if ($tipo === 'personas') {
                $total = $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_personas")['count'];
                $donaciones = $this->db->fetchAll(
                    "SELECT *, 'persona' as tipo_donante_categoria 
                     FROM donaciones_personas 
                     ORDER BY fecha_donacion DESC 
                     LIMIT $limite OFFSET $offset"
                );
            } elseif ($tipo === 'empresas') {
                $total = $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_empresas")['count'];
                $donaciones = $this->db->fetchAll(
                    "SELECT *, 'empresa' as tipo_donante_categoria 
                     FROM donaciones_empresas 
                     ORDER BY fecha_donacion DESC 
                     LIMIT $limite OFFSET $offset"
                );
            } else {
                // Ambas
                $donaciones = $this->db->fetchAll(
                    "SELECT id, nombre_donante as nombre, monto, estado, fecha_donacion, 'persona' as tipo_categoria
                     FROM donaciones_personas
                     UNION ALL
                     SELECT id, nombre_empresa as nombre, monto, estado, fecha_donacion, 'empresa' as tipo_categoria
                     FROM donaciones_empresas
                     ORDER BY fecha_donacion DESC
                     LIMIT $limite OFFSET $offset"
                );
                
                $total = $this->db->fetchOne(
                    "SELECT (SELECT COUNT(*) FROM donaciones_personas) + (SELECT COUNT(*) FROM donaciones_empresas) as count"
                )['count'];
            }
            
            return Response::success([
                'donaciones' => $donaciones,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'totalPaginas' => ceil($total / $limite)
            ]);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::getDonaciones - " . $e->getMessage());
            return Response::error('Error al obtener donaciones', null, 500);
        }
    }
    
    /**
     * PUT /api/admin/donaciones/{tipo}/{id}
     */
    public function updateDonacion($tipo, $id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $tabla = $tipo === 'persona' ? 'donaciones_personas' : 'donaciones_empresas';
            
            // Verificar que existe
            $donacion = $this->db->fetchOne("SELECT id FROM $tabla WHERE id = ?", [$id]);
            
            if (!$donacion) {
                return Response::error('Donación no encontrada', null, 404);
            }
            
            // Actualizar estado (principalmente)
            if (isset($data['estado'])) {
                $this->db->execute(
                    "UPDATE $tabla SET estado = ? WHERE id = ?",
                    [$data['estado'], $id]
                );
                
                $this->authService->logActivity(
                    $this->user['id'],
                    'ACTUALIZAR_DONACION',
                    $tabla,
                    $id,
                    "Estado cambiado a: {$data['estado']}"
                );
            }
            
            return Response::success(null, 'Donación actualizada');
            
        } catch (Exception $e) {
            error_log("Error en AdminController::updateDonacion - " . $e->getMessage());
            return Response::error('Error al actualizar donación', null, 500);
        }
    }
    
    // ============================================
    // GESTIÓN DE MENSAJES
    // ============================================
    
    /**
     * GET /api/admin/mensajes
     */
    public function getMensajes() {
        try {
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $limite = isset($_GET['limite']) ? min(100, max(1, intval($_GET['limite']))) : 20;
            $leido = isset($_GET['leido']) ? ($_GET['leido'] === 'true' ? 1 : 0) : null;
            
            $offset = ($pagina - 1) * $limite;
            
            $where = $leido !== null ? "WHERE leido = $leido" : "";
            
            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM mensajes_contacto $where"
            )['count'];
            
            $mensajes = $this->db->fetchAll(
                "SELECT * FROM mensajes_contacto $where ORDER BY fecha_envio DESC LIMIT $limite OFFSET $offset"
            );
            
            return Response::success([
                'mensajes' => $mensajes,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'totalPaginas' => ceil($total / $limite)
            ]);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::getMensajes - " . $e->getMessage());
            return Response::error('Error al obtener mensajes', null, 500);
        }
    }
    
    /**
     * PUT /api/admin/mensajes/{id}/marcar-leido
     */
    public function marcarMensajeLeido($id) {
        try {
            $this->db->execute(
                "UPDATE mensajes_contacto SET leido = TRUE WHERE id = ?",
                [$id]
            );
            
            $this->authService->logActivity(
                $this->user['id'],
                'MARCAR_MENSAJE_LEIDO',
                'mensajes_contacto',
                $id
            );
            
            return Response::success(null, 'Mensaje marcado como leído');
            
        } catch (Exception $e) {
            error_log("Error en AdminController::marcarMensajeLeido - " . $e->getMessage());
            return Response::error('Error al marcar mensaje', null, 500);
        }
    }
    
    /**
     * DELETE /api/admin/mensajes/{id}
     */
    public function deleteMensaje($id) {
        try {
            // Solo admin o super_admin
            AuthController::requireRole('admin');
            
            $this->db->execute(
                "DELETE FROM mensajes_contacto WHERE id = ?",
                [$id]
            );
            
            $this->authService->logActivity(
                $this->user['id'],
                'ELIMINAR_MENSAJE',
                'mensajes_contacto',
                $id
            );
            
            return Response::success(null, 'Mensaje eliminado');
            
        } catch (Exception $e) {
            error_log("Error en AdminController::deleteMensaje - " . $e->getMessage());
            return Response::error('Error al eliminar mensaje', null, 500);
        }
    }
    
    // ============================================
    // LOGS DE ACTIVIDAD
    // ============================================
    
    /**
     * GET /api/admin/logs
     */
    public function getLogs() {
        try {
            // Solo admin o super_admin
            AuthController::requireRole('admin');
            
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $limite = isset($_GET['limite']) ? min(100, max(1, intval($_GET['limite']))) : 50;
            
            $offset = ($pagina - 1) * $limite;
            
            $logs = $this->db->fetchAll(
                "SELECT l.*, u.username, u.nombre_completo
                 FROM admin_logs l
                 JOIN admin_users u ON l.user_id = u.id
                 ORDER BY l.created_at DESC
                 LIMIT $limite OFFSET $offset"
            );
            
            $total = $this->db->fetchOne("SELECT COUNT(*) as count FROM admin_logs")['count'];
            
            return Response::success([
                'logs' => $logs,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'totalPaginas' => ceil($total / $limite)
            ]);
            
        } catch (Exception $e) {
            error_log("Error en AdminController::getLogs - " . $e->getMessage());
            return Response::error('Error al obtener logs', null, 500);
        }
    }
}

