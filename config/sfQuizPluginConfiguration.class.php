<?php
class sfQuizPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('user.method_not_found', array('QuizStart', 'methodNotFound'));
  }
}