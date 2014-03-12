<?php
    class tgapng {
        //constructor
        public function __construct($format_res, $flname) {
            $image = $this->imagecreate('Original/'.$format_res);
            imagepng($image, 'Recode/'.$flname.'.png');
        }

        function imagecreate($filename){
            $f = fopen($filename, 'rb');
            $header = fread($f, 18);
            $header = unpack(   "cimage_id_len/ccolor_map_type/cimage_type/vcolor_map_origin/vcolor_map_len/" .
                                "ccolor_map_entry_size/vx_origin/vy_origin/vwidth/vheight/" .
                                "cpixel_size/cdescriptor", $header);
            
            if ($header['image_id_len'] > 0) $header['image_id'] = fread($f, $header['image_id_len']);
            else $header['image_id'] = '';   
            
            $im = imagecreatetruecolor($header['width'], $header['height']);
            
            $size = $header['width'] * $header['height'] * 3;
             
            $pos = ftell($f);
            fseek($f, -26, SEEK_END);   
            $newtga = fread($f, 26);
            
            fseek($f, 0, SEEK_END);
            $datasize = ftell($f) - $pos; 
            if ($newtga) $datasize -= 26;
            
            fseek($f, $pos, SEEK_SET);

            $data = fread($f, $datasize);   

            if ($this->bit5($header['descriptor']) == 1) $mirrow = true;    
            else $mirrow = false;   
            
            $i = 0;
            $num_bytes = $header['pixel_size']/8;
            //var_dump($data);
            $pixels = str_split($data, $num_bytes);

            //read pixels 
            if ($mirrow == true) {   
                for ($y=0; $y<$header['height']; $y++) {       
                    for ($x=0; $x<$header['width']; $x++) {
                        imagesetpixel($im, $x, $y, $this->dwordize($pixels[$i]));
                        $i++;
                    }
                }
            }
            else {
                for ($y=$header['height']-1; $y>=0; $y--) {       
                    for ($x=0; $x<$header['width']; $x++) {
                        imagesetpixel($im, $x, $y, $this->dwordize($pixels[$i]));
                        $i++;
                    }
                }
            }
            fclose($f);         
            
            return $im;
        }

        function dwordize($str) {
            $a = ord($str[0]);
            $b = ord($str[1]);
            $c = ord($str[2]);
            return $c*256*256 + $b*256 + $a;
        }

        function bit5($x) {
            return ($x & 32) >> 5;  
        }
    }

?>