<?php
/**
 * Class InstaWidget | classes\InstaWidget.php
 *
 * @package classes
 */
namespace classes;

/**
 * Class FileAction
 * File actions
 * @package classes
 */
class FileAction
{
    /**
     * File Name
     * @var string
     */
    private $filepath = "niknames.json";

    /**
     * @var array|mixed array of niksname
     */
    private $niks = [];

    /**
     * FileAction constructor.
     * Getting info from file and put data to the niks attribute
     */
    public function __construct()
    {
        if(file_exists($this->filepath)) {
            $this->niks = json_decode(file_get_contents($this->filepath), true);
        }
    }

    /**
     * Пetter for attribute niks
     * @return array|mixed
     */
    public function getNiks()
    {
        return $this->niks;
    }

    public function upload()
    {
        $fileBlob = 'input-b7';                      // the parameter name that stores the file blob
        if (isset($_FILES[$fileBlob])) {
            $file = $_FILES[$fileBlob]['tmp_name'][0] ?? false;  // the path for the uploaded file chunk
            if ($file && move_uploaded_file($file, '../' . $this->filepath)) {
                // get list of all chunks uploaded so far to server
                return json_encode([
                    'success' => 'File was upload '
                ]);

            } else {
                return json_encode([
                    'error' => 'Error uploading chunk ' . $_POST['chunkIndex']
                ]);
            }
        }
        return json_encode([
            'error' => 'No file found'
        ]);
    }
}
