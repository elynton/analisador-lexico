<?php
/**
 * @author Elynton Fellipe Bazzo
 */
class TokenReader extends Parser
{
  public function __construct(Tokenizer $source)
  {
    parent::__construct($source);
  }

  public function arithmetic()
  {
    $lookahead = $this->lookahead;
    $this->expr();
    $this->match(Tokenizer::T_SEMICOLON);
  }

  private function isOperator()
  {
    switch ($this->lookahead->key) {
      case Tokenizer::T_PLUS:
      case Tokenizer::T_MINUS:
      case Tokenizer::T_DIVISION:
      case Tokenizer::T_MULTIPLICATION:
        return true;
      default:
        return false;
    }
  }

  private function isSecondaryOperator()
  {
    return $this->lookahead->key === Tokenizer::T_PLUS
        || $this->lookahead->key === Tokenizer::T_MINUS;
  }

  private function isPrimaryOperator()
  {
    return $this->lookahead->key === Tokenizer::T_MULTIPLICATION
        || $this->lookahead->key === Tokenizer::T_DIVISION;
  }

  public function digit()
  {
    return $this->match(Tokenizer::T_INTEGER, Tokenizer::T_DOUBLE);
  }

  public function primaryOperator()
  {
    return $this->match(Tokenizer::T_DIVISION, Tokenizer::T_MULTIPLICATION);
  }

  public function secondaryOperator()
  {
    return $this->match(Tokenizer::T_PLUS, Tokenizer::T_MINUS);
  }

  public function expr()
  {
    $x  = $this->term();
    $xs = [];

    while ($this->isSecondaryOperator()) {
      $operator = $this->secondaryOperator();
      $term     = $this->term();
    }
  }

  public function term()
  {
    $x  = $this->factor();
    $xs = [];

    while ($this->isPrimaryOperator()) {
      $operator = $this->primaryOperator();
      $factor   = $this->factor();
    }

    // return
  }

  public function factor()
  {
    if ($this->lookahead->key === Tokenizer::T_LPAREN) {
      $this->match(Tokenizer::T_LPAREN);
      $factor = $this->expr();
      $this->match(Tokenizer::T_RPAREN);
    } else {
      $factor = $this->digit();
    }

    return $factor;
  }
}

/*
expression = term  { ("+" | "-") term} .
term       = factor  { ("*"|"/") factor} .
factor     = constant | "("  expression  ")" .
constant   = digit {digit} .
digit      = "0" | "1" | "..." | "9" .
*/