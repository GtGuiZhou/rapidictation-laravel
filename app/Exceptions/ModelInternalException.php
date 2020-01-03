<?php


namespace App\Exceptions;
use Exception;
use Illuminate\Http\Request;
class ModelInternalException extends Exception
{
    public function __construct(string $message = "", int $code = 400)
    {
        parent::__construct($message, $code);
    }
    public function render(Request $request)
    {
        return response()->json(['message' => $this->message], $this->code);
    }
}