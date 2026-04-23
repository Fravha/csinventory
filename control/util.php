<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

function CreateCbo($_lista, $_nameCtl, $_colValue, $_colText, $_valueSelected = ""){
    $opciones = "";
    $opSel = "";
    
    foreach($_lista as $ele){        
        if ( is_array($ele) ){
            $opSel = ($ele[$_colValue] == $_valueSelected) ? "selected" : "";
            $opciones .= "<option value='". $ele[$_colValue] ."' ". $opSel .">". $ele[$_colText] ."</option>";
        }else if ( is_object($ele) ){            
            $opSel = ($ele->$_colValue == $_valueSelected) ? "selected" : "";
            $opciones .= "<option value='". $ele->$_colValue ."' ". $opSel .">". $ele->$_colText ."</option>";
        }        
    }

    $control = "<select name='". $_nameCtl ."'>
        ". $opciones ."
    </select>";

    return $control;
}

function Filtrar($_lista, $_colName, $_colValue){
    $newList = array();

    foreach($_lista as $ele){
        if ( is_array($ele) ){
            if ( $ele[$_colName] == $_colValue ){
                $newList[] = $ele;
            }
        }else if ( is_object($ele)){
            if ( $ele->$_colName == $_colValue ){
                $newList[] = $ele;
            }
        }
        
    }

    return $newList;
}

function Buscar($_lista, $_colName, $_colValue){
    $data = null;
    foreach($_lista as $ele){
        if ( is_array($ele) ){
            if ( $ele[$_colName] == $_colValue ){
                // encontrado
                $data = $ele;
                break;
            }
        }else if ( is_object($ele)){
            if ( $ele->$_colName == $_colValue ){
                // encontrado
                $data = $ele;
                break;
            }
        }
        
    }

    return $data;
}

?>