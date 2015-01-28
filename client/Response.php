<?php

// @todo: add cache support
class Response
{
    public static function redirect($location, $timeout = 0, $status = null) 
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

    public static function download($fileURL, $filename = null)
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

    public static function parse($__file, $vars)
    {
        extract($vars);
        //include(self::parseFilePath($__file));
        include($__file);
    }

    public static function returnParsed($file, $vars)
    {
        ob_start();
        self::parse($file, $vars);
        return ob_get_clean();
    }

    /*
    private static function parseFilePath($filePath) 
    {
        // Path from filePath.
        if (mb_strrpos($filePath, '/') > 0) {
            set_include_path(mb_substr($filePath, 0, mb_strrpos($filePath, '/') + 1));
            return mb_substr($filePath, mb_strrpos($filePath, '/') + 1);
        }
        else {
            return $filePath;
        }
    }*/
}

?>