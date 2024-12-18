<?php
namespace Hikvision;

use RuntimeException;

/**
 * Custom exception class for Hikvision API errors.
 *
 * This class extends the built-in RuntimeException to provide a specific exception type
 * for errors related to interacting with Hikvision devices or the Hikvision API.
 *
 * By using this custom exception, you can catch and handle Hikvision-specific errors
 * in a structured way, distinguishing them from general runtime exceptions.
 */
class Exception extends RuntimeException
{
    /**
     * Constructor for Exception.
     *
     * This constructor passes the error message and optional error code to the parent
     * RuntimeException constructor, allowing you to create custom error messages for Hikvision-related issues.
     *
     * @param string $message The error message to be passed to the exception.
     * @param int $code Optional error code.
     * @param \Throwable|null $previous Optional previous exception for chaining.
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        // Pass the parameters to the parent constructor
        parent::__construct($message, $code, $previous);
    }
}
