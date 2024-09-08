<?php
class PHP_Email_Form {
  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';
  public $smtp = array();
  public $messages = array();
  public $ajax = false;

  public function add_message($content, $label, $priority = 0) {
    $this->messages[] = array(
      'content' => $content,
      'label' => $label,
      'priority' => $priority
    );
  }

  public function send() {
    $email_text = "You have received a new message:\n=============================\n";

    foreach ($this->messages as $message) {
      $email_text .= $message['label'] . ": " . $message['content'] . "\n";
    }

    $headers = 'From: ' . $this->from_name . ' <' . $this->from_email . '>' . "\r\n" .
               'Reply-To: ' . $this->from_email . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    if (!empty($this->smtp)) {
      // Use SMTP
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->Host = $this->smtp['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtp['username'];
        $mail->Password = $this->smtp['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $this->smtp['port'];

        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addAddress($this->to);
        $mail->Subject = $this->subject;
        $mail->Body = $email_text;

        $mail->send();
        return 'OK';
      } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
      }
    } else {
      // Use PHP mail()
      if (mail($this->to, $this->subject, $email_text, $headers)) {
        return 'OK';
      } else {
        return 'Message could not be sent.';
      }
    }
  }
}
?>
