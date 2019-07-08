<?php

require_once dirname(__FILE__) . "/../../vendor/autoload.php"                ;
use CIOS\DB        as CiosDB                                                 ;
use CIOS\Name      as Name                                                   ;
use CIOS\Strings   as Strings                                                ;
use CIOS\Html      as Html                                                   ;
use CIOS\TimeZones as TimeZones                                              ;
use CIOS\StarDate  as StarDate                                               ;
require_once dirname(__FILE__) . "/../Common/Common.php"                     ;

class Earth
{

public static function EarthVelocity ( $Distance )
{
  $MU  = "132749350000000000000"       ;
  $AUS = "149597870700"                ;
  $DAU = gmp_mul  ( $AUS , 2         ) ;
  $RAX = gmp_mul  ( $AUS , $Distance ) ;
  $DAU = gmp_sub  ( $DAU , $Distance ) ;
  $DAU = gmp_mul  ( $DAU , $MU       ) ;
  $DAU = gmp_div  ( $DAU , $RAX      ) ;
  return gmp_sqrt ( $DAU             ) ;
}

public static function Apsides ( $content , $argv )
{
  ////////////////////////////////////////////////////////////////////////////
  $TZS         = trim ( TagString ( "timezone" , $argv ) )                   ;
  if ( strlen ( $TZS ) <= 0 ) $TZS = "UTC"                                   ;
  ////////////////////////////////////////////////////////////////////////////
  $CurrentDB   = $GLOBALS     [ "CurrentDB"     ]                            ;
  $AstroTables = $GLOBALS     [ "AstroTables"   ]                            ;
  $Helions     = $AstroTables [ "Earth-Helions" ]                            ;
  $Sidereal    = 31558150                                                    ;
  $AU          = 149597870700                                                ;
  $MU          = "132749350000000000000"                                     ;
  $AUS         = "149597870700"                                              ;
  ////////////////////////////////////////////////////////////////////////////
  $DB          = new CiosDB              (                                 ) ;
  if                                     ( ! $DB -> Connect ( $CurrentDB ) ) {
    return $DB -> ConnectionError        (                                 ) ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $TABLE       = new Html                (                                 ) ;
  $TBODY       = $TABLE -> ConfigureTable ( 1 , 1 , 1                      ) ;
  $TBODY      -> setSplitter             ( "\n"                            ) ;
  ////////////////////////////////////////////////////////////////////////////
  $HR          = $TBODY -> addTr         (                                 ) ;
  $HD          = $HR    -> addTd         ( $content                        ) ;
  $HD         -> AddPair                 ( "colspan" , "9"                 ) ;
  ////////////////////////////////////////////////////////////////////////////
  $TZSTR       = "Time Zone : {$TZS}"                                        ;
  $HR          = $TBODY -> addTr         (                                 ) ;
  $HD          = $HR    -> addTd         ( $TZSTR                          ) ;
  $HD         -> AddPair                 ( "colspan" , "9"                 ) ;
  ////////////////////////////////////////////////////////////////////////////
  $HR          = $TBODY -> addTr         (                                 ) ;
  $HD          = $HR    -> addTd         ( "Year"                          ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Perihelion"                    ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Distance"                      ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Sidereal error"                ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Velocity"                      ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Aphelion"                      ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Distance"                      ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Sidereal error"                ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  $HD          = $HR    -> addTd         ( "Velocity"                      ) ;
  $HD         -> AddPair                 ( "align" , "center"              ) ;
  ////////////////////////////////////////////////////////////////////////////
  $QQ          = "select `year`,`perihelion`,`peridistance`,`aphelion`,`apdistance` from {$Helions} order by id asc ;" ;
  $qq          = $DB -> Query            ( $QQ                             ) ;
  ////////////////////////////////////////////////////////////////////////////
  if                                     ( $DB -> hasResult ( $qq )        ) {
    $PDT       = new StarDate  (                                           ) ;
    $ADT       = new StarDate  (                                           ) ;
    $IDP       = 0                                                           ;
    $LPERI     = 0                                                           ;
    $LAPHE     = 0                                                           ;
    while ( $rr = $qq -> fetch_array ( MYSQLI_BOTH ) )                       {
      $YEAR            = $rr [ 0 ]                                           ;
      $Perihelion      = $rr [ 1 ]                                           ;
      $PeriDistance    = $rr [ 2 ]                                           ;
      $Aphelion        = $rr [ 3 ]                                           ;
      $ApDistance      = $rr [ 4 ]                                           ;
      ////////////////////////////////////////////////////////////////////////
      $VPERI           = self::EarthVelocity ( $PeriDistance )               ;
      $VAPHD           = self::EarthVelocity ( $ApDistance   )               ;
      ////////////////////////////////////////////////////////////////////////
      $PERR            = ""                                                  ;
      $AERR            = ""                                                  ;
      ////////////////////////////////////////////////////////////////////////
      if                       ( $IDP > 0                                  ) {
        $PERR = $Perihelion - $LPERI - $Sidereal                             ;
        $AERR = $Aphelion   - $LAPHE - $Sidereal                             ;
      }                                                                      ;
      ////////////////////////////////////////////////////////////////////////
      $PDT -> Stardate = $Perihelion                                         ;
      $ADT -> Stardate = $Aphelion                                           ;
      ////////////////////////////////////////////////////////////////////////
      $PDS   = $PDT -> toDateTimeString ( $TZS , " " , "Y/m/d" , "H:i:s"   ) ;
      $ADS   = $ADT -> toDateTimeString ( $TZS , " " , "Y/m/d" , "H:i:s"   ) ;
      ////////////////////////////////////////////////////////////////////////
      $HR    = $TBODY -> addTr (                                           ) ;
      $HD    = $HR    -> addTd ( $YEAR                                     ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $PDS                                      ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $PeriDistance                             ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $PERR                                     ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $VPERI                                    ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $ADS                                      ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $ApDistance                               ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $AERR                                     ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      $HD    = $HR    -> addTd ( $VAPHD                                    ) ;
      $HD   -> AddPair         ( "align" , "right"                         ) ;
      ////////////////////////////////////////////////////////////////////////
      $LPERI = $Perihelion                                                   ;
      $LAPHE = $Aphelion                                                     ;
      ////////////////////////////////////////////////////////////////////////
      $IDP   = $IDP + 1                                                      ;
      ////////////////////////////////////////////////////////////////////////
    }                                                                        ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $DB -> Close (     )                                                       ;
  unset        ( $DB )                                                       ;
  ////////////////////////////////////////////////////////////////////////////
  return $TABLE -> Content ( )                                               ;
}

public static function Dispatch ( $content , $argv )
{
  ////////////////////////////////////////////////////////////////////////////
  $PROPERTY   = ShrinkString ( TagString ( "property"  , $argv           ) ) ;
  $PARAMETER  = ShrinkString ( TagString ( "parameter" , $argv           ) ) ;
  $METHOD     = ShrinkString ( TagString ( "method"    , $argv           ) ) ;
  ////////////////////////////////////////////////////////////////////////////
  if                         ( $PROPERTY  == "orbit"                       ) {
    if                       ( $PARAMETER == "apsides"                     ) {
      if                     ( $METHOD    == "table"                       ) {
        return self::Apsides ( $content , $argv                            ) ;
      }                                                                      ;
    }                                                                        ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  return "{$PROPERTY} {$PARAMETER} {$METHOD} : {$content}" ;
}

}

?>
