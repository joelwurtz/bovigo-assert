<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Wrap;

use function bovigo\assert\predicate\equals;
/**
 * Allows to make assertions on a catched error.
 *
 * @since  2.1.0
 */
class CatchedError
{
    /**
     * map of error level numbers and their textual name
     */
    const LEVEL = [
                E_ERROR             => 'E_ERROR',
                E_WARNING           => 'E_WARNING',
                E_PARSE             => 'E_PARSE',
                E_NOTICE            => 'E_NOTICE',
                E_CORE_ERROR        => 'E_CORE_ERROR',
                E_CORE_WARNING      => 'E_CORE_WARNING',
                E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
                E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
                E_USER_ERROR        => 'E_USER_ERROR',
                E_USER_WARNING      => 'E_USER_WARNING',
                E_USER_NOTICE       => 'E_USER_NOTICE',
                E_STRICT            => 'E_STRICT',
                E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                E_DEPRECATED        => 'E_DEPRECATED',
                E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
                E_ALL               => 'E_ALL'
    ];

    /**
     * returns the name of the integer error level
     *
     * @param   int  $level
     * @return  string
     */
    public static function nameOf(int $level): string
    {
        return self::LEVEL[$level] ?? 'Unknown error ' . $level;
    }

    private $errno;
    private $errstr;
    private $errfile;
    private $errline;
    private $errcontext;

    /**
     * constructor
     *
     * @param  int     $errno
     * @param  string  $errstr
     * @param  string  $errfile
     * @param  int     $errline
     * @param  array   $errcontext
     */
    public function __construct(int $errno , string $errstr, string $errfile, int $errline, array $errcontext = [])
    {
        $this->errno      = $errno;
        $this->errstr     = $errstr;
        $this->errfile    = $errfile;
        $this->errline    = $errline;
        $this->errcontext = $errcontext;
    }

    /**
     * returns the error level of the catched error
     *
     * @return  int
     */
    public function level(): int
    {
        return $this->errno;
    }

    /**
     * returns the error level name of the catched error
     *
     * @return  string
     */
    public function name(): string
    {
        return self::nameOf($this->errno);
    }

    /**
     * returns the actual error strin, i.e. the error message
     *
     * @return  string
     */
    public function errstr(): string
    {
        return $this->errstr;
    }

    /**
     * asserts actual error message equals expected message
     *
     * @api
     * @param   string  $expectedMessage
     * @return  \bovigo\assert\CatchedError
     */
    public function withMessage(string $expectedMessage): self
    {
        return $this->message(equals($expectedMessage));
    }

    /**
     * asserts actual error message fulfills given predicate
     *
     * @api
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate
     * @return  \bovigo\assert\CatchedError
     */
    public function message(callable $predicate): self
    {
        assert($this->errstr, new Wrap($predicate, 'error message %s'));
        return $this;
    }

    /**
     * returns file in which the error was triggered
     *
     * @return  string
     */
    public function file(): string
    {
        return $this->errfile;
    }

    /**
     * returns the line on which the error was triggered
     *
     * @return  int
     */
    public function line(): int
    {
        return $this->errline;
    }

    /**
     * asserts anything after the error was catched
     *
     * @api
     * @param   mixed                                        $value        value to test
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\Expectation
     */
    public function after($value, callable $predicate, string $description = null): self
    {
        assert($value, $predicate, $description);
        return $this;
    }
}
