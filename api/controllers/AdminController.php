<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/AuthController.php';

class AdminController {
    private $db;
    private $user;
    private $authService;
    
    public function __construct() {
        try {
            Logger::debug("AdminController: Inicializando");
            $this->db = Database::getInstance();
            Logger::debug("AdminController: Database OK");
            
            $this->user = AuthController::requireAuth();
            Logger::debug("AdminController: Auth OK", ['user_id' => $this->user['id']]);
            
            require_once __DIR__ . '/../services/AuthService.php';
            $this->authService = new AuthService();
            Logger::debug("AdminController: AuthService OK");
        } catch (Exception $e) {
            Logger::error("AdminController construct failed", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }
    
    // DASHBOARD
    public function getDashboard() {
        try {
            Logger::debug("getDashboard: Iniciando");
            
            // Obtener counts de forma segura
            Logger::debug("getDashboard: Consultando abuelos");
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible'");
            $totalAbuelos = $result ? intval($result['count']) : 0;
            Logger::debug("getDashboard: Total abuelos = $totalAbuelos");
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible' AND genero = 'M'");
            $hombres = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos WHERE estado = 'disponible' AND genero = 'F'");
            $mujeres = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_personas");
            $totalPersonas = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM donaciones_empresas");
            $totalEmpresas = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COALESCE(SUM(monto), 0) as total FROM donaciones_personas WHERE estado = 'completada'");
            $montoPersonas = $result ? floatval($result['total']) : 0;
            
            $result = $this->db->fetchOne("SELECT COALESCE(SUM(monto), 0) as total FROM donaciones_empresas WHERE estado = 'completada'");
            $montoEmpresas = $result ? floatval($result['total']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM mensajes_contacto");
            $totalMensajes = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM mensajes_contacto WHERE estado = 'nuevo'");
            $mensajesNoLeidos = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM transacciones_payu WHERE status = 'APPROVED'");
            $exitosas = $result ? intval($result['count']) : 0;
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM transacciones_payu WHERE status = 'PENDING'");
            $pendientes = $result ? intval($result['count']) : 0;
            
            $stats = [
                'abuelos' => [
                    'total' => $totalAbuelos,
                    'hombres' => $hombres,
                    'mujeres' => $mujeres,
                ],
                'donaciones' => [
                    'total_personas' => $totalPersonas,
                    'total_empresas' => $totalEmpresas,
                    'monto_total_personas' => $montoPersonas,
                    'monto_total_empresas' => $montoEmpresas,
                ],
                'mensajes' => [
                    'total' => $totalMensajes,
                    'no_leidos' => $mensajesNoLeidos,
                ],
                'transacciones' => [
                    'exitosas' => $exitosas,
                    'pendientes' => $pendientes,
                ]
            ];
            
            // Donaciones recientes
            try {
                $donaciones = $this->db->fetchAll(
                    "SELECT 'persona' as tipo, nombre_completo as nombre, monto, created_at as fecha, estado
                     FROM donaciones_personas
                     UNION ALL
                     SELECT 'empresa' as tipo, nombre_empresa as nombre, monto, created_at as fecha, estado
                     FROM donaciones_empresas
                     ORDER BY fecha DESC LIMIT 10"
                );
                $stats['donaciones_recientes'] = $donaciones ?: [];
            } catch (Exception $e) {
                Logger::error("Error en donaciones recientes", ['error' => $e->getMessage()]);
                $stats['donaciones_recientes'] = [];
            }
            
            Logger::debug("getDashboard: Stats completas", $stats);
            Logger::api('/admin/dashboard', 'GET', 200, ['stats_count' => count($stats)]);
            
            return Response::success($stats);
        } catch (Exception $e) {
            Logger::error("Error en getDashboard", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Devolver error con detalles para debugging
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener estadísticas',
                'message' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
                'details' => 500
            ]);
            exit;
        }
    }
    
    // ABUELOS - Listar
    public function getAbuelos() {
        try {
            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
            $limite = isset($_GET['limite']) ? min(100, max(1, intval($_GET['limite']))) : 20;
            $estado = $_GET['estado'] ?? '';
            
            $offset = ($pagina - 1) * $limite;
            $where = $estado ? "WHERE estado = '$estado' AND deleted = 0" : "WHERE deleted = 0";
            
            $total = $this->db->fetchOne("SELECT COUNT(*) as count FROM abuelos $where")['count'];
            // Calcular edad en la consulta
            $abuelos = $this->db->fetchAll(
                "SELECT *, 
                        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) as edad,
                        (DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')) as cumple_hoy,
                        (DAYOFYEAR(fecha_nacimiento) BETWEEN DAYOFYEAR(CURDATE()) AND DAYOFYEAR(CURDATE() + INTERVAL 7 DAY)) as cumple_esta_semana
                 FROM abuelos 
                 $where 
                 ORDER BY created_at DESC 
                 LIMIT $limite OFFSET $offset"
            );
            
            return Response::success([
                'abuelos' => $abuelos,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'totalPaginas' => ceil($total / $limite)
            ]);
        } catch (Exception $e) {
            error_log("Error getAbuelos: " . $e->getMessage());
            return Response::error('Error al obtener abuelos', null, 500);
        }
    }
    
    // ABUELOS - Crear
    public function createAbuelo() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $result = $this->db->execute(
                "INSERT INTO abuelos (nombre, ciudad, descripcion, genero, foto_url, fecha_nacimiento, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['nombre'],
                    $data['ciudad'],
                    $data['descripcion'],
                    $data['genero'] ?? 'M',
                    $data['foto_url'] ?? 'assets/images/placeholder.jpg',
                    $data['fecha_nacimiento'] ?? null,
                    $data['estado'] ?? 'disponible'
                ]
            );
            
            $newId = $this->db->lastInsertId();
            $this->authService->logActivity($this->user['id'], 'CREAR_ABUELO', 'abuelos', $newId, "Creado: {$data['nombre']}");
            
            return Response::success(['id' => $newId], 'Abuelo creado exitosamente', 201);
        } catch (Exception $e) {
            error_log("Error createAbuelo: " . $e->getMessage());
            return Response::error('Error al crear abuelo', null, 500);
        }
    }
    
    // ABUELOS - Actualizar
    public function updateAbuelo($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $abuelo = $this->db->fetchOne("SELECT id FROM abuelos WHERE id = ?", [$id]);
            if (!$abuelo) {
                return Response::error('Abuelo no encontrado', null, 404);
            }
            
            $fields = [];
            $values = [];
            $allowedFields = ['nombre', 'ciudad', 'descripcion', 'genero', 'foto_url', 'fecha_nacimiento', 'estado'];
            
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
            $this->db->execute("UPDATE abuelos SET " . implode(', ', $fields) . " WHERE id = ?", $values);
            
            $this->authService->logActivity($this->user['id'], 'ACTUALIZAR_ABUELO', 'abuelos', $id);
            
            return Response::success(null, 'Abuelo actualizado');
        } catch (Exception $e) {
            error_log("Error updateAbuelo: " . $e->getMessage());
            return Response::error('Error al actualizar', null, 500);
        }
    }
    
    // ABUELOS - Eliminar (soft delete)
    public function deleteAbuelo($id) {
        try {
            Logger::debug("deleteAbuelo: Eliminando abuelo #$id");
            
            // Soft delete: marcar como eliminado
            $this->db->execute("UPDATE abuelos SET deleted = 1 WHERE id = ?", [$id]);
            
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_ABUELO', 'abuelos', $id);
            
            Logger::debug("deleteAbuelo: Abuelo #$id marcado como eliminado");
            
            return Response::success(null, 'Abuelo eliminado');
        } catch (Exception $e) {
            Logger::error("Error en deleteAbuelo", ['error' => $e->getMessage(), 'id' => $id]);
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al eliminar',
                'message' => $e->getMessage(),
                'details' => 500
            ]);
            exit;
        }
    }
    
    // DONACIONES
    public function getDonaciones() {
        try {
            Logger::debug("getDonaciones: Iniciando");
            $tipo = $_GET['tipo'] ?? 'todas';
            $limite = isset($_GET['limite']) ? min(100, intval($_GET['limite'])) : 20;
            
            if ($tipo === 'personas') {
                $donaciones = $this->db->fetchAll(
                    "SELECT *, 'persona' as tipo_categoria FROM donaciones_personas ORDER BY created_at DESC LIMIT $limite"
                );
            } elseif ($tipo === 'empresas') {
                $donaciones = $this->db->fetchAll(
                    "SELECT *, 'empresa' as tipo_categoria FROM donaciones_empresas ORDER BY created_at DESC LIMIT $limite"
                );
            } else {
                $donaciones = $this->db->fetchAll(
                    "SELECT id, nombre_completo as nombre, email, monto, estado, created_at as fecha_donacion, 'persona' as tipo_categoria
                     FROM donaciones_personas
                     UNION ALL
                     SELECT id, nombre_empresa as nombre, email, monto, estado, created_at as fecha_donacion, 'empresa' as tipo_categoria
                     FROM donaciones_empresas
                     ORDER BY fecha_donacion DESC LIMIT $limite"
                );
            }
            
            Logger::debug("getDonaciones: Encontradas " . count($donaciones) . " donaciones");
            return Response::success(['donaciones' => $donaciones, 'total' => count($donaciones)]);
        } catch (Exception $e) {
            Logger::error("Error en getDonaciones", ['error' => $e->getMessage()]);
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener donaciones',
                'message' => $e->getMessage(),
                'details' => 500
            ]);
            exit;
        }
    }
    
    // MENSAJES
    public function getMensajes() {
        try {
            Logger::debug("getMensajes: Iniciando");
            $leido = isset($_GET['leido']) ? $_GET['leido'] : null;
            
            // Adaptar al esquema real: estado = 'nuevo', 'leido', 'respondido'
            $where = "WHERE deleted = 0";
            if ($leido === 'false') {
                $where = "WHERE estado = 'nuevo' AND deleted = 0";
            } elseif ($leido === 'true') {
                $where = "WHERE estado IN ('leido', 'respondido') AND deleted = 0";
            }
            
            $mensajes = $this->db->fetchAll(
                "SELECT * FROM mensajes_contacto $where ORDER BY created_at DESC LIMIT 50"
            );
            
            Logger::debug("getMensajes: Encontrados " . count($mensajes) . " mensajes");
            return Response::success(['mensajes' => $mensajes, 'total' => count($mensajes)]);
        } catch (Exception $e) {
            Logger::error("Error en getMensajes", ['error' => $e->getMessage()]);
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener mensajes',
                'message' => $e->getMessage(),
                'details' => 500
            ]);
            exit;
        }
    }
    
    public function marcarMensajeLeido($id) {
        try {
            // Cambiar estado de 'nuevo' a 'leido'
            $this->db->execute("UPDATE mensajes_contacto SET estado = 'leido' WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'MARCAR_MENSAJE_LEIDO', 'mensajes_contacto', $id);
            
            Logger::debug("marcarMensajeLeido: Mensaje #$id marcado como leído");
            return Response::success(null, 'Mensaje marcado como leído');
        } catch (Exception $e) {
            Logger::error("Error en marcarMensajeLeido", ['error' => $e->getMessage(), 'id' => $id]);
            return Response::error('Error al marcar mensaje', null, 500);
        }
    }
    
    public function deleteMensaje($id) {
        try {
            // Soft delete: marcar como eliminado
            $this->db->execute("UPDATE mensajes_contacto SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_MENSAJE', 'mensajes_contacto', $id);
            
            Logger::debug("deleteMensaje: Mensaje #$id marcado como eliminado");
            return Response::success(null, 'Mensaje eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar mensaje", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
    
    // DONACIONES - Actualizar estado
    public function updateDonacionEstado($tipo, $id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $nuevoEstado = $data['estado'] ?? null;
            
            if (!in_array($nuevoEstado, ['pendiente', 'completada', 'cancelada'])) {
                return Response::error('Estado inválido', null, 400);
            }
            
            $tabla = $tipo === 'persona' ? 'donaciones_personas' : 'donaciones_empresas';
            
            $this->db->execute(
                "UPDATE $tabla SET estado = ? WHERE id = ?",
                [$nuevoEstado, $id]
            );
            
            $this->authService->logActivity(
                $this->user['id'], 
                'ACTUALIZAR_DONACION', 
                $tabla, 
                $id, 
                "Estado cambiado a: $nuevoEstado"
            );
            
            Logger::debug("updateDonacionEstado: Donación $tipo #$id → $nuevoEstado");
            
            return Response::success(null, 'Donación actualizada');
        } catch (Exception $e) {
            Logger::error("Error en updateDonacionEstado", ['error' => $e->getMessage()]);
            return Response::error('Error al actualizar donación', null, 500);
        }
    }
    
    // ===== VOLUNTARIOS =====
    
    public function getVoluntarios() {
        try {
            $estado = $_GET['estado'] ?? 'todos';
            $sql = "SELECT * FROM voluntarios WHERE deleted = 0";
            
            if ($estado !== 'todos') {
                $sql .= " AND estado = ?";
                $voluntarios = $this->db->fetchAll($sql . " ORDER BY created_at DESC", [$estado]);
            } else {
                $voluntarios = $this->db->fetchAll($sql . " ORDER BY created_at DESC");
            }
            
            Logger::debug("getVoluntarios: Encontrados " . count($voluntarios) . " voluntarios");
            return Response::success(['voluntarios' => $voluntarios, 'total' => count($voluntarios)]);
        } catch (Exception $e) {
            Logger::error("Error al obtener voluntarios", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener voluntarios', null, 500);
        }
    }
    
    public function updateVoluntario($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $estado = $data['estado'] ?? null;
            
            if (!in_array($estado, ['nuevo', 'revisado', 'aprobado', 'rechazado', 'inactivo'])) {
                return Response::error('Estado inválido', null, 400);
            }
            
            $fechaAprobacion = ($estado === 'aprobado') ? date('Y-m-d H:i:s') : null;
            
            $this->db->execute(
                "UPDATE voluntarios SET estado = ?, fecha_aprobacion = ? WHERE id = ?",
                [$estado, $fechaAprobacion, $id]
            );
            
            $this->authService->logActivity(
                $this->user['id'],
                'ACTUALIZAR_VOLUNTARIO',
                'voluntarios',
                $id,
                "Estado: $estado"
            );
            
            Logger::debug("updateVoluntario: Voluntario #$id → $estado");
            return Response::success(null, 'Voluntario actualizado');
        } catch (Exception $e) {
            Logger::error("Error al actualizar voluntario", ['error' => $e->getMessage()]);
            return Response::error('Error al actualizar', null, 500);
        }
    }
    
    public function deleteVoluntario($id) {
        try {
            // Soft delete: marcar como eliminado
            $this->db->execute("UPDATE voluntarios SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_VOLUNTARIO', 'voluntarios', $id);
            
            Logger::debug("deleteVoluntario: Voluntario #$id marcado como eliminado");
            return Response::success(null, 'Voluntario eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar voluntario", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
    
    // ===== TESTIMONIOS =====
    
    public function getTestimonios() {
        try {
            $estado = $_GET['estado'] ?? 'todos';
            $sql = "SELECT * FROM testimonios WHERE deleted = 0";
            
            if ($estado === 'aprobados') {
                $sql .= " AND aprobado = TRUE";
                $testimonios = $this->db->fetchAll($sql);
            } elseif ($estado === 'pendientes') {
                $sql .= " AND aprobado = FALSE";
                $testimonios = $this->db->fetchAll($sql);
            } else {
                $testimonios = $this->db->fetchAll($sql . " ORDER BY created_at DESC");
            }
            
            Logger::debug("getTestimonios: Encontrados " . count($testimonios) . " testimonios");
            return Response::success(['testimonios' => $testimonios, 'total' => count($testimonios)]);
        } catch (Exception $e) {
            Logger::error("Error al obtener testimonios", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener testimonios', null, 500);
        }
    }
    
    public function updateTestimonio($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $updates = [];
            $params = [];
            
            if (isset($data['aprobado'])) {
                $updates[] = "aprobado = ?";
                $params[] = (bool)$data['aprobado'];
            }
            
            if (isset($data['destacado'])) {
                $updates[] = "destacado = ?";
                $params[] = (bool)$data['destacado'];
            }
            
            if (isset($data['orden'])) {
                $updates[] = "orden = ?";
                $params[] = (int)$data['orden'];
            }
            
            if (empty($updates)) {
                return Response::error('No hay datos para actualizar', null, 400);
            }
            
            $params[] = $id;
            $sql = "UPDATE testimonios SET " . implode(", ", $updates) . " WHERE id = ?";
            
            $this->db->execute($sql, $params);
            
            $this->authService->logActivity(
                $this->user['id'],
                'ACTUALIZAR_TESTIMONIO',
                'testimonios',
                $id,
                json_encode($data)
            );
            
            Logger::debug("updateTestimonio: Testimonio #$id actualizado");
            return Response::success(null, 'Testimonio actualizado');
        } catch (Exception $e) {
            Logger::error("Error al actualizar testimonio", ['error' => $e->getMessage()]);
            return Response::error('Error al actualizar', null, 500);
        }
    }
    
    public function deleteTestimonio($id) {
        try {
            // Soft delete: marcar como eliminado
            $this->db->execute("UPDATE testimonios SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_TESTIMONIO', 'testimonios', $id);
            
            Logger::debug("deleteTestimonio: Testimonio #$id marcado como eliminado");
            return Response::success(null, 'Testimonio eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar testimonio", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
    
    // ===== HERO SLIDER =====
    
    public function getHeroSlider() {
        try {
            $slides = $this->db->fetchAll(
                "SELECT * FROM hero_slider WHERE deleted = 0 ORDER BY orden ASC, id ASC"
            );
            
            Logger::debug("getHeroSlider: Encontrados " . count($slides) . " slides");
            return Response::success(['slides' => $slides, 'total' => count($slides)]);
        } catch (Exception $e) {
            Logger::error("Error al obtener slides", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener slides', null, 500);
        }
    }
    
    public function createHeroSlide() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $this->db->execute(
                "INSERT INTO hero_slider (titulo, subtitulo, imagen_url, orden, activo, enlace_url) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $data['titulo'],
                    $data['subtitulo'] ?? null,
                    $data['imagen_url'],
                    $data['orden'] ?? 0,
                    $data['activo'] ?? 1,
                    $data['enlace_url'] ?? null
                ]
            );
            
            $this->authService->logActivity($this->user['id'], 'CREAR_HERO_SLIDE', 'hero_slider', $this->db->lastInsertId());
            
            Logger::debug("createHeroSlide: Slide creado");
            return Response::success(['id' => $this->db->lastInsertId()], 'Slide creado', 201);
        } catch (Exception $e) {
            Logger::error("Error al crear slide", ['error' => $e->getMessage()]);
            return Response::error('Error al crear', null, 500);
        }
    }
    
    public function updateHeroSlide($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $updates = [];
            $params = [];
            
            foreach (['titulo', 'subtitulo', 'imagen_url', 'orden', 'activo', 'enlace_url'] as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($updates)) {
                return Response::error('No hay datos para actualizar', null, 400);
            }
            
            $params[] = $id;
            $this->db->execute(
                "UPDATE hero_slider SET " . implode(", ", $updates) . " WHERE id = ?",
                $params
            );
            
            $this->authService->logActivity($this->user['id'], 'ACTUALIZAR_HERO_SLIDE', 'hero_slider', $id);
            
            Logger::debug("updateHeroSlide: Slide #$id actualizado");
            return Response::success(null, 'Slide actualizado');
        } catch (Exception $e) {
            Logger::error("Error al actualizar slide", ['error' => $e->getMessage()]);
            return Response::error('Error al actualizar', null, 500);
        }
    }
    
    public function deleteHeroSlide($id) {
        try {
            // Soft delete
            $this->db->execute("UPDATE hero_slider SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_HERO_SLIDE', 'hero_slider', $id);
            
            Logger::debug("deleteHeroSlide: Slide #$id marcado como eliminado");
            return Response::success(null, 'Slide eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar slide", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
    
    // ===== PATROCINADORES =====
    
    public function getPatrocinadores() {
        try {
            $patrocinadores = $this->db->fetchAll(
                "SELECT * FROM patrocinadores WHERE deleted = 0 ORDER BY orden ASC, id ASC"
            );
            
            Logger::debug("getPatrocinadores: Encontrados " . count($patrocinadores) . " patrocinadores");
            return Response::success(['patrocinadores' => $patrocinadores, 'total' => count($patrocinadores)]);
        } catch (Exception $e) {
            Logger::error("Error al obtener patrocinadores", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener patrocinadores', null, 500);
        }
    }
    
    public function createPatrocinador() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $this->db->execute(
                "INSERT INTO patrocinadores (nombre_empresa, logo_url, sitio_web, descripcion, orden, activo) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $data['nombre_empresa'],
                    $data['logo_url'],
                    $data['sitio_web'] ?? null,
                    $data['descripcion'] ?? null,
                    $data['orden'] ?? 0,
                    $data['activo'] ?? 1
                ]
            );
            
            $this->authService->logActivity($this->user['id'], 'CREAR_PATROCINADOR', 'patrocinadores', $this->db->lastInsertId());
            
            Logger::debug("createPatrocinador: Patrocinador creado");
            return Response::success(['id' => $this->db->lastInsertId()], 'Patrocinador creado', 201);
        } catch (Exception $e) {
            Logger::error("Error al crear patrocinador", ['error' => $e->getMessage()]);
            return Response::error('Error al crear', null, 500);
        }
    }
    
    public function updatePatrocinador($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $updates = [];
            $params = [];
            
            foreach (['nombre_empresa', 'logo_url', 'sitio_web', 'descripcion', 'orden', 'activo'] as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($updates)) {
                return Response::error('No hay datos para actualizar', null, 400);
            }
            
            $params[] = $id;
            $this->db->execute(
                "UPDATE patrocinadores SET " . implode(", ", $updates) . " WHERE id = ?",
                $params
            );
            
            $this->authService->logActivity($this->user['id'], 'ACTUALIZAR_PATROCINADOR', 'patrocinadores', $id);
            
            Logger::debug("updatePatrocinador: Patrocinador #$id actualizado");
            return Response::success(null, 'Patrocinador actualizado');
        } catch (Exception $e) {
            Logger::error("Error al actualizar patrocinador", ['error' => $e->getMessage()]);
            return Response::error('Error al actualizar', null, 500);
        }
    }
    
    public function deletePatrocinador($id) {
        try {
            // Soft delete
            $this->db->execute("UPDATE patrocinadores SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_PATROCINADOR', 'patrocinadores', $id);
            
            Logger::debug("deletePatrocinador: Patrocinador #$id marcado como eliminado");
            return Response::success(null, 'Patrocinador eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar patrocinador", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
    
    // ===== MENSAJES DE CUMPLEAÑOS =====
    
    public function getMensajesCumpleanos() {
        try {
            $estado = $_GET['estado'] ?? 'todos';
            $sql = "SELECT mc.*, a.nombre as nombre_abuelo 
                    FROM mensajes_cumpleanos mc 
                    JOIN abuelos a ON mc.abuelo_id = a.id 
                    WHERE mc.deleted = 0";
            
            if ($estado === 'pendientes') {
                $sql .= " AND mc.aprobado = 0";
            } elseif ($estado === 'aprobados') {
                $sql .= " AND mc.aprobado = 1";
            }
            
            $mensajes = $this->db->fetchAll($sql . " ORDER BY mc.created_at DESC");
            
            Logger::debug("getMensajesCumpleanos: Encontrados " . count($mensajes) . " mensajes");
            return Response::success(['mensajes' => $mensajes, 'total' => count($mensajes)]);
        } catch (Exception $e) {
            Logger::error("Error al obtener mensajes cumpleaños", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener mensajes', null, 500);
        }
    }
    
    public function aprobarMensajeCumpleanos($id) {
        try {
            $this->db->execute("UPDATE mensajes_cumpleanos SET aprobado = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'APROBAR_MENSAJE_CUMPLEANOS', 'mensajes_cumpleanos', $id);
            
            Logger::debug("aprobarMensajeCumpleanos: Mensaje #$id aprobado");
            return Response::success(null, 'Mensaje aprobado');
        } catch (Exception $e) {
            Logger::error("Error al aprobar mensaje cumpleaños", ['error' => $e->getMessage()]);
            return Response::error('Error al aprobar', null, 500);
        }
    }
    
    public function deleteMensajeCumpleanos($id) {
        try {
            $this->db->execute("UPDATE mensajes_cumpleanos SET deleted = 1 WHERE id = ?", [$id]);
            $this->authService->logActivity($this->user['id'], 'ELIMINAR_MENSAJE_CUMPLEANOS', 'mensajes_cumpleanos', $id);
            
            Logger::debug("deleteMensajeCumpleanos: Mensaje #$id eliminado");
            return Response::success(null, 'Mensaje eliminado');
        } catch (Exception $e) {
            Logger::error("Error al eliminar mensaje cumpleaños", ['error' => $e->getMessage()]);
            return Response::error('Error al eliminar', null, 500);
        }
    }
}
