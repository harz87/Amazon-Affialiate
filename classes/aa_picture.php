<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aa_picture
 *
 * @author h_titz
 */
class AA_Picture {
     /**
     *
     * @var integer $id
     *
     */
     private $id;
     /**
      *
      * @var string
      *
      */
     private $filename;

      /**
      *
      * @var string
      *
      */

     private $title;

     /**
     *
     * @var integer
     *
     */
     private $item_id;

     /**
      * path where files will be saved
      * @var string
      */
     private $path;

     private $source;
     /**
      * if false usage of an unknown extension
      * @return bool;
      */
     private $extension = true;

     public function __get($property)
    {
        return $this->$property;
    }
    public function __set($property,$value)
    {
        $this->$property = $value;
    }

    public function  __construct($title, $filename, $url, $itemId) {
        $this->title = $title;
        $this->filename = $filename;
        $this->source = $url;
        $this->item_id = $itemId;

        $this->path = WP_PLUGIN_DIR.get_option('dbaa_images_path');
    }
    public function download($method="curl"){
        $info = @GetImageSize($this->source);
        $mime = $info['mime'];

        // What sort of image?
        $type = substr(strrchr($mime, '/'), 1);

        switch ($type)
        {
        case 'jpeg':
            $image_create_func = 'ImageCreateFromJPEG';
            $image_save_func = 'ImageJPEG';
            $new_image_ext = 'jpg';

            // Best Quality: 100
            $quality = isSet($this->quality) ? $this->quality : 100;
        break;

        case 'png':
            $image_create_func = 'ImageCreateFromPNG';
            $image_save_func = 'ImagePNG';
            $new_image_ext = 'png';

            // Compression Level: from 0  (no compression) to 9
            $quality = isSet($this->quality) ? $this->quality : 0;
        break;

        case 'bmp':
            $image_create_func = 'ImageCreateFromBMP';
            $image_save_func = 'ImageBMP';
            $new_image_ext = 'bmp';
        break;

        case 'gif':
            $image_create_func = 'ImageCreateFromGIF';
            $image_save_func = 'ImageGIF';
            $new_image_ext = 'gif';
        break;

        default:
            $image_create_func = 'ImageCreateFromJPEG';
            $image_save_func = 'ImageJPEG';
            $new_image_ext = 'jpg';
        }

        if(isset($this->extension)) {
            $ext = strrchr($this->source, ".");
            $strlen = strlen($ext);
            $new_name = $this->filename.'.'.$new_image_ext;
            $this->filename = $new_name;
        }
        else {
            $new_name = basename($this->source);
        }
        $save_to = $this->path.$new_name;

        if($method == 'curl') {
            $save_image = $this->LoadImageCURL($save_to);
	}
	elseif($method == 'gd') {
            $img = $image_create_func($this->source);
	    if(isSet($quality)) {
                $save_image = $image_save_func($img, $save_to, $quality);
            }
            else {
                $save_image = $image_save_func($img, $save_to);
            }
	}
        $this->save();
	return $save_image;
    }

    private function LoadImageCURL($save_to)
    {
        $ch = curl_init($this->source);
        $fp = fopen($save_to, "wb");

        // set URL and other appropriate options
        $options = array(CURLOPT_FILE => $fp,
                         CURLOPT_HEADER => 0,
                         CURLOPT_FOLLOWLOCATION => 1,
                         CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)

        curl_setopt_array($ch, $options);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    public function save()
    {
        global $wpdb;
        $table = AA_PICTURES_TABLE;
        $format = array('%d','%s','%s');
        $wpdb->insert($table, array('item_id'=>$this->item_id, 'picture_filename'=>$this->filename, 'picture_title'=>$this->title),$format);
    }

}

?>
