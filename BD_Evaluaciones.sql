USE cedes_evaluacion;

CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE areas (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_area INT,
    FOREIGN KEY (id_area) REFERENCES areas(id_area)
);

CREATE TABLE usuarios (
    cedula VARCHAR(25) PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    apellido1 VARCHAR(80) NOT NULL,
    apellido2 VARCHAR(80) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

CREATE TABLE detalle_usuario (
    id_detalle_usuario INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(25),
    telefono VARCHAR(20),
    correo VARCHAR(120),
    departamento VARCHAR(100),
    id_materia INT,
    FOREIGN KEY (cedula) REFERENCES usuarios(cedula),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia)
);

CREATE TABLE periodos (
	id_periodo INT AUTO_INCREMENT PRIMARY KEY,
    anio_escolar INT NOT NULL,
    inicio_entrega DATETIME,
    cierre_entrega DATETIME,
    inicio_evaluacion DATETIME,
    cierre_evaluacion DATETIME
);

CREATE TABLE planeamientos (
	id_planeamiento INT AUTO_INCREMENT PRIMARY KEY,
    cedula_docente VARCHAR (25),
    id_periodo INT,
    archivo VARCHAR (255) NOT NULL,
    fecha_subido DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cedula_docente) REFERENCES usuarios(cedula),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo)
);

CREATE TABLE puntos (
	id_puntos INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (50) NOT NULL,
    puntos  INT NOT NULL	
);

CREATE TABLE rubricas (
	id_rubrica INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (150) NOT NULL,
    descripcion VARCHAR (250),
    id_periodo INT,
    id_area INT,
    activo TINYINT(1) DEFAULT(1),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo),
    FOREIGN KEY (id_area) REFERENCES areas(id_area)
);

CREATE TABLE criterios (
	id_criterio INT AUTO_INCREMENT PRIMARY KEY,
    id_rubrica INT,
    descripcion VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_rubrica) REFERENCES rubricas(id_rubrica)
);

CREATE TABLE niveles_evaluacion (
    id_niveles_evaluacion INT AUTO_INCREMENT PRIMARY KEY,
    id_criterio INT,
    id_puntos INT,
    descripcion VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_criterio) REFERENCES criterios(id_criterio),
    FOREIGN KEY (id_puntos) REFERENCES puntos(id_puntos)
);

INSERT INTO roles (nombre) VALUES
('Coordinador'),
('Docente');

INSERT INTO areas (nombre) VALUES
('Ciencias y Matemáticas'),
('Español y Estudios Sociales');

INSERT INTO materias (nombre, id_area) VALUES
('Matemáticas', 1),
('Español', 2);

INSERT INTO usuarios (cedula, nombre, apellido1, apellido2, contrasena, id_rol) VALUES
('1-0945-0612', 'Javier', 'Mena', 'Solís', '123456', 1),
('100564782', 'Lucía', 'Rodríguez', 'Vargas', '123456', 2),
('187263944', 'Randall', 'Ocón', 'Mora', '123456', 2);

INSERT INTO detalle_usuario (cedula, telefono, correo, departamento, id_materia) VALUES
('1-0945-0612', '2250-1122', 'jmena@cedesdonbosco.ed.cr', 'Coordinación Académica', NULL),
('100564782', '8812-3456', 'lrodriguez@cedesdonbosco.ed.cr', 'Docencia Académica', 2),
('187263944', '8745-9012', 'rocon@cedesdonbosco.ed.cr', 'Docencia Académica', 1);

INSERT INTO periodos (anio_escolar, inicio_entrega, cierre_entrega, inicio_evaluacion, cierre_evaluacion) VALUES
(2026, '2026-08-01 08:00:00', '2026-11-27 23:59:59', '2026-11-16 08:00:00', '2026-12-11 23:59:59');

INSERT INTO planeamientos (cedula_docente, id_periodo, archivo, fecha_subido) VALUES
('100564782', 1, 'Planeamiento_01_Espanol_2026.pdf', '2026-10-15 09:30:00'),
('187263944', 1, 'Planeamiento_01_Matematicas_2026.pdf', '2026-10-18 14:15:00');

INSERT INTO puntos (nombre, puntos) VALUES
('Excelente', 4),
('Muy Bueno', 3),
('Bueno', 2),
('Insuficiente', 1);

INSERT INTO rubricas (nombre, descripcion, id_periodo, id_area, activo) VALUES
('Rúbrica General Docente 2026', 'Evaluación de clase y planeamiento', 1, 2, 1);

INSERT INTO criterios (id_rubrica, descripcion) VALUES
(1, 'Observación de clase'),
(1, 'Planeación');

INSERT INTO niveles_evaluacion (id_criterio, id_puntos, descripcion) VALUES
(1, 1, 'Clases dinámicas y participativas.'),
(1, 2, 'Clases participativas en su mayoría.'),
(1, 3, 'Clases algo pasivas.'),
(1, 4, 'Clases pasivas.'),
(2, 1, 'Planes claros, organizados y alineados.'),
(2, 2, 'Planes generalmente claros y organizados.'),
(2, 3, 'Planes un poco desorganizados.'),
(2, 4, 'Planes confusos o desorganizados.');

SELECT u.nombre, u.apellido1, r.nombre AS rol, d.correo, p.archivo
FROM usuarios u
JOIN roles r ON u.id_rol = r.id_rol
LEFT JOIN detalle_usuario d ON u.cedula = d.cedula
LEFT JOIN planeamientos p ON u.cedula = p.cedula_docente;