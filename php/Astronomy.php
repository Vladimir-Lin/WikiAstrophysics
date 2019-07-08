<?php

require_once dirname(__FILE__) . "/../vendor/autoload.php"                   ;
use CIOS\DB        as CiosDB                                                 ;
use CIOS\Name      as Name                                                   ;
use CIOS\Strings   as Strings                                                ;
use CIOS\Html      as Html                                                   ;
use CIOS\TimeZones as TimeZones                                              ;

class TzWiki
{

public static function SayTz ( $TZH , $root , $args )
{
  $HTML    = ""                                                              ;
  $LANG    = "en"                                                            ;
  $EDIT    = false                                                           ;
  $LANGS   = $GLOBALS [ "Languages" ]                                        ;
  $TABLES  = $GLOBALS [ "TzTables"  ]                                        ;
  $TzTable = $TABLES  [ "TimeZone"  ]                                        ;
  $TzNames = $TABLES  [ "TzNames"   ]                                        ;
  $LL      = array ( )                                                       ;
  ////////////////////////////////////////////////////////////////////////////
  if ( count ( $args ) > 1 ) $LANG = $args [ 1 ]                             ;
  ////////////////////////////////////////////////////////////////////////////
  $IXX = 2                                                                   ;
  $LXX = count ( $args )                                                     ;
  while ( $IXX < $LXX )                                                      {
    $Opts  = new Strings ( $args [ $IXX ] )                                  ;
    $Opts -> split ( "=" )                                                   ;
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "editable"         ) {
      if ( strtolower ( trim ( $Opts -> at ( 1 ) ) ) == "yes"              ) {
        $EDIT = true                                                         ;
      }                                                                      ;
    } else
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "languages"        ) {
      $LSK = strtolower ( trim ( $Opts -> at ( 1 ) ) )                       ;
      $LLK = explode ( "," , $LSK )                                          ;
      foreach ( $LLK as $kv )                                                {
        $kv = intval ( strtolower ( trim ( $kv ) ) , 10 )                    ;
        array_push   ( $LL , $kv                        )                    ;
      }                                                                      ;
    }                                                                        ;
    $IXX  = $IXX + 1                                                         ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $DB = new CiosDB ( )                                                       ;
  if                         ( ! $DB -> Connect ( $TZH )                   ) {
    return $DB -> ConnectionError ( )                                        ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $TZS    = new TimeZones    (                                             ) ;
  $TZS   -> Query            ( $DB , $TzTable                              ) ;
  $TZS   -> ZoneNames        ( $DB , $TzNames , $LL                        ) ;
  $IDs    = $TZS -> IDs                                                      ;
  ////////////////////////////////////////////////////////////////////////////
  $MAPS = array              (                                             ) ;
  ////////////////////////////////////////////////////////////////////////////
  $htm  = new Html           (                                             ) ;
  $htm -> setType            ( 4                                           ) ;
  $htm -> setSplitter        ( "\n"                                        ) ;
  ////////////////////////////////////////////////////////////////////////////
  foreach                    ( $LL as $kk                                  ) {
    $HD    = $htm  -> addTd  ( $LANGS [ $kk ]                              ) ;
    $HD   -> AddPair         ( "align" , "center"                          ) ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $MAPS [ "$(TIMEZONE-LANGUAGES)"  ] = $htm -> Content ( )                   ;
  ////////////////////////////////////////////////////////////////////////////
  $htm  = new Html           (                                             ) ;
  $htm -> setType            ( 4                                           ) ;
  $htm -> setSplitter        ( "\n"                                        ) ;
  ////////////////////////////////////////////////////////////////////////////
  foreach                    ( $IDs as $id                                 ) {
    $TUID  = $TZS -> UuidById     ( $id )                                    ;
    $ZNAME = $TZS -> ZoneNameById ( $id )                                    ;
    $HR    = $htm -> addTr   (                                             ) ;
    $HD    = $HR  -> addTd   ( $id                                         ) ;
    $HD   -> AddPair         ( "align" , "right"                           ) ;
    $HD    = $HR  -> addTd   ( $TUID                                       ) ;
    $HD   -> AddPair         ( "align" , "right"                           ) ;
    $HD    = $HR  -> addTd   ( $ZNAME                                      ) ;
    foreach                  ( $LL as $kk                                  ) {
//      $NI -> set             ( "Uuid"      , $TUID                         ) ;
//      $NI -> set             ( "Locality"  , $kk                           ) ;
//      $LNAME = $NI -> Fetch  ( $DB , $TzNames                              ) ;
      $LNAME = $TZS -> NAMEs [ $kk ] [ $TUID ]                               ;
      if ( $EDIT )                                                           {
        $JSC  = "updateTimeZoneName(this.value,'{$TUID}',$kk);"              ;
        $HD   = $HR  -> addTd    (                                         ) ;
        $INP  = $HD  -> addInput (                                         ) ;
        $INP -> AddPair          ( "class"    , "NameInput"                ) ;
        $INP -> AddPair          ( "onchange" , $JSC                       ) ;
        $INP -> AddPair          ( "value"    , $LNAME                     ) ;
      } else                                                                 {
        $HD   = $HR  -> addTd    ( $LNAME                                  ) ;
      }                                                                      ;
      $HD    -> NoWrap           (                                         ) ;
    }                                                                        ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $MAPS [ "$(TIMEZONE-COLUMNS)"  ] = count ( $LL ) + 3                       ;
  $MAPS [ "$(TIMEZONE-LISTINGS)" ] = $htm -> Content ( )                     ;
  ////////////////////////////////////////////////////////////////////////////
  $FILENAME = dirname(__FILE__) . "/../templates/{$LANG}.html"               ;
  $HTML     = Strings::ReplaceFileByKeys ( $FILENAME , $MAPS )               ;
  ////////////////////////////////////////////////////////////////////////////
  $MAPS     = array ( )                                                      ;
  $MAPS [ "$(TIMEZONE-BLOCK)" ] = $HTML                                      ;
  $MAPS [ "$(TZ-ROOT-PATH)"   ] = $root                                      ;
  ////////////////////////////////////////////////////////////////////////////
  $FILENAME = dirname(__FILE__) . "/../templates/FrameWork.html"             ;
  $HTML     = Strings::ReplaceFileByKeys ( $FILENAME , $MAPS )               ;
  ////////////////////////////////////////////////////////////////////////////
  $DB -> Close (     )                                                       ;
  unset        ( $DB )                                                       ;
  ////////////////////////////////////////////////////////////////////////////
  return $HTML                                                               ;
}

public static function TzSelector ( $TZH , $root , $args )
{
  ////////////////////////////////////////////////////////////////////////////
  $HTML     = ""                                                             ;
  $LANG     = 1001                                                           ;
  $EDIT     = false                                                          ;
  $TABLES   = $GLOBALS [ "TzTables"  ]                                       ;
  $TzTable  = $TABLES  [ "TimeZone"  ]                                       ;
  $TzNames  = $TABLES  [ "TzNames"   ]                                       ;
  $SID      = ""                                                             ;
  $CID      = ""                                                             ;
  $TZID     = ""                                                             ;
  $JSC      = ""                                                             ;
  $TRIGGER  = "onchange"                                                     ;
  ////////////////////////////////////////////////////////////////////////////
  if ( count ( $args ) > 1 ) $LANG = $args [ 1 ]                             ;
  ////////////////////////////////////////////////////////////////////////////
  $IXX      = 2                                                              ;
  $LXX      = count ( $args )                                                ;
  while ( $IXX < $LXX )                                                      {
    $Opts   = new Strings ( $args [ $IXX ] )                                 ;
    $Opts -> split ( "=" )                                                   ;
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "id"               ) {
      $SID  = trim ( $Opts -> at ( 1 ) )                                     ;
    } else
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "class"            ) {
      $CID  = trim ( $Opts -> at ( 1 ) )                                     ;
    } else
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "timezone"         ) {
      $TZID = trim ( $Opts -> at ( 1 ) )                                     ;
    } else
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "js"               ) {
      $JSC  = trim ( $Opts -> at ( 1 ) )                                     ;
    } else
    if   ( strtolower ( trim ( $Opts -> at ( 0 ) ) ) == "trigger"          ) {
      $TRIGGER = trim ( $Opts -> at ( 1 ) )                                  ;
    }                                                                        ;
    $IXX    = $IXX + 1                                                       ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $DB        = new CiosDB           (                                      ) ;
  if                                ( ! $DB -> Connect ( $TZH )            ) {
    return $DB -> ConnectionError   (                                      ) ;
  }                                                                          ;
  ////////////////////////////////////////////////////////////////////////////
  $TZS       = new TimeZones        (                                      ) ;
  $TZS      -> Query                ( $DB , $TzTable                       ) ;
  $TZS      -> ZoneNames            ( $DB , $TzNames , [ $LANG ]           ) ;
  ////////////////////////////////////////////////////////////////////////////
  $SELECTOR  = $TZS -> addSelection ( $TZID , $SID , $CID , $LANG          ) ;
  if                                ( strlen ( $JSC ) > 0                  ) {
    $SELECTOR -> AddPair            ( $TRIGGER , $JSC                      ) ;
  }                                                                          ;
  $HTML      = $SELECTOR -> Content (                                      ) ;
  ////////////////////////////////////////////////////////////////////////////
  $DB -> Close (     )                                                       ;
  unset        ( $DB )                                                       ;
  ////////////////////////////////////////////////////////////////////////////
  return $HTML                                                               ;
}

}

?>
