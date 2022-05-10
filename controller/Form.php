<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("marca", "");
    $form->set("configuracao", "");
    $form->set("valor", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST['marca']) && isset($_POST['configuracao']) && isset($_POST['valor'])) {
      try {
        $conexao = Transaction::get();
        $computador = new Crud('computador');
        $marca = $conexao->quote($_POST['marca']);
        $configuracao = $conexao->quote($_POST['configuracao']);
        $valor = $conexao->quote($_POST['valor']);
        if (empty($_POST["id"])) {
          $computador->insert("marca,configuracao,valor", "$marca,$configuracao,$valor");
        } else {
          $id = $conexao->quote($_POST['id']);
          $computador->update("marca=$marca,configuracao=$configuracao,valor=$valor", "id=$id");
        }
        $this->message = $computador->getMessage();
        $this->error = $computador->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $computador = new Crud('computador');
        $resultado = $computador->select("*", "id=$id");
        if (!$computador->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $valor) {
            $form->set($cod, $valor);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $computador->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}
