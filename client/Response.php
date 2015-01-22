<?php

class Response
{
    public function redirect($location, $timeout = 0, $status = null) 
    {
        if ($status) {
            if ($timeout > 0) {
                header("Refresh:$timeout;url=$location");
            } else {
                header("Location:$location");
                exit;
            }
        }
        else {
            if ($timeout > 0) {
                header("Refresh:$timeout;url=$location", true, $status);
            } else {
                header("Location:$location", true, $status);
                exit;
            }
        }
    }

    // @todo: add cache support
    public function download($fileURL, $filename = null)
    {
        // No filename? Get it from $file
        if (empty($filename)) {
            $filenameStartsAt = mb_strrpos($fileURL, '/');
            $filename = !$filenameStartsAt ? $fileURL : mb_substr($fileURL, $filenameStartsAt);
        }

        $fileInfo = new finfo(FILEINFO_MIME);
        
        header("Content-Type: " . $fileInfo->file($fileURL));
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        
        readfile($fileURL);
    }
}

?>