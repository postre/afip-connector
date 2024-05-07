<?php 

namespace AfipConnector\Exceptions;

class EvalAfipException
{

    public function __construct($message,$code=0)
    {
        if($_ENV['JSON']===true){
            echo json_encode([
                'status' => 'error',
                'code' => $code,
                'message' => $message,
            ]);
            die();
        }

        throw new AfipException($message,$code);
    }

}