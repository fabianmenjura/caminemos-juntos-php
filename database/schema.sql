-- Script para crear las tablas necesarias en MySQL
-- Ejecutar este script en tu base de datos MySQL

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS caminemos_juntos;
USE caminemos_juntos;

-- Tabla de abuelos
CREATE TABLE IF NOT EXISTS abuelos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  edad INT NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  foto_url VARCHAR(255),
  fecha_nacimiento DATE,
  estado ENUM('disponible', 'adoptado', 'inactivo') DEFAULT 'disponible',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de donaciones de personas naturales
CREATE TABLE IF NOT EXISTS donaciones_personas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  monto DECIMAL(10, 2) NOT NULL,
  frecuencia ENUM('unica', 'mensual', 'trimestral', 'anual') NOT NULL,
  metodo_pago ENUM('nequi', 'daviplata', 'tarjeta', 'transferencia') NOT NULL,
  abuelo_id INT,
  mensaje TEXT,
  estado ENUM('pendiente', 'completada', 'cancelada') DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (abuelo_id) REFERENCES abuelos(id) ON DELETE SET NULL
);

-- Tabla de donaciones de empresas
CREATE TABLE IF NOT EXISTS donaciones_empresas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_empresa VARCHAR(200) NOT NULL,
  nit VARCHAR(50) NOT NULL,
  nombre_contacto VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  monto DECIMAL(10, 2) NOT NULL,
  frecuencia ENUM('unica', 'mensual', 'trimestral', 'anual') NOT NULL,
  metodo_pago ENUM('transferencia', 'tarjeta') NOT NULL,
  tipo_patrocinio ENUM('general', 'especifico') NOT NULL,
  abuelo_id INT,
  mensaje TEXT,
  requiere_factura BOOLEAN DEFAULT FALSE,
  estado ENUM('pendiente', 'completada', 'cancelada') DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (abuelo_id) REFERENCES abuelos(id) ON DELETE SET NULL
);

-- Tabla de mensajes de contacto
CREATE TABLE IF NOT EXISTS mensajes_contacto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefono VARCHAR(20),
  asunto VARCHAR(200) NOT NULL,
  mensaje TEXT NOT NULL,
  estado ENUM('nuevo', 'leido', 'respondido') DEFAULT 'nuevo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de cumpleaños
CREATE TABLE IF NOT EXISTS cumpleanos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  abuelo_id INT NOT NULL,
  fecha_cumpleanos DATE NOT NULL,
  destacado BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (abuelo_id) REFERENCES abuelos(id) ON DELETE CASCADE
);

-- Tabla de patrocinadores
CREATE TABLE IF NOT EXISTS patrocinadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_empresa VARCHAR(200) NOT NULL,
  logo_url VARCHAR(255),
  sitio_web VARCHAR(255),
  activo BOOLEAN DEFAULT TRUE,
  orden INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de transacciones PayU
CREATE TABLE IF NOT EXISTS transacciones_payu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference_code VARCHAR(100) NOT NULL UNIQUE,
  amount DECIMAL(10, 2) NOT NULL,
  buyer_email VARCHAR(150) NOT NULL,
  buyer_name VARCHAR(150) NOT NULL,
  description TEXT,
  status ENUM('PENDING', 'APPROVED', 'DECLINED', 'EXPIRED', 'CANCELLED') DEFAULT 'PENDING',
  transaction_id VARCHAR(100),
  response_data JSON,
  confirmation_data JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Índices para mejorar el rendimiento
CREATE INDEX idx_abuelos_estado ON abuelos(estado);
CREATE INDEX idx_donaciones_personas_email ON donaciones_personas(email);
CREATE INDEX idx_donaciones_empresas_nit ON donaciones_empresas(nit);
CREATE INDEX idx_mensajes_estado ON mensajes_contacto(estado);
CREATE INDEX idx_cumpleanos_fecha ON cumpleanos(fecha_cumpleanos);
CREATE INDEX idx_transacciones_reference ON transacciones_payu(reference_code);
CREATE INDEX idx_transacciones_status ON transacciones_payu(status);
