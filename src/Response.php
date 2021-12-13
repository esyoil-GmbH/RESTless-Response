<?php
namespace RESTless;

class Response {

  public static $instance;
  
  private $content = null;
  private $errors = null;
  private $meta = null;

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
    $this->content_type = "json";
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
   * Adds a meta element to the response. Can contain anthing.
   *
   * @param array $meta
   * @return void
   */
  public function meta(array $meta) {
    $this->meta[] = $meta;
    return $this;
  }

  /**
   * Adds an error to the error list
   *
   * @param [type] $id
   * @param integer $status
   * @param string $title
   * @param string $detail
   * @return void
   */
  public function error($id, $status = 400, $title = "Bad Request", $detail = "Malformed Request") {
    $this->errors[] = [
      'id' => $id,
      'status' => $status,
      'title' => $title,
      'detail' => $detail
    ];
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
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PATCH, DELETE");
    return $this;
  }

  /**
   * builds the final array
   *
   * @return array
   */
  public function build(): array {

    $final = [
      'errors' => false,
      'meta' => null,
      'data' => null
    ];

    if ($this->errors) {
      $final['errors'] = $this->errors;
    }
    if ($this->content) {
      $final['data'] = $this->content;
    }
    if ($this->meta) {
      $final['meta'] = $this->meta;
    }

    return $final;
  }

  /**
   * sends the output to the browser
   */
  public function send(): void {
    $this->content = $this->build();
    switch ($this->content_type) {
      case "json":
        $this->content = json_encode($this->content);
        break;
    }
    echo $this->content;
  }
}
