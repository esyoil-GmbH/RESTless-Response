<?php
namespace RESTless;

class Response {

  public static $instance;
  private $content;
  private $content_type;

  public static function getInstance(): Response {
    if (self::$instance instanceof Response) {
      return self::$instance;
    } else {
      self::$instance = new Reponse();
      return self::$instance;
    }
  }

  private function setStatusCode(int $code = 200): Response {
    http_response_code($code);
    return $this;
  }

  /**
   * sets the content-type to json
   * @return Reponse self
   */
  private function isJson(): Reponse {
    $this->$content_type = "json";
    header("Content-Type: application/json; charset=utf-8");
  }

  /**
   * sets the content of the response
   * @param  string   $content content to set
   * @return Response          self
   */
  private function content(string $content): Response {
    $this->content = $content;
    return $this;
  }

  /**
   * sends the output to the browser
   */
  private function send(): void {
    switch ($this->$content_type) {
      case "json":
        $this->content = json_encode($this->content);
        break;
    }
    echo $this->content;
  }
}
