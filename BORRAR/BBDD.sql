-- Crear la tabla de Usuarios
drop database tfg_mad;
create database tfg_mad;
use tfg_mad;

CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña_hash VARCHAR(255) NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES Roles(id)
);

-- Crear la tabla de Roles
CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(255) UNIQUE NOT NULL,
    descripcion TEXT
);

-- Crear la tabla de Permisos
CREATE TABLE Permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_permiso VARCHAR(255) UNIQUE NOT NULL,
    descripcion TEXT
);

-- Crear la tabla de Roles_Permisos
CREATE TABLE Roles_Permisos (
    rol_id INT,
    permiso_id INT,
    PRIMARY KEY (rol_id, permiso_id),
    FOREIGN KEY (rol_id) REFERENCES Roles(id),
    FOREIGN KEY (permiso_id) REFERENCES Permisos(id)
);

-- Crear la tabla de Productos
CREATE TABLE Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(5, 2) NOT NULL,
    stock INT NOT NULL
); 