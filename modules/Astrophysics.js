var TzRootPath = function ( filename )
{
  var root = $( "#TzRootPath" ) . val ( ) ;
  return root + "/" + filename            ;
}

var updateTimeZoneName = function ( name , tzid , language )
{
  AssignAJAX (
    TzRootPath ( "ajax/ajaxName.php" ) ,
    {
      Name: PurgeInput ( name ) ,
      Uuid: tzid ,
      Locality: language ,
      Priority: 0 ,
      Relevance: 0 ,
    }
  ) ;
}
