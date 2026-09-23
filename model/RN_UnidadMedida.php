<?php

require_once "data/UnidadMedida.php";
require_once "data/DB.php";

class RN_UnidadMedida extends DataBase
{
    public function __construct()
    {
        parent::Open();
    }

    /**
     * Obtener lista de unidades de medida
     * @return UnidadMedida[]
     */
    public function GetList()
    {
        $sql = "SELECT * FROM unidades_medida";
        $res = $this->Execute($sql);

        $list = array();

        if ($this->ContainsData($res)) {
            $data = $this->DataListStructure($res);

            foreach ($data as $item) {
                $list[] = new UnidadMedida(
                    $item["idUnidad"],
                    $item["nombre"],
                    $item["abreviatura"],
                    $item["tipo"],
                    $item["factor_base"],
                    $item["unidad_base"],
                    $item["activo"]
                );
            }
        }

        return $list;
    }
}

?>