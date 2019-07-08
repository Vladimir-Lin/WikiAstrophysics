<?php

if ( function_exists( 'wfLoadExtension' ) )           {
  wfLoadExtension ( 'Astrophysics' )                  ;
  $wgMessagesDirs['Astrophysics'] = __DIR__ . '/i18n' ;
  return true                                         ;
} else                                                {
  die ( 'This extension requires MediaWiki 1.25+' )   ;
}

?>
