<?php

class Sql extends PDO {

  private $conn;

  public function __construct() {

   // $this->conn = new PDO( "mysql:dbname=david682_mvjob;local=br212.hostgator.com.br:3306", "david682_mvjob", "@M47zed5iir" );
    $this->conn = new PDO( "mysql:dbname=david682_mv;local=https://162.240.162.180:2086", "david682_mvjob", "@M47zed5iir" );


  }


  // tratando a passagem de vários parametros para executar comando sqls  no banco

  private function setParams( $statement, $parameters = array() ) {

    foreach ( $parameters as $key => $value ) {

      if ( is_array( $value ) ) {

        foreach ( $value as $key2 => $vl ) {

          $this->setParam( $statement, $key2, $vl );

        }

      } else {

        $this->setParam( $statement, $key, $value );

      }
    }

  }

  // tratando quando é passado apenas um parametro para executar comandos sqls no banco
  private function setParam( $statement, $key, $value ) {

    $statement->bindParam( $key, $value );

  }


  // função genéria para executar todos o tipos de comandos sql no banco

  public function run( $cmdSql, $params = array() ) {

    $stmt = $this->conn->prepare( $cmdSql );

    $this->setParams( $stmt, $params );

    $stmt->execute();
      
    return $stmt;

  }


  // função de SELECT, pois é a únida que retorna dados que estão no banco.

  public function select( $cmdSql, $params = array() ): array // esse ': array' é função nova do php7, determina que a função retorna um array
  
  {

    $stmt = $this->run( $cmdSql, $params );

    return $stmt->fetchAll( PDO::FETCH_ASSOC );

  }

  public function select2( $cmdSql, $params = array()) :array  // esse ': array' é função nova do php7, determina que a função retorna um array
  
  {

    $stmt = $this->run( $cmdSql, $params );

    return $stmt->fetchAll(PDO::FETCH_NAMED );

  }

     
 }

?>