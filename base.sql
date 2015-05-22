
CREATE TABLE categorias (
                categoria_id INT AUTO_INCREMENT NOT NULL,
                nombre VARCHAR(512) NOT NULL,
                PRIMARY KEY (categoria_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE negocios (
                negocio_id INT AUTO_INCREMENT NOT NULL,
                categoria_id INT NULL,
                nombre VARCHAR(512) NULL,
                duenio VARCHAR(512) NULL,
                descripcion LONGTEXT NULL,
                logo LONGTEXT NULL,
                banner LONGTEXT NULL,
                fecha_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (negocio_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE sucursales (
                sucursal_id BIGINT AUTO_INCREMENT NOT NULL,
                negocio_id INT NOT NULL,
                nombre VARCHAR(512) NULL,
                telefonos LONGTEXT NULL,
                celulares LONGTEXT NULL,
                emails LONGTEXT NULL,
                calle VARCHAR(512) NULL,
                numero_exterior VARCHAR(16) NULL,
                numero_interior VARCHAR(16) NULL,
                entre_calles VARCHAR(512) NULL,
                localidad VARCHAR(512) NULL,
                municipio VARCHAR(512) NULL,
                estado VARCHAR(512) NULL,
                codigo_postal VARCHAR(5) NULL,
                otras_referencias LONGTEXT NULL,
                PRIMARY KEY (sucursal_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE llaves (
                llave VARCHAR(32) NOT NULL,
                negocio_id INT NULL,
                PRIMARY KEY (llave)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE negocios ADD CONSTRAINT categorias_negocios_fk
FOREIGN KEY (categoria_id)
REFERENCES categorias (categoria_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE sucursales ADD CONSTRAINT negocios_sucursales_fk
FOREIGN KEY (negocio_id)
REFERENCES negocios (negocio_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE llaves ADD CONSTRAINT negocios_llaves_fk
FOREIGN KEY (negocio_id)
REFERENCES negocios (negocio_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;