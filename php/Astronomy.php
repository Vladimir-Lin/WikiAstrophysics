<?php

require_once dirname(__FILE__) . "/../vendor/autoload.php"                   ;
use CIOS\DB        as CiosDB                                                 ;
use CIOS\Name      as Name                                                   ;
use CIOS\Strings   as Strings                                                ;
use CIOS\Html      as Html                                                   ;
use CIOS\TimeZones as TimeZones                                              ;
require_once dirname(__FILE__) . "/../site/site.php"                         ;
require_once dirname(__FILE__) . "/Common/Common.php"                        ;
require_once dirname(__FILE__) . "/Earth/Earth.php"                          ;
require_once dirname(__FILE__) . "/Moon/Moon.php"                            ;
require_once dirname(__FILE__) . "/Sun/Sun.php"                              ;

class AstrophysicsWiki
{

public static function setHooks ( &$parser )
{
  $parser -> setFunctionHook ( 'Astroputer'   , [ self::class , 'Astroputer'   ] ) ;
  $parser -> setHook         ( 'Astrophysics' , [ self::class , 'Astrophysics' ] ) ;
  return true ;
}

public static function Astroputer( Parser $parser )
{
  ////////////////////////////////////////////////////////////////////////////
  $mypath   = dirname      ( __FILE__                                      ) ;
  $mypath   = str_replace  ( "\\" , "/" , $mypath                          ) ;
  $rootpt   = dirname      ( dirname ( $mypath )                           ) ;
  $rootpt   = str_replace  ( "\\" , "/" , $rootpt                          ) ;
  $croot    = str_replace  ( $rootpt , "" , $mypath                        ) ;
  ////////////////////////////////////////////////////////////////////////////
  $args     = func_get_args (                                              ) ;
  $outp     = "Astroputer"                                                   ;
  ////////////////////////////////////////////////////////////////////////////
  $parser -> getOutput ( ) -> addModules ( [ 'ext.Astrophysics' ] )          ;
  ////////////////////////////////////////////////////////////////////////////
  return array ( $outp , 'noparse' => true , 'isHTML' => true )              ;
}

public static function Astrophysics( $str , array $argv, Parser $parser, PPFrame $frame )
{
  ////////////////////////////////////////////////////////////////////////////
  $mypath       = dirname      ( __FILE__                                  ) ;
  $mypath       = str_replace  ( "\\" , "/" , $mypath                      ) ;
  $rootpt       = dirname      ( dirname ( $mypath )                       ) ;
  $rootpt       = str_replace  ( "\\" , "/" , $rootpt                      ) ;
  $croot        = str_replace  ( $rootpt , "" , $mypath                    ) ;
  ////////////////////////////////////////////////////////////////////////////
  $OBJECT       = ""                                                         ;
  $OBJECT       = TagString    ( "object" , $argv                          ) ;
  $OBJECT       = strtolower   ( $OBJECT                                   ) ;
  ////////////////////////////////////////////////////////////////////////////
  if                           ( $OBJECT == "earth"                        ) {
    $outp    = Earth::Dispatch ( $str , $argv                              ) ;
    //////////////////////////////////////////////////////////////////////////
    $parser -> getOutput ( ) -> addModules ( [ 'ext.Astrophysics' ] )        ;
  } else
  if                           ( $OBJECT == "moon"                         ) {
  } else
  if                           ( $OBJECT == "sun"                          ) {
  } else                                                                     {
    $outp   = ""                                                             ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  return array ( $outp , 'noparse' => true , 'isHTML' => true )              ;
}

}

?>
