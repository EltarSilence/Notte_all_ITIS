<?php
  include 'wa_wrapper/WolframAlphaEngine.php';
?>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="http://orioteamcontrollers.it/giovannirizza/NAI/inyee.css">
</head>
<body style="text-align: center;">
    <h1>WOLFRAM VOBARNO</h1>
    <small>alpha 0.1</small>
<form method='POST' action='#'>
Funzione: 
<input type="text" name="q" value="
<?php
  $queryIsSet = isset($_REQUEST['q']);
  if ($queryIsSet) {
    echo $_REQUEST['q'];
  };
?>"
>&nbsp;&nbsp; <input type="submit" name="Search" value="Studia Funzione">
</form>
<br><br>
<hr>
<?php  
  $appID = 'TTW6Y4-RRKPKPAP6U';

  if (!$queryIsSet) die();

  $qArgs = array();
  if (isset($_REQUEST['assumption']))
    $qArgs['assumption'] = $_REQUEST['assumption'];

  // instantiate an engine object with your app id
  $engine = new WolframAlphaEngine( $appID );

  // we will construct a basic query to the api with the input 'pi'
  // only the bare minimum will be used
  $response = $engine->getResults( $_REQUEST['q'], $qArgs);

  // getResults will send back a WAResponse object
  // this object has a parsed version of the wolfram alpha response
  // as well as the raw xml ($response->rawXML) 
  
  // we can check if there was an error from the response object
  if ( $response->isError() ) {
?>
  <h1>Errore di richiesta!</h1>
  </body>
  </html>
<?php
    die();
  }
?>

<?php
  // if there are any assumptions, display them 
  if ( count($response->getAssumptions()) > 0 ) {
?>
    <h2>Assumptions:</h2>
    <ul>
<?php
      // assumptions come as a hash of type as key and array of assumptions as value
      foreach ( $response->getAssumptions() as $type => $assumptions ) {
?>
        <li><?php echo $type; ?>:<br>
          <ol>
<?php
          foreach ( $assumptions as $assumption ) {
?>
            <li><?php echo $assumption->name ." - ". $assumption->description;?>, to change search to this assumption <a href="simpleRequest.php?q=<?php echo urlencode($_REQUEST['q']);?>&assumption=<?php echo $assumption->input;?>">click here</a></li>
<?php
          }
?>
          </ol>
        </li>
<?php
      }
?>
      
    </ul>
<?php
  }
?>

<hr>

<?php
if ( count($response->getPods()) > 0 ) {
    echo '<table border=1 width="60%" align="center">';
    echo '<tr><td align="center"><b>Elemento</b></td><td colspan="5" align="center"><b>Risultato</b></td></tr>';
    foreach ($response->getPods() as $pod){
        echo '<tr><td align="center" style="font-weight: bold;">';
        //var_dump($pod);
        switch($pod->attributes['title']){
            case 'Input':
                echo 'Funzione di x:';
            break;
            case 'Plots':
                echo 'Grafici:';
            break;
            case 'Alternate form':
                echo 'Forma alternativa di scrittura:';
            break;
            case 'Alternate forms':
                echo 'Forme alternative di scrittura:';
            break;
            case 'Expanded form':
                echo 'Forma estesa di scrittura:';
            break;
            case 'Root':
                echo 'Zero della frazione:';
            break;
            case 'Roots':
                echo 'Zeri della frazione:';
            break;
            case 'Property as a real function':
                echo 'Dominio:';
            break;
            case 'Properties as a real function':
                echo 'Dominio:';
            break;
            case 'Derivative':
                echo 'Derivata:';
            break;
            case 'Global minimum':
                echo 'Minimo:';
            break;
            case 'Global maximum':
                echo 'Massimo:';
            break;
            case 'Limit':
                echo 'Limiti:';
            break;
            case 'Alternate form assuming x and y are positive':
                echo 'Forma di scrittura immaginando x ed y come positivi:';
            break;
            case 'Implicit derivatives':
                echo 'Derivate implicite:';
            break;
            case 'Local maximum':
                echo 'Massimo (locale):';
            break;
            case 'Local minimum':
                echo 'Minimo (locale):';
            break;
            case 'Alternate form assuming x and y are real':
                echo 'Forma di scrittura immaginando x ed y come reali:';
            break;
        }
        /*if ($pod->attributes['title']=="Input"){
            //grafici
            echo 'Funzione di x:';
        }
        if ($pod->attributes['title']=="Plots"){
            //grafici
            echo 'Grafici:';
        }*/
        
        #echo $pod->attributes['title'].':';
        
        foreach ( $pod->getSubpods() as $subpod ) {
            //var_dump($subpod);
            echo '<td align="center">';
            echo '<img src="'.$subpod->image->attributes['src'].'">';
            echo '</td>';
        }
    }
}
?>


</body>
</html>