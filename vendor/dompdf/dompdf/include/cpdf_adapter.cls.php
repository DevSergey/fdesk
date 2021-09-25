<?php
require_once(DOMPDF_LIB_DIR . "/class.pdf.php");
class CPDF_Adapter implements Canvas {
  static $PAPER_SIZES = array(
    "4a0" => array(0,0,4767.87,6740.79),
    "2a0" => array(0,0,3370.39,4767.87),
    "a0" => array(0,0,2383.94,3370.39),
    "a1" => array(0,0,1683.78,2383.94),
    "a2" => array(0,0,1190.55,1683.78),
    "a3" => array(0,0,841.89,1190.55),
    "a4" => array(0,0,595.28,841.89),
    "a5" => array(0,0,419.53,595.28),
    "a6" => array(0,0,297.64,419.53),
    "a7" => array(0,0,209.76,297.64),
    "a8" => array(0,0,147.40,209.76),
    "a9" => array(0,0,104.88,147.40),
    "a10" => array(0,0,73.70,104.88),
    "b0" => array(0,0,2834.65,4008.19),
    "b1" => array(0,0,2004.09,2834.65),
    "b2" => array(0,0,1417.32,2004.09),
    "b3" => array(0,0,1000.63,1417.32),
    "b4" => array(0,0,708.66,1000.63),
    "b5" => array(0,0,498.90,708.66),
    "b6" => array(0,0,354.33,498.90),
    "b7" => array(0,0,249.45,354.33),
    "b8" => array(0,0,175.75,249.45),
    "b9" => array(0,0,124.72,175.75),
    "b10" => array(0,0,87.87,124.72),
    "c0" => array(0,0,2599.37,3676.54),
    "c1" => array(0,0,1836.85,2599.37),
    "c2" => array(0,0,1298.27,1836.85),
    "c3" => array(0,0,918.43,1298.27),
    "c4" => array(0,0,649.13,918.43),
    "c5" => array(0,0,459.21,649.13),
    "c6" => array(0,0,323.15,459.21),
    "c7" => array(0,0,229.61,323.15),
    "c8" => array(0,0,161.57,229.61),
    "c9" => array(0,0,113.39,161.57),
    "c10" => array(0,0,79.37,113.39),
    "ra0" => array(0,0,2437.80,3458.27),
    "ra1" => array(0,0,1729.13,2437.80),
    "ra2" => array(0,0,1218.90,1729.13),
    "ra3" => array(0,0,864.57,1218.90),
    "ra4" => array(0,0,609.45,864.57),
    "sra0" => array(0,0,2551.18,3628.35),
    "sra1" => array(0,0,1814.17,2551.18),
    "sra2" => array(0,0,1275.59,1814.17),
    "sra3" => array(0,0,907.09,1275.59),
    "sra4" => array(0,0,637.80,907.09),
    "letter" => array(0,0,612.00,792.00),
    "legal" => array(0,0,612.00,1008.00),
    "ledger" => array(0,0,1224.00, 792.00),
    "tabloid" => array(0,0,792.00, 1224.00),
    "executive" => array(0,0,521.86,756.00),
    "folio" => array(0,0,612.00,936.00),
    "commercial #10 envelope" => array(0,0,684,297),
    "catalog #10 1/2 envelope" => array(0,0,648,864),
    "8.5x11" => array(0,0,612.00,792.00),
    "8.5x14" => array(0,0,612.00,1008.0),
    "11x17"  => array(0,0,792.00, 1224.00),
  );
  private $_dompdf;
  private $_pdf;
  private $_width;
  private $_height;
  private $_page_number;
  private $_page_count;
  private $_page_text;
  private $_pages;
  private $_image_cache;
  function __construct($paper = "letter", $orientation = "portrait", DOMPDF $dompdf) {
    if ( is_array($paper) ) {
      $size = $paper;
    }
    else if ( isset(self::$PAPER_SIZES[mb_strtolower($paper)]) ) {
      $size = self::$PAPER_SIZES[mb_strtolower($paper)];
    }
    else {
      $size = self::$PAPER_SIZES["letter"];
    }
    if ( mb_strtolower($orientation) === "landscape" ) {
      list($size[2], $size[3]) = array($size[3], $size[2]);
    }
    $this->_dompdf = $dompdf;
    $this->_pdf = new Cpdf(
      $size,
      $dompdf->get_option("enable_unicode"),
      $dompdf->get_option("font_cache"),
      $dompdf->get_option("temp_dir")
    );
    $this->_pdf->addInfo("Creator", "DOMPDF");
    $time = substr_replace(date('YmdHisO'), '\'', -2, 0).'\'';
    $this->_pdf->addInfo("CreationDate", "D:$time");
    $this->_pdf->addInfo("ModDate", "D:$time");
    $this->_width = $size[2] - $size[0];
    $this->_height= $size[3] - $size[1];
    $this->_page_number = $this->_page_count = 1;
    $this->_page_text = array();
    $this->_pages = array($this->_pdf->getFirstPageId());
    $this->_image_cache = array();
  }
  function get_dompdf(){
    return $this->_dompdf;
  }
  function __destruct() {
    foreach ($this->_image_cache as $img) {
      if (!file_exists($img)) {
        continue;
      }
      if (DEBUGPNG) print '[__destruct unlink '.$img.']';
      if (!DEBUGKEEPTEMP) unlink($img);
    }
  }
  function get_cpdf() {
    return $this->_pdf;
  }
  function add_info($label, $value) {
    $this->_pdf->addInfo($label, $value);
  }
  function open_object() {
    $ret = $this->_pdf->openObject();
    $this->_pdf->saveState();
    return $ret;
  }
  function reopen_object($object) {
    $this->_pdf->reopenObject($object);
    $this->_pdf->saveState();
  }
  function close_object() {
    $this->_pdf->restoreState();
    $this->_pdf->closeObject();
  }
  function add_object($object, $where = 'all') {
    $this->_pdf->addObject($object, $where);
  }
  function stop_object($object) {
    $this->_pdf->stopObject($object);
  }
  function serialize_object($id) {
    return $this->_pdf->serializeObject($id);
  }
  function reopen_serialized_object($obj) {
    return $this->_pdf->restoreSerializedObject($obj);
  }
  function get_width() { return $this->_width; }
  function get_height() { return $this->_height; }
  function get_page_number() { return $this->_page_number; }
  function get_page_count() { return $this->_page_count; }
  function set_page_number($num) { $this->_page_number = $num; }
  function set_page_count($count) {  $this->_page_count = $count; }
  protected function _set_stroke_color($color) {
    $this->_pdf->setStrokeColor($color);
  }
  protected function _set_fill_color($color) {
    $this->_pdf->setColor($color);
  }
  protected function _set_line_transparency($mode, $opacity) {
    $this->_pdf->setLineTransparency($mode, $opacity);
  }
  protected function _set_fill_transparency($mode, $opacity) {
    $this->_pdf->setFillTransparency($mode, $opacity);
  }
  protected function _set_line_style($width, $cap, $join, $dash) {
    $this->_pdf->setLineStyle($width, $cap, $join, $dash);
  }
  function set_opacity($opacity, $mode = "Normal") {
    $this->_set_line_transparency($mode, $opacity);
    $this->_set_fill_transparency($mode, $opacity);
  }
  function set_default_view($view, $options = array()) {
    array_unshift($options, $view);
    call_user_func_array(array($this->_pdf, "openHere"), $options);
  }
  protected function y($y) {
    return $this->_height - $y;
  }
  function line($x1, $y1, $x2, $y2, $color, $width, $style = array()) {
    $this->_set_stroke_color($color);
    $this->_set_line_style($width, "butt", "", $style);
    $this->_pdf->line($x1, $this->y($y1),
                      $x2, $this->y($y2));
  }
  function arc($x, $y, $r1, $r2, $astart, $aend, $color, $width, $style = array()) {
    $this->_set_stroke_color($color);
    $this->_set_line_style($width, "butt", "", $style);
    $this->_pdf->ellipse($x, $this->y($y), $r1, $r2, 0, 8, $astart, $aend, false, false, true, false);
  }
  protected function _convert_gif_bmp_to_png($image_url, $type) {
    $image_type = Image_Cache::type_to_ext($type);
    $func_name = "imagecreatefrom$image_type";
    if ( !function_exists($func_name) ) {
      throw new DOMPDF_Exception("Function $func_name() not found.  Cannot convert $image_type image: $image_url.  Please install the image PHP extension.");
    }
    set_error_handler("record_warnings");
    $im = $func_name($image_url);
    if ( $im ) {
      imageinterlace($im, false);
      $tmp_dir = $this->_dompdf->get_option("temp_dir");
      $tmp_name = tempnam($tmp_dir, "{$image_type}dompdf_img_");
      @unlink($tmp_name);
      $filename = "$tmp_name.png";
      $this->_image_cache[] = $filename;
      imagepng($im, $filename);
      imagedestroy($im);
    } 
    else {
      $filename = Image_Cache::$broken_image;
    }
    restore_error_handler();
    return $filename;
  }
  function rectangle($x1, $y1, $w, $h, $color, $width, $style = array()) {
    $this->_set_stroke_color($color);
    $this->_set_line_style($width, "butt", "", $style);
    $this->_pdf->rectangle($x1, $this->y($y1) - $h, $w, $h);
  }
  function filled_rectangle($x1, $y1, $w, $h, $color) {
    $this->_set_fill_color($color);
    $this->_pdf->filledRectangle($x1, $this->y($y1) - $h, $w, $h);
  }
  function clipping_rectangle($x1, $y1, $w, $h) {
    $this->_pdf->clippingRectangle($x1, $this->y($y1) - $h, $w, $h);
  }
  function clipping_roundrectangle($x1, $y1, $w, $h, $rTL, $rTR, $rBR, $rBL) {
    $this->_pdf->clippingRectangleRounded($x1, $this->y($y1) - $h, $w, $h, $rTL, $rTR, $rBR, $rBL);
  }
  function clipping_end() {
    $this->_pdf->clippingEnd();
  }
  function save() {
    $this->_pdf->saveState();
  }
  function restore() {
    $this->_pdf->restoreState();
  }
  function rotate($angle, $x, $y) {
    $this->_pdf->rotate($angle, $x, $y);
  }
  function skew($angle_x, $angle_y, $x, $y) {
    $this->_pdf->skew($angle_x, $angle_y, $x, $y);
  }
  function scale($s_x, $s_y, $x, $y) {
    $this->_pdf->scale($s_x, $s_y, $x, $y);
  }
  function translate($t_x, $t_y) {
    $this->_pdf->translate($t_x, $t_y);
  }
  function transform($a, $b, $c, $d, $e, $f) {
    $this->_pdf->transform(array($a, $b, $c, $d, $e, $f));
  }
  function polygon($points, $color, $width = null, $style = array(), $fill = false) {
    $this->_set_fill_color($color);
    $this->_set_stroke_color($color);
    for ( $i = 1; $i < count($points); $i += 2) {
      $points[$i] = $this->y($points[$i]);
    }
    $this->_pdf->polygon($points, count($points) / 2, $fill);
  }
  function circle($x, $y, $r1, $color, $width = null, $style = null, $fill = false) {
    $this->_set_fill_color($color);
    $this->_set_stroke_color($color);
    if ( !$fill && isset($width) ) {
      $this->_set_line_style($width, "round", "round", $style);
    }
    $this->_pdf->ellipse($x, $this->y($y), $r1, 0, 0, 8, 0, 360, 1, $fill);
  }
  function image($img, $x, $y, $w, $h, $resolution = "normal") {
    list($width, $height, $type) = dompdf_getimagesize($img);
    $debug_png = $this->_dompdf->get_option("debug_png");
    if ($debug_png) print "[image:$img|$width|$height|$type]";
    switch ($type) {
    case IMAGETYPE_JPEG:
      if ($debug_png) print '!!!jpg!!!';
      $this->_pdf->addJpegFromFile($img, $x, $this->y($y) - $h, $w, $h);
      break;
    case IMAGETYPE_GIF:
    case IMAGETYPE_BMP:
      if ($debug_png) print '!!!bmp or gif!!!';
      $img = $this->_convert_gif_bmp_to_png($img, $type);
    case IMAGETYPE_PNG:
      if ($debug_png) print '!!!png!!!';
      $this->_pdf->addPngFromFile($img, $x, $this->y($y) - $h, $w, $h);
      break;
    default:
      if ($debug_png) print '!!!unknown!!!';
    }
  }
  function text($x, $y, $text, $font, $size, $color = array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0) {
    $pdf = $this->_pdf;
    $pdf->setColor($color);
    $font .= ".afm";
    $pdf->selectFont($font);
    $pdf->addText($x, $this->y($y) - $pdf->getFontHeight($size), $size, $text, $angle, $word_space, $char_space);
  }
  function javascript($code) {
    $this->_pdf->addJavascript($code);
  }
  function add_named_dest($anchorname) {
    $this->_pdf->addDestination($anchorname, "Fit");
  }
  function add_link($url, $x, $y, $width, $height) {
    $y = $this->y($y) - $height;
    if ( strpos($url, '#') === 0 ) {
      $name = substr($url,1);
      if ( $name ) {
        $this->_pdf->addInternalLink($name, $x, $y, $x + $width, $y + $height);
      }
    }
    else {
      $this->_pdf->addLink(rawurldecode($url), $x, $y, $x + $width, $y + $height);
    }
  }
  function get_text_width($text, $font, $size, $word_spacing = 0, $char_spacing = 0) {
    $this->_pdf->selectFont($font);
    $unicode = $this->_dompdf->get_option("enable_unicode");
    if (!$unicode) {
      $text = mb_convert_encoding($text, 'Windows-1252', 'UTF-8');
    }
    return $this->_pdf->getTextWidth($size, $text, $word_spacing, $char_spacing);
  }
  function register_string_subset($font, $string) {
    $this->_pdf->registerText($font, $string);
  }
  function get_font_height($font, $size) {
    $this->_pdf->selectFont($font);
    $ratio = $this->_dompdf->get_option("font_height_ratio");
    return $this->_pdf->getFontHeight($size) * $ratio;
  }
  function get_font_baseline($font, $size) {
    $ratio = $this->_dompdf->get_option("font_height_ratio");
    return $this->get_font_height($font, $size) / $ratio;
  }
  function page_text($x, $y, $text, $font, $size, $color = array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0) {
    $_t = "text";
    $this->_page_text[] = compact("_t", "x", "y", "text", "font", "size", "color", "word_space", "char_space", "angle");
  }
  function page_script($code, $type = "text/php") {
    $_t = "script";
    $this->_page_text[] = compact("_t", "code", "type");
  }
  function new_page() {
    $this->_page_number++;
    $this->_page_count++;
    $ret = $this->_pdf->newPage();
    $this->_pages[] = $ret;
    return $ret;
  }
  protected function _add_page_text() {
    if ( !count($this->_page_text) ) {
      return;
    }
    $page_number = 1;
    $eval = null;
    foreach ($this->_pages as $pid) {
      $this->reopen_object($pid);
      foreach ($this->_page_text as $pt) {
        extract($pt);
        switch ($_t) {
          case "text":
            $text = str_replace(array("{PAGE_NUM}","{PAGE_COUNT}"),
                                array($page_number, $this->_page_count), $text);
            $this->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            break;
          case "script":
            if ( !$eval ) {
              $eval = new PHP_Evaluator($this);
            }
            $eval->evaluate($code, array('PAGE_NUM' => $page_number, 'PAGE_COUNT' => $this->_page_count));
            break;
        }
      }
      $this->close_object();
      $page_number++;
    }
  }
  function stream($filename, $options = null) {
    $this->_add_page_text();
    $options["Content-Disposition"] = $filename;
    $this->_pdf->stream($options);
  }
  function output($options = null) {
    $this->_add_page_text();
    $debug = isset($options["compress"]) && $options["compress"] != 1;
    return $this->_pdf->output($debug);
  }
  function get_messages() {
    return $this->_pdf->messages;
  }
}
