<?php

if ( function_exists( 'wfLoadExtension' ) ) {
  wfLoadExtension( 'TimeZones' );
  $wgMessagesDirs['TimeZones'] = __DIR__ . '/i18n';
  return true;
} else {
  die( 'This extension requires MediaWiki 1.25+' );
}

?>
