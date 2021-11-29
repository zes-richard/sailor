<?php namespace Spawnia\Sailor\Codegen;

class Printer extends \Nette\PhpGenerator\Printer
{
    /** @var int */
    public $wrapLength = 180;

    /** @var string */
    protected $indentation = '    ';

    /** @var int */
    protected $linesBetweenMethods = 1;

    /** @var int */
    protected $linesBetweenProperties = 1;
}
