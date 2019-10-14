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
      self::$instance = new self();
      return self::$instance;
    }
  }

  /**
   * set the status code of the answer
   * @param integer $code status code
   */
  public function setStatusCode(int $code = 200) {
    http_response_code($code);
    return $this;
  }

  /**
   * sets the content-type to json
   * @return Reponse self
   */
  public function isJson() {
    $this->$content_type = "json";
    header("Content-Type: application/json; charset=utf-8");
    return $this;
  }

  /**
   * sets the content of the response
   * @param  string   $content content to set
   * @return Response          self
   */
  public function content(array $content) {
    $this->content = $content;
    return $this;
  }

  /**
   * set cors headers
   *
   * @param string $source the sources allowed
   * @return void
   */
  public function cors(string $source) {
    header("Access-Control-Allow-Origin: " . $source);
    return $this;
  }

  /**
   * sends the output to the browser
   */
  public function send(): void {
    switch ($this->content_type) {
      case "json":
        $this->content = json_encode($this->content);
        break;
    }
    echo $this->content;
  }
}
