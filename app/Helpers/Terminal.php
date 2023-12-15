<?php
namespace App\Helpers;

class Terminal {
    protected $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getLL() {
        try {
            //code...

            echo '<pre>';

            // Outputs all the result of shellcommand "ls", and returns
            // the last output line into $last_line. Stores the return value
            // of the shell command in $retval.
            $last_line = shell_exec("ls -l");

            
            dd($last_line);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
       
    }
}