<?php
class GD_Adapter implements Canvas {
  private $_dompdf;
  private $_img;
  private $_width;
  private $_height;
  private $_page_number;
  private $_page_count;
  private $_aa_factor;
  private $_colors;
  private $_bg_color;
  function __construct($size, $orientation = "portrait", DOMPDF $dompdf, $aa_factor = 1.0, $bg_color = array(1,1,1,0) ) {
    if ( !is_array($size) ) {
      $size = strtolower($size);
      if ( isset(CPDF_Adapter::$PAPER_SIZES[$size]) ) {
        $size = CPDF_Adapter::$PAPER_SIZES[$size];
      }
      else {
        $size = CPDF_Adapter::$PAPER_SIZES["letter"];
      }
    }
    if ( strtolower($orientation) === "landscape" ) {
      list($size[2],$size[3]) = array($size[3],$size[2]);
    }
    $this->_dompdf = $dompdf;
    if ( $aa_factor < 1 ) {
      $aa_factor = 1;
    }
    $this->_aa_factor = $aa_factor;
    $size[2] *= $aa_factor;
    $size[3] *= $aa_factor;
    $this->_width = $size[2] - $size[0];
    $this->_height = $size[3] - $size[1];
    $this->_img = imagecreatetruecolor($this->_width, $this->_height);
    if ( is_null($bg_color) || !is_array($bg_color) ) {
      $bg_color = array(1,1,1,0);
    }
    $this->_bg_color = $this->_allocate_color($bg_color);
    imagealphablending($this->_img, true);
    imagesavealpha($this->_img, true);
    imagefill($this->_img, 0, 0, $this->_bg_color);
  }
  function get_dompdf(){
    return $this->_dompdf;
  }
  function get_image() { return $this->_img; }
  function get_width() { return $this->_width / $this->_aa_factor; }
  function get_height() { return $this->_height / $this->_aa_factor; }
  function get_page_number() { return $this->_page_number; }
  function get_page_count() { return $this->_page_count; }
  function set_page_number($num) { $this->_page_number = $num; }
  function set_page_count($count) {  $this->_page_count = $count; }
  function set_opacity($opacity, $mode = "Normal") {
  }
  private function _allocate_color($color) {
    if ( isset($color["c"]) ) {
      $color = cmyk_to_rgb($color);
    }
    if ( !isset($color[3]) ) 
      $color[3] = 0;
    list($r,$g,$b,$a) = $color;
    $r *= 255;
    $g *= 255;
    $b *= 255;
    $a *= 127;
    $r = $r > 255 ? 255 : $r;
    $g = $g > 255 ? 255 : $g;
    $b = $b > 255 ? 255 : $b;
    $a = $a > 127 ? 127 : $a;
    $r = $r < 0 ? 0 : $r;
    $g = $g < 0 ? 0 : $g;
    $b = $b < 0 ? 0 : $b;
    $a = $a < 0 ? 0 : $a;
    $key = sprintf("#%02X%02X%02X%02X", $r, $g, $b, $a);
    if ( isset($this->_colors[$key]) )
      return $this->_colors[$key];
    if ( $a != 0 ) 
      $this->_colors[$key] = imagecolorallocatealpha($this->_img, $r, $g, $b, $a);
    else
      $this->_colors[$key] = imagecolorallocate($this->_img, $r, $g, $b);
    return $this->_colors[$key];
  }
  function line($x1, $y1, $x2, $y2, $color, $width, $style = null) {
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $x2 *= $this->_aa_factor;
    $y2 *= $this->_aa_factor;
    $width *= $this->_aa_factor;
    $c = $this->_allocate_color($color);
    if ( !is_null($style) ) {
      $gd_style = array();
      if ( count($style) == 1 ) {
        for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) {
          $gd_style[] = $c;
        }
        for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) {
          $gd_style[] = $this->_bg_color;
        }
      } else {
        $i = 0;
        foreach ($style as $length) {
          if ( $i % 2 == 0 ) {
            for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) 
              $gd_style[] = $c;
          } else {
            for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) 
              $gd_style[] = $this->_bg_color;
          }
          $i++;
        }
      }
      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }
    imagesetthickness($this->_img, $width);
    imageline($this->_img, $x1, $y1, $x2, $y2, $c);
  }
  function arc($x1, $y1, $r1, $r2, $astart, $aend, $color, $width, $style = array()) {
  }
  function rectangle($x1, $y1, $w, $h, $color, $width, $style = null) {
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;
    $c = $this->_allocate_color($color);
    if ( !is_null($style) ) {
      $gd_style = array();
      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }
      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }
    imagesetthickness($this->_img, $width);
    imagerectangle($this->_img, $x1, $y1, $x1 + $w, $y1 + $h, $c);
  }
  function filled_rectangle($x1, $y1, $w, $h, $color) {
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;
    $c = $this->_allocate_color($color);
    imagefilledrectangle($this->_img, $x1, $y1, $x1 + $w, $y1 + $h, $c);
  }
  function clipping_rectangle($x1, $y1, $w, $h) {
  }
  function clipping_roundrectangle($x1, $y1, $w, $h, $rTL, $rTR, $rBR, $rBL) {
  }
  function clipping_end() {
  }
  function save() {
  }
  function restore() {
  }
  function rotate($angle, $x, $y) {
  }
  function skew($angle_x, $angle_y, $x, $y) {
  }
  function scale($s_x, $s_y, $x, $y) {
  }
  function translate($t_x, $t_y) {
  }
  function transform($a, $b, $c, $d, $e, $f) {
  }
  function polygon($points, $color, $width = null, $style = null, $fill = false) {
    foreach (array_keys($points) as $i)
      $points[$i] *= $this->_aa_factor;
    $c = $this->_allocate_color($color);
    if ( !is_null($style) && !$fill ) {
      $gd_style = array();
      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }
      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }
    imagesetthickness($this->_img, $width);
    if ( $fill ) 
      imagefilledpolygon($this->_img, $points, count($points) / 2, $c);
    else
      imagepolygon($this->_img, $points, count($points) / 2, $c);
  }
  function circle($x, $y, $r, $color, $width = null, $style = null, $fill = false) {
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;
    $r *= $this->_aa_factor;
    $c = $this->_allocate_color($color);
    if ( !is_null($style) && !$fill ) {
      $gd_style = array();
      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }
      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }
    imagesetthickness($this->_img, $width);
    if ( $fill )
      imagefilledellipse($this->_img, $x, $y, $r, $r, $c);
    else
      imageellipse($this->_img, $x, $y, $r, $r, $c);
  }
  function image($img_url, $x, $y, $w, $h, $resolution = "normal") {
    $img_type = Image_Cache::detect_type($img_url);
    $img_ext  = Image_Cache::type_to_ext($img_type);
    if ( !$img_ext ) {
      return;
    }
    $func = "imagecreatefrom$img_ext";
    $src = @$func($img_url);
    if ( !$src ) {
      return; 
    }
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;
    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;
    $img_w = imagesx($src);
    $img_h = imagesy($src);
    imagecopyresampled($this->_img, $src, $x, $y, 0, 0, $w, $h, $img_w, $img_h);
  }
  function text($x, $y, $text, $font, $size, $color = array(0,0,0), $word_spacing = 0.0, $char_spacing = 0.0, $angle = 0.0) {
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;
    $size *= $this->_aa_factor;
    $h = $this->get_font_height($font, $size);
    $c = $this->_allocate_color($color);
    $text = mb_encode_numericentity($text, array(0x0080, 0xff, 0, 0xff), 'UTF-8');
    $font = $this->get_ttf_file($font);
    @imagettftext($this->_img, $size, $angle, $x, $y + $h, $c, $font, $text);
  }
  function javascript($code) {
  }
  function add_named_dest($anchorname) {
  }
  function add_link($url, $x, $y, $width, $height) {
  }
  function add_info($label, $value) {
  }
  function set_default_view($view, $options = array()) {
  }
  function get_text_width($text, $font, $size, $word_spacing = 0.0, $char_spacing = 0.0) {
    $font = $this->get_ttf_file($font);
    $text = mb_encode_numericentity($text, array(0x0080, 0xffff, 0, 0xffff), 'UTF-8');
    list($x1,,$x2) = @imagettfbbox($size, 0, $font, $text);
    return $x2 - $x1;
  }
  function get_ttf_file($font) {
    if ( strpos($font, '.ttf') === false )
      $font .= ".ttf";
    return $font;
  }
  function get_font_height($font, $size) {
    $font = $this->get_ttf_file($font);
    $ratio = $this->_dompdf->get_option("font_height_ratio");
    list(,$y2,,,,$y1) = imagettfbbox($size, 0, $font, "MXjpqytfhl");  
    return ($y2 - $y1) * $ratio;
  }
  function get_font_baseline($font, $size) {
    $ratio = $this->_dompdf->get_option("font_height_ratio");
    return $this->get_font_height($font, $size) / $ratio;
  }
  function new_page() {
    $this->_page_number++;
    $this->_page_count++;
  }    
  function open_object(){
  }
  function close_object(){
  }
  function add_object(){
  }
  function page_text(){
  }
  function stream($filename, $options = null) {
    if ( $this->_aa_factor != 1 ) {
      $dst_w = $this->_width / $this->_aa_factor;
      $dst_h = $this->_height / $this->_aa_factor;
      $dst = imagecreatetruecolor($dst_w, $dst_h);
      imagecopyresampled($dst, $this->_img, 0, 0, 0, 0,
                         $dst_w, $dst_h,
                         $this->_width, $this->_height);
    } else {
      $dst = $this->_img;
    }
    if ( !isset($options["type"]) )
      $options["type"] = "png";
    $type = strtolower($options["type"]);
    header("Cache-Control: private");
    switch ($type) {
    case "jpg":
    case "jpeg":
      if ( !isset($options["quality"]) )
        $options["quality"] = 75;
      header("Content-type: image/jpeg");
      imagejpeg($dst, '', $options["quality"]);
      break;
    case "png":
    default:
      header("Content-type: image/png");
      imagepng($dst);
      break;
    }
    if ( $this->_aa_factor != 1 ) 
      imagedestroy($dst);
  }
  function output($options = null) {
    if ( $this->_aa_factor != 1 ) {
      $dst_w = $this->_width / $this->_aa_factor;
      $dst_h = $this->_height / $this->_aa_factor;
      $dst = imagecreatetruecolor($dst_w, $dst_h);
      imagecopyresampled($dst, $this->_img, 0, 0, 0, 0,
                         $dst_w, $dst_h,
                         $this->_width, $this->_height);
    } else {
      $dst = $this->_img;
    }
    if ( !isset($options["type"]) )
      $options["type"] = "png";
    $type = $options["type"];
    ob_start();
    switch ($type) {
    case "jpg":
    case "jpeg":
      if ( !isset($options["quality"]) )
        $options["quality"] = 75;
      imagejpeg($dst, '', $options["quality"]);
      break;
    case "png":
    default:
      imagepng($dst);
      break;
    }
    $image = ob_get_clean();
    if ( $this->_aa_factor != 1 )
      imagedestroy($dst);
    return $image;
  }
}