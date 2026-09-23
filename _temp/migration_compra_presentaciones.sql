-- Soporte de compras por presentacion de producto.
-- Ejecutar una vez sobre la base creative_spot_db.

ALTER TABLE compra_detalle
  ADD COLUMN idPresentacion INT(11) NULL AFTER idUnidadMedida,
  ADD KEY idx_compra_detalle_presentacion (idPresentacion);

ALTER TABLE compra_detalle
  ADD CONSTRAINT fk_compra_detalle_presentacion
  FOREIGN KEY (idPresentacion) REFERENCES producto_presentaciones (idPresentacion);

-- Unidades esperadas por compras directas. Si ya existen, omitir estas sentencias.
ALTER TABLE unidades_medida
  MODIFY tipo ENUM('PESO','VOLUMEN','LONGITUD','UNIDAD') NOT NULL;

UPDATE unidades_medida
SET abreviatura = 'l'
WHERE abreviatura = 'lt';

INSERT INTO unidades_medida (nombre, abreviatura, tipo, factor_base, unidad_base, activo)
SELECT 'Metro', 'm', 'LONGITUD', 1, 'm', 1
WHERE NOT EXISTS (
  SELECT 1 FROM unidades_medida WHERE abreviatura = 'm'
);
