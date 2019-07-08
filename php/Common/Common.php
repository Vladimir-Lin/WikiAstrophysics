<?php

require_once dirname(__FILE__) . "/../../vendor/autoload.php"                ;
use CIOS\DB        as CiosDB                                                 ;
use CIOS\Name      as Name                                                   ;
use CIOS\Strings   as Strings                                                ;
use CIOS\Html      as Html                                                   ;
use CIOS\TimeZones as TimeZones                                              ;

function ContainsString ( $KEY , $TAGs )
{
  $K = array_keys   ( $TAGs    ) ;
  $M = strtolower   ( $KEY     ) ;
  foreach           ( $K as $L ) {
    $S = strtolower ( $L       ) ;
    if              ( $S == $M ) {
      return true                ;
    }                            ;
  }                              ;
  return false                   ;
}

function TagString ( $KEY , $TAGs )
{
  $K = array_keys   ( $TAGs    ) ;
  $M = strtolower   ( $KEY     ) ;
  foreach           ( $K as $L ) {
    $S = strtolower ( $L       ) ;
    if              ( $S == $M ) {
      return $TAGs [ $L ]        ;
    }                            ;
  }                              ;
  return ""                      ;
}

function ShrinkString ( $KEY )
{
  return strtolower ( trim ( $KEY ) ) ;
}

?>
