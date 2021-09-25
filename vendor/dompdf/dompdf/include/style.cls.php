<?php
class Style {
  const CSS_IDENTIFIER = "-?[_a-zA-Z]+[_a-zA-Z0-9-]*";
  const CSS_INTEGER    = "-?\d+";
  static $default_font_size = 12;
  static $default_line_height = 1.2;
  static $font_size_keywords = array(
    "xx-small" => 0.6,   
    "x-small"  => 0.75,  
    "small"    => 0.889, 
    "medium"   => 1,     
    "large"    => 1.2,   
    "x-large"  => 1.5,   
    "xx-large" => 2.0,   
  );
  static $INLINE_TYPES = array("inline");
  static $BLOCK_TYPES = array("block", "inline-block", "table-cell", "list-item");
  static $POSITIONNED_TYPES = array("relative", "absolute", "fixed");
  static $TABLE_TYPES = array("table", "inline-table");
  static $BORDER_STYLES = array("none", "hidden", "dotted", "dashed", "solid",
                                "double", "groove", "ridge", "inset", "outset");
  static protected $_defaults = null;
  static protected $_inherited = null;
  static protected $_methods_cache = array();
  protected $_stylesheet; 
  protected $_props;
  protected $_important_props;
  protected $_prop_cache;
  protected $_parent_font_size; 
  protected $_font_family;
  protected $_frame;
  protected $_origin = Stylesheet::ORIG_AUTHOR;
  private $__font_size_calculated; 
  private $_computed_border_radius = null;
  public $_has_border_radius = false;
  function __construct(Stylesheet $stylesheet, $origin = Stylesheet::ORIG_AUTHOR) {
    $this->_props = array();
    $this->_important_props = array();
    $this->_stylesheet = $stylesheet;
    $this->_origin = $origin;
    $this->_parent_font_size = null;
    $this->__font_size_calculated = false;
    if ( !isset(self::$_defaults) ) {
      $d =& self::$_defaults;
      $d["azimuth"] = "center";
      $d["background_attachment"] = "scroll";
      $d["background_color"] = "transparent";
      $d["background_image"] = "none";
      $d["background_image_resolution"] = "normal";
      $d["_dompdf_background_image_resolution"] = $d["background_image_resolution"];
      $d["background_position"] = "0% 0%";
      $d["background_repeat"] = "repeat";
      $d["background"] = "";
      $d["border_collapse"] = "separate";
      $d["border_color"] = "";
      $d["border_spacing"] = "0";
      $d["border_style"] = "";
      $d["border_top"] = "";
      $d["border_right"] = "";
      $d["border_bottom"] = "";
      $d["border_left"] = "";
      $d["border_top_color"] = "";
      $d["border_right_color"] = "";
      $d["border_bottom_color"] = "";
      $d["border_left_color"] = "";
      $d["border_top_style"] = "none";
      $d["border_right_style"] = "none";
      $d["border_bottom_style"] = "none";
      $d["border_left_style"] = "none";
      $d["border_top_width"] = "medium";
      $d["border_right_width"] = "medium";
      $d["border_bottom_width"] = "medium";
      $d["border_left_width"] = "medium";
      $d["border_width"] = "medium";
      $d["border_bottom_left_radius"] = "";
      $d["border_bottom_right_radius"] = "";
      $d["border_top_left_radius"] = "";
      $d["border_top_right_radius"] = "";
      $d["border_radius"] = "";
      $d["border"] = "";
      $d["bottom"] = "auto";
      $d["caption_side"] = "top";
      $d["clear"] = "none";
      $d["clip"] = "auto";
      $d["color"] = "#000000";
      $d["content"] = "normal";
      $d["counter_increment"] = "none";
      $d["counter_reset"] = "none";
      $d["cue_after"] = "none";
      $d["cue_before"] = "none";
      $d["cue"] = "";
      $d["cursor"] = "auto";
      $d["direction"] = "ltr";
      $d["display"] = "inline";
      $d["elevation"] = "level";
      $d["empty_cells"] = "show";
      $d["float"] = "none";
      $d["font_family"] = $stylesheet->get_dompdf()->get_option("default_font");
      $d["font_size"] = "medium";
      $d["font_style"] = "normal";
      $d["font_variant"] = "normal";
      $d["font_weight"] = "normal";
      $d["font"] = "";
      $d["height"] = "auto";
      $d["image_resolution"] = "normal";
      $d["_dompdf_image_resolution"] = $d["image_resolution"];
      $d["_dompdf_keep"] = "";
      $d["left"] = "auto";
      $d["letter_spacing"] = "normal";
      $d["line_height"] = "normal";
      $d["list_style_image"] = "none";
      $d["list_style_position"] = "outside";
      $d["list_style_type"] = "disc";
      $d["list_style"] = "";
      $d["margin_right"] = "0";
      $d["margin_left"] = "0";
      $d["margin_top"] = "0";
      $d["margin_bottom"] = "0";
      $d["margin"] = "";
      $d["max_height"] = "none";
      $d["max_width"] = "none";
      $d["min_height"] = "0";
      $d["min_width"] = "0";
      $d["opacity"] = "1.0"; 
      $d["orphans"] = "2";
      $d["outline_color"] = ""; 
      $d["outline_style"] = "none";
      $d["outline_width"] = "medium";
      $d["outline"] = "";
      $d["overflow"] = "visible";
      $d["padding_top"] = "0";
      $d["padding_right"] = "0";
      $d["padding_bottom"] = "0";
      $d["padding_left"] = "0";
      $d["padding"] = "";
      $d["page_break_after"] = "auto";
      $d["page_break_before"] = "auto";
      $d["page_break_inside"] = "auto";
      $d["pause_after"] = "0";
      $d["pause_before"] = "0";
      $d["pause"] = "";
      $d["pitch_range"] = "50";
      $d["pitch"] = "medium";
      $d["play_during"] = "auto";
      $d["position"] = "static";
      $d["quotes"] = "";
      $d["richness"] = "50";
      $d["right"] = "auto";
      $d["size"] = "auto"; 
      $d["speak_header"] = "once";
      $d["speak_numeral"] = "continuous";
      $d["speak_punctuation"] = "none";
      $d["speak"] = "normal";
      $d["speech_rate"] = "medium";
      $d["stress"] = "50";
      $d["table_layout"] = "auto";
      $d["text_align"] = "left";
      $d["text_decoration"] = "none";
      $d["text_indent"] = "0";
      $d["text_transform"] = "none";
      $d["top"] = "auto";
      $d["transform"] = "none"; 
      $d["transform_origin"] = "50% 50%"; 
      $d["_webkit_transform"] = $d["transform"]; 
      $d["_webkit_transform_origin"] = $d["transform_origin"]; 
      $d["unicode_bidi"] = "normal";
      $d["vertical_align"] = "baseline";
      $d["visibility"] = "visible";
      $d["voice_family"] = "";
      $d["volume"] = "medium";
      $d["white_space"] = "normal";
      $d["word_wrap"] = "normal";
      $d["widows"] = "2";
      $d["width"] = "auto";
      $d["word_spacing"] = "normal";
      $d["z_index"] = "auto";
      $d["src"] = "";
      $d["unicode_range"] = "";
      self::$_inherited = array(
        "azimuth",
        "background_image_resolution",
        "border_collapse",
        "border_spacing",
        "caption_side",
        "color",
        "cursor",
        "direction",
        "elevation",
        "empty_cells",
        "font_family",
        "font_size",
        "font_style",
        "font_variant",
        "font_weight",
        "font",
        "image_resolution",
        "letter_spacing",
        "line_height",
        "list_style_image",
        "list_style_position",
        "list_style_type",
        "list_style",
        "orphans",
        "page_break_inside",
        "pitch_range",
        "pitch",
        "quotes",
        "richness",
        "speak_header",
        "speak_numeral",
        "speak_punctuation",
        "speak",
        "speech_rate",
        "stress",
        "text_align",
        "text_indent",
        "text_transform",
        "visibility",
        "voice_family",
        "volume",
        "white_space",
        "word_wrap",
        "widows",
        "word_spacing",
      );
    }
  }
  function dispose() {
    clear_object($this);
  }
  function set_frame(Frame $frame) {
    $this->_frame = $frame;
  }
  function get_frame() {
    return $this->_frame;
  }
  function set_origin($origin) {
    $this->_origin = $origin;
  }
  function get_origin() {
    return $this->_origin;
  }
  function get_stylesheet() { return $this->_stylesheet; }
  function length_in_pt($length, $ref_size = null) {
    static $cache = array();
    if ( !is_array($length) ) {
      $length = array($length);
    }
    if ( !isset($ref_size) ) {
      $ref_size = self::$default_font_size;
    }
    $key = implode("@", $length)."/$ref_size";
    if ( isset($cache[$key]) ) {
      return $cache[$key];
    }
    $ret = 0;
    foreach ($length as $l) {
      if ( $l === "auto" ) {
        return "auto";
      }
      if ( $l === "none" ) {
        return "none";
      }
      if ( is_numeric($l) ) {
        $ret += $l;
        continue;
      }
      if ( $l === "normal" ) {
        $ret += $ref_size;
        continue;
      }
      if ( $l === "thin" ) {
        $ret += 0.5;
        continue;
      }
      if ( $l === "medium" ) {
        $ret += 1.5;
        continue;
      }
      if ( $l === "thick" ) {
        $ret += 2.5;
        continue;
      }
      if ( ($i = mb_strpos($l, "px"))  !== false ) {
        $dpi = $this->_stylesheet->get_dompdf()->get_option("dpi");
        $ret += ( mb_substr($l, 0, $i)  * 72 ) / $dpi;
        continue;
      }
      if ( ($i = mb_strpos($l, "pt"))  !== false ) {
        $ret += (float)mb_substr($l, 0, $i);
        continue;
      }
      if ( ($i = mb_strpos($l, "%"))  !== false ) {
        $ret += (float)mb_substr($l, 0, $i)/100 * $ref_size;
        continue;
      }
      if ( ($i = mb_strpos($l, "rem"))  !== false ) {
        $ret += (float)mb_substr($l, 0, $i) * $this->_stylesheet->get_dompdf()->get_tree()->get_root()->get_style()->font_size;
        continue;
      }
      if ( ($i = mb_strpos($l, "em"))  !== false ) {
        $ret += (float)mb_substr($l, 0, $i) * $this->__get("font_size");
        continue;
      }
      if ( ($i = mb_strpos($l, "cm")) !== false ) {
        $ret += mb_substr($l, 0, $i) * 72 / 2.54;
        continue;
      }
      if ( ($i = mb_strpos($l, "mm")) !== false ) {
        $ret += mb_substr($l, 0, $i) * 72 / 25.4;
        continue;
      }
      if ( ($i = mb_strpos($l, "ex"))  !== false ) {
        $ret += mb_substr($l, 0, $i) * $this->__get("font_size") / 2;
        continue;
      }
      if ( ($i = mb_strpos($l, "in")) !== false ) {
        $ret += (float)mb_substr($l, 0, $i) * 72;
        continue;
      }
      if ( ($i = mb_strpos($l, "pc")) !== false ) {
        $ret += (float)mb_substr($l, 0, $i) * 12;
        continue;
      }
      $ret += $ref_size;
    }
    return $cache[$key] = $ret;
  }
  function inherit(Style $parent) {
    $this->_parent_font_size = $parent->get_font_size();
    foreach (self::$_inherited as $prop) {
      if ( isset($parent->_props[$prop]) &&
           ( !isset($this->_props[$prop]) ||
             ( isset($parent->_important_props[$prop]) && !isset($this->_important_props[$prop]) )
           )
         ) {
        if ( isset($parent->_important_props[$prop]) ) {
          $this->_important_props[$prop] = true;
        }
        $this->_prop_cache[$prop] = null;
        $this->_props[$prop] = $parent->_props[$prop];
      }
    }
    foreach ($this->_props as $prop => $value) {
      if ( $value === "inherit" ) {
        if ( isset($parent->_important_props[$prop]) ) {
          $this->_important_props[$prop] = true;
        }
        $this->__set($prop, $parent->__get($prop));
      }
    }
    return $this;
  }
  function merge(Style $style) {
    foreach($style->_props as $prop => $val ) {
      if (isset($style->_important_props[$prop])) {
        $this->_important_props[$prop] = true;
        $this->_prop_cache[$prop] = null;
        $this->_props[$prop] = $val;
      }
      else if ( !isset($this->_important_props[$prop]) ) {
        $this->_prop_cache[$prop] = null;
        $this->_props[$prop] = $val;
      }
    }
    if ( isset($style->_props["font_size"]) ) {
      $this->__font_size_calculated = false;
    }
  }
  function munge_color($color) {
    return CSS_Color::parse($color);
  }
  function important_set($prop) {
    $prop = str_replace("-", "_", $prop);
    $this->_important_props[$prop] = true;
  }
  function important_get($prop) {
    return isset($this->_important_props[$prop]);
  }
  function __set($prop, $val) {
    $prop = str_replace("-", "_", $prop);
    $this->_prop_cache[$prop] = null;
    if ( !isset(self::$_defaults[$prop]) ) {
      global $_dompdf_warnings;
      $_dompdf_warnings[] = "'$prop' is not a valid CSS2 property.";
      return;
    }
    if ( $prop !== "content" && is_string($val) && strlen($val) > 5 && mb_strpos($val, "url") === false ) {
      $val = mb_strtolower(trim(str_replace(array("\n", "\t"), array(" "), $val)));
      $val = preg_replace("/([0-9]+) (pt|px|pc|em|ex|in|cm|mm|%)/S", "\\1\\2", $val);
    }
    $method = "set_$prop";
    if ( !isset(self::$_methods_cache[$method]) ) {
      self::$_methods_cache[$method] = method_exists($this, $method);
    }
    if ( self::$_methods_cache[$method] ) {
      $this->$method($val);
    }
    else {
      $this->_props[$prop] = $val;
    }
  }
  function __get($prop) {
    if ( !isset(self::$_defaults[$prop]) ) {
      throw new DOMPDF_Exception("'$prop' is not a valid CSS2 property.");
    }
    if ( isset($this->_prop_cache[$prop]) && $this->_prop_cache[$prop] != null ) {
      return $this->_prop_cache[$prop];
    }
    $method = "get_$prop";
    if ( !isset($this->_props[$prop]) ) {
      $this->_props[$prop] = self::$_defaults[$prop];
    }
    if ( !isset(self::$_methods_cache[$method]) ) {
      self::$_methods_cache[$method] = method_exists($this, $method);
    }
    if ( self::$_methods_cache[$method] ) {
      return $this->_prop_cache[$prop] = $this->$method();
    }
    return $this->_prop_cache[$prop] = $this->_props[$prop];
  }
  function get_font_family_raw(){
    return trim($this->_props["font_family"], " \t\n\r\x0B\"'");
  }
  function get_font_family() {
    if ( isset($this->_font_family) ) {
      return $this->_font_family;
    }
    $DEBUGCSS=DEBUGCSS; 
    $weight = $this->__get("font_weight");
    if ( is_numeric($weight) ) {
      if ( $weight < 600 ) {
        $weight = "normal";
      }
      else {
        $weight = "bold";
      }
    }
    else if ( $weight === "bold" || $weight === "bolder" ) {
      $weight = "bold";
    }
    else {
      $weight = "normal";
    }
    $font_style = $this->__get("font_style");
    if ( $weight === "bold" && ($font_style === "italic" || $font_style === "oblique") ) {
      $subtype = "bold_italic";
    }
    else if ( $weight === "bold" && $font_style !== "italic" && $font_style !== "oblique" ) {
      $subtype = "bold";
    }
    else if ( $weight !== "bold" && ($font_style === "italic" || $font_style === "oblique") ) {
      $subtype = "italic";
    }
    else {
      $subtype = "normal";
    }
    if ( $DEBUGCSS ) {
      print "<pre>[get_font_family:";
      print '('.$this->_props["font_family"].'.'.$font_style.'.'.$this->__get("font_weight").'.'.$weight.'.'.$subtype.')';
    }
    $families = preg_split("/\s*,\s*/", $this->_props["font_family"]);
    $font = null;
    foreach($families as $family) {
      $family = trim($family, " \t\n\r\x0B\"'");
      if ( $DEBUGCSS ) {
        print '('.$family.')';
      }
      $font = Font_Metrics::get_font($family, $subtype);
      if ( $font ) {
        if ($DEBUGCSS) print '('.$font.")get_font_family]\n</pre>";
        return $this->_font_family = $font;
      }
    }
    $family = null;
    if ( $DEBUGCSS ) {
      print '(default)';
    }
    $font = Font_Metrics::get_font($family, $subtype);
    if ( $font ) {
      if ( $DEBUGCSS ) print '('.$font.")get_font_family]\n</pre>";
      return$this->_font_family = $font;
    }
    throw new DOMPDF_Exception("Unable to find a suitable font replacement for: '" . $this->_props["font_family"] ."'");
  }
  function get_font_size() {
    if ( $this->__font_size_calculated ) {
      return $this->_props["font_size"];
    }
    if ( !isset($this->_props["font_size"]) ) {
      $fs = self::$_defaults["font_size"];
    }
    else {
      $fs = $this->_props["font_size"];
    }
    if ( !isset($this->_parent_font_size) ) {
      $this->_parent_font_size = self::$default_font_size;
    }
    switch ((string)$fs) {
      case "xx-small":
      case "x-small":
      case "small":
      case "medium":
      case "large":
      case "x-large":
      case "xx-large":
        $fs = self::$default_font_size * self::$font_size_keywords[$fs];
        break;
      case "smaller":
        $fs = 8/9 * $this->_parent_font_size;
        break;
      case "larger":
        $fs = 6/5 * $this->_parent_font_size;
        break;
      default:
        break;
    }
    if ( ($i = mb_strpos($fs, "em")) !== false ) {
      $fs = mb_substr($fs, 0, $i) * $this->_parent_font_size;
    }
    else if ( ($i = mb_strpos($fs, "ex")) !== false ) {
      $fs = mb_substr($fs, 0, $i) * $this->_parent_font_size;
    }
    else {
      $fs = $this->length_in_pt($fs);
    }
    $this->_prop_cache["font_size"] = null;
    $this->_props["font_size"] = $fs;
    $this->__font_size_calculated = true;
    return $this->_props["font_size"];
  }
  function get_word_spacing() {
    if ( $this->_props["word_spacing"] === "normal" ) {
      return 0;
    }
    return $this->_props["word_spacing"];
  }
  function get_letter_spacing() {
    if ( $this->_props["letter_spacing"] === "normal" ) {
      return 0;
    }
    return $this->_props["letter_spacing"];
  }
  function get_line_height() {
    $line_height = $this->_props["line_height"];
    if ( $line_height === "normal" ) {
      return self::$default_line_height * $this->get_font_size();
    }
    if ( is_numeric($line_height) ) {
      return $this->length_in_pt( $line_height . "em", $this->get_font_size());
    }
    return $this->length_in_pt( $line_height, $this->_parent_font_size );
  }
  function get_color() {
    return $this->munge_color( $this->_props["color"] );
  }
  function get_background_color() {
    return $this->munge_color( $this->_props["background_color"] );
  }
  function get_background_position() {
    $tmp = explode(" ", $this->_props["background_position"]);
    switch ($tmp[0]) {
      case "left":
        $x = "0%";
        break;
      case "right":
        $x = "100%";
        break;
      case "top":
        $y = "0%";
        break;
      case "bottom":
        $y = "100%";
        break;
      case "center":
        $x = "50%";
        $y = "50%";
        break;
      default:
        $x = $tmp[0];
        break;
    }
    if ( isset($tmp[1]) ) {
      switch ($tmp[1]) {
        case "left":
          $x = "0%";
          break;
        case "right":
          $x = "100%";
          break;
        case "top":
          $y = "0%";
          break;
        case "bottom":
          $y = "100%";
          break;
        case "center":
          if ( $tmp[0] === "left" || $tmp[0] === "right" || $tmp[0] === "center" ) {
            $y = "50%";
          }
          else {
            $x = "50%";
          }
          break;
        default:
          $y = $tmp[1];
          break;
      }
    }
    else {
      $y = "50%";
    }
    if ( !isset($x) ) {
      $x = "0%";
    }
    if ( !isset($y) ) {
      $y = "0%";
    }
    return array(
      0 => $x, "x" => $x,
      1 => $y, "y" => $y,
    );
  }
  function get_background_attachment() {
    return $this->_props["background_attachment"];
  }
  function get_background_repeat() {
    return $this->_props["background_repeat"];
  }
  function get_background() {
    return $this->_props["background"];
  }
  function get_border_top_color() {
    if ( $this->_props["border_top_color"] === "" ) {
      $this->_prop_cache["border_top_color"] = null;
      $this->_props["border_top_color"] = $this->__get("color");
    }
    return $this->munge_color($this->_props["border_top_color"]);
  }
  function get_border_right_color() {
    if ( $this->_props["border_right_color"] === "" ) {
      $this->_prop_cache["border_right_color"] = null;
      $this->_props["border_right_color"] = $this->__get("color");
    }
    return $this->munge_color($this->_props["border_right_color"]);
  }
  function get_border_bottom_color() {
    if ( $this->_props["border_bottom_color"] === "" ) {
      $this->_prop_cache["border_bottom_color"] = null;
      $this->_props["border_bottom_color"] = $this->__get("color");
    }
    return $this->munge_color($this->_props["border_bottom_color"]);
  }
  function get_border_left_color() {
    if ( $this->_props["border_left_color"] === "" ) {
      $this->_prop_cache["border_left_color"] = null;
      $this->_props["border_left_color"] = $this->__get("color");
    }
    return $this->munge_color($this->_props["border_left_color"]);
  }
  function get_border_top_width() {
    $style = $this->__get("border_top_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_top_width"]) : 0;
  }
  function get_border_right_width() {
    $style = $this->__get("border_right_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_right_width"]) : 0;
  }
  function get_border_bottom_width() {
    $style = $this->__get("border_bottom_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_bottom_width"]) : 0;
  }
  function get_border_left_width() {
    $style = $this->__get("border_left_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_left_width"]) : 0;
  }
  function get_border_properties() {
    return array(
      "top" => array(
        "width" => $this->__get("border_top_width"),
        "style" => $this->__get("border_top_style"),
        "color" => $this->__get("border_top_color"),
      ),
      "bottom" => array(
        "width" => $this->__get("border_bottom_width"),
        "style" => $this->__get("border_bottom_style"),
        "color" => $this->__get("border_bottom_color"),
      ),
      "right" => array(
        "width" => $this->__get("border_right_width"),
        "style" => $this->__get("border_right_style"),
        "color" => $this->__get("border_right_color"),
      ),
      "left" => array(
        "width" => $this->__get("border_left_width"),
        "style" => $this->__get("border_left_style"),
        "color" => $this->__get("border_left_color"),
      ),
    );
  }
  protected function _get_border($side) {
    $color = $this->__get("border_" . $side . "_color");
    return $this->__get("border_" . $side . "_width") . " " .
      $this->__get("border_" . $side . "_style") . " " . $color["hex"];
  }
  function get_border_top() {
    return $this->_get_border("top");
  }
  function get_border_right() {
    return $this->_get_border("right");
  }
  function get_border_bottom() {
    return $this->_get_border("bottom");
  }
  function get_border_left() {
    return $this->_get_border("left");
  }
  function get_computed_border_radius($w, $h) {
    if ( !empty($this->_computed_border_radius) ) {
      return $this->_computed_border_radius;
    }
    $rTL = $this->__get("border_top_left_radius");
    $rTR = $this->__get("border_top_right_radius");
    $rBL = $this->__get("border_bottom_left_radius");
    $rBR = $this->__get("border_bottom_right_radius");
    if ( $rTL + $rTR + $rBL + $rBR == 0 ) {
      return $this->_computed_border_radius = array(
        0, 0, 0, 0,
        "top-left"     => 0, 
        "top-right"    => 0, 
        "bottom-right" => 0, 
        "bottom-left"  => 0, 
      );
    }
    $t = $this->__get("border_top_width");
    $r = $this->__get("border_right_width");
    $b = $this->__get("border_bottom_width");
    $l = $this->__get("border_left_width");
    $rTL = min($rTL, $h - $rBL - $t/2 - $b/2, $w - $rTR - $l/2 - $r/2);
    $rTR = min($rTR, $h - $rBR - $t/2 - $b/2, $w - $rTL - $l/2 - $r/2);
    $rBL = min($rBL, $h - $rTL - $t/2 - $b/2, $w - $rBR - $l/2 - $r/2);
    $rBR = min($rBR, $h - $rTR - $t/2 - $b/2, $w - $rBL - $l/2 - $r/2);
    return $this->_computed_border_radius = array(
      $rTL, $rTR, $rBR, $rBL,
      "top-left"     => $rTL, 
      "top-right"    => $rTR, 
      "bottom-right" => $rBR, 
      "bottom-left"  => $rBL, 
    );
  }
  function get_outline_color() {
    if ( $this->_props["outline_color"] === "" ) {
      $this->_prop_cache["outline_color"] = null;
      $this->_props["outline_color"] = $this->__get("color");
    }
    return $this->munge_color($this->_props["outline_color"]);
  }
  function get_outline_width() {
    $style = $this->__get("outline_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["outline_width"]) : 0;
  }
  function get_outline() {
    $color = $this->__get("outline_color");
    return 
      $this->__get("outline_width") . " " . 
      $this->__get("outline_style") . " " . 
      $color["hex"]; 
  }
  function get_border_spacing() {
    $arr = explode(" ", $this->_props["border_spacing"]);
    if ( count($arr) == 1 ) {
      $arr[1] = $arr[0];
    }
    return $arr;
  }
  protected function _set_style_side_type($style, $side, $type, $val, $important) {
    $prop = $style.'_'.$side.$type;
    if ( !isset($this->_important_props[$prop]) || $important) {
      $this->_prop_cache[$prop] = null;
      if ( $important ) {
        $this->_important_props[$prop] = true;
      }
      $this->_props[$prop] = $val;
    }
  }
  protected function _set_style_sides_type($style,$top,$right,$bottom,$left,$type,$important) {
    $this->_set_style_side_type($style,'top',$type,$top,$important);
    $this->_set_style_side_type($style,'right',$type,$right,$important);
    $this->_set_style_side_type($style,'bottom',$type,$bottom,$important);
    $this->_set_style_side_type($style,'left',$type,$left,$important);
  }
  protected function _set_style_type($style,$type,$val,$important) {
    $val = preg_replace("/\s*\,\s*/", ",", $val); 
    $arr = explode(" ", $val);
    switch (count($arr)) {
      case 1: $this->_set_style_sides_type($style,$arr[0],$arr[0],$arr[0],$arr[0],$type,$important); break;
      case 2: $this->_set_style_sides_type($style,$arr[0],$arr[1],$arr[0],$arr[1],$type,$important); break;
      case 3: $this->_set_style_sides_type($style,$arr[0],$arr[1],$arr[2],$arr[1],$type,$important); break;
      case 4: $this->_set_style_sides_type($style,$arr[0],$arr[1],$arr[2],$arr[3],$type,$important); break;
    }
    $this->_prop_cache[$style.$type] = null;
    $this->_props[$style.$type] = $val;
  }
  protected function _set_style_type_important($style,$type,$val) {
    $this->_set_style_type($style,$type,$val,isset($this->_important_props[$style.$type]));
  }
  protected function _set_style_side_width_important($style,$side,$val) {
    $this->_prop_cache[$style.'_'.$side] = null;
    $this->_props[$style.'_'.$side] = str_replace("none", "0px", $val);
  }
  protected function _set_style($style,$val,$important) {
    if ( !isset($this->_important_props[$style]) || $important) {
      if ( $important ) {
        $this->_important_props[$style] = true;
      }
      $this->_prop_cache[$style] = null;
      $this->_props[$style] = $val;
    }
  }
  protected function _image($val) {
    $DEBUGCSS=DEBUGCSS;
    $parsed_url = "none";
    if ( mb_strpos($val, "url") === false ) {
      $path = "none"; 
    }
    else {
      $val = preg_replace("/url\(['\"]?([^'\")]+)['\"]?\)/","\\1", trim($val));
      $parsed_url = explode_url($val);
      if ( $parsed_url["protocol"] == "" && $this->_stylesheet->get_protocol() == "" ) {
        if ($parsed_url["path"][0] === '/' || $parsed_url["path"][0] === '\\' ) {
          $path = $_SERVER["DOCUMENT_ROOT"].'/';
        }
        else {
          $path = $this->_stylesheet->get_base_path();
        }
        $path .= $parsed_url["path"] . $parsed_url["file"];
        $path = realpath($path);
        if ( !$path ) {
          $path = 'none';
        }
      }
      else {
        $path = build_url($this->_stylesheet->get_protocol(),
                          $this->_stylesheet->get_host(),
                          $this->_stylesheet->get_base_path(),
                          $val);
      }
    }
    if ($DEBUGCSS) {
      print "<pre>[_image\n";
      print_r($parsed_url);
      print $this->_stylesheet->get_protocol()."\n".$this->_stylesheet->get_base_path()."\n".$path."\n";
      print "_image]</pre>";;
    }
    return $path;
  }
  function set_color($color) {
    $col = $this->munge_color($color);
    if ( is_null($col) || !isset($col["hex"]) ) {
      $color = "inherit";
    }
    else {
      $color = $col["hex"];
    }
    $this->_prop_cache["color"] = null;
    $this->_props["color"] = $color;
  }
  function set_background_color($color) {
    $col = $this->munge_color($color);
    if ( is_null($col) ) {
      return;
    }
    $this->_prop_cache["background_color"] = null;
    $this->_props["background_color"] = is_array($col) ? $col["hex"] : $col;
  }
  function set_background_image($val) {
    $this->_prop_cache["background_image"] = null;
    $this->_props["background_image"] = $this->_image($val);
  }
  function set_background_repeat($val) {
    if ( is_null($val) ) {
      $val = self::$_defaults["background_repeat"];
    }
    $this->_prop_cache["background_repeat"] = null;
    $this->_props["background_repeat"] = $val;
  }
  function set_background_attachment($val) {
    if ( is_null($val) ) {
      $val = self::$_defaults["background_attachment"];
    }
    $this->_prop_cache["background_attachment"] = null;
    $this->_props["background_attachment"] = $val;
  }
  function set_background_position($val) {
    if ( is_null($val) ) {
      $val = self::$_defaults["background_position"];
    }
    $this->_prop_cache["background_position"] = null;
    $this->_props["background_position"] = $val;
  }
  function set_background($val) {
    $val = trim($val);
    $important = isset($this->_important_props["background"]);
    if ( $val === "none" ) {
      $this->_set_style("background_image", "none", $important);
      $this->_set_style("background_color", "transparent", $important);
    }
    else {
      $pos = array();
      $tmp = preg_replace("/\s*\,\s*/", ",", $val); 
      $tmp = preg_split("/\s+/", $tmp);
      foreach($tmp as $attr) {
        if ( mb_substr($attr, 0, 3) === "url" || $attr === "none" ) {
          $this->_set_style("background_image", $this->_image($attr), $important);
        } 
        elseif ( $attr === "fixed" || $attr === "scroll" ) {
          $this->_set_style("background_attachment", $attr, $important);
        } 
        elseif ( $attr === "repeat" || $attr === "repeat-x" || $attr === "repeat-y" || $attr === "no-repeat" ) {
          $this->_set_style("background_repeat", $attr, $important);
        }
        elseif ( ($col = $this->munge_color($attr)) != null ) {
          $this->_set_style("background_color", is_array($col) ? $col["hex"] : $col, $important);
        } 
        else {
          $pos[] = $attr;
        }
      }
      if (count($pos)) {
        $this->_set_style("background_position", implode(" ", $pos), $important);
      }
    }
    $this->_prop_cache["background"] = null;
    $this->_props["background"] = $val;
  }
  function set_font_size($size) {
    $this->__font_size_calculated = false;
    $this->_prop_cache["font_size"] = null;
    $this->_props["font_size"] = $size;
  }
  function set_font($val) {
    $this->__font_size_calculated = false;
    $this->_prop_cache["font"] = null;
    $this->_props["font"] = $val;
    $important = isset($this->_important_props["font"]);
    if ( preg_match("/^(italic|oblique|normal)\s*(.*)$/i",$val,$match) ) {
      $this->_set_style("font_style", $match[1], $important);
      $val = $match[2];
    }
    else {
      $this->_set_style("font_style", self::$_defaults["font_style"], $important);
    }
    if ( preg_match("/^(small-caps|normal)\s*(.*)$/i",$val,$match) ) {
      $this->_set_style("font_variant", $match[1], $important);
      $val = $match[2];
    }
    else {
      $this->_set_style("font_variant", self::$_defaults["font_variant"], $important);
    }
    if ( preg_match("/^(bold|bolder|lighter|100|200|300|400|500|600|700|800|900|normal)\s*(.*)$/i", $val, $match) &&
         !preg_match("/^(?:pt|px|pc|em|ex|in|cm|mm|%)/",$match[2])
       ) {
      $this->_set_style("font_weight", $match[1], $important);
      $val = $match[2];
    }
    else {
      $this->_set_style("font_weight", self::$_defaults["font_weight"], $important);
    }
    if ( preg_match("/^(xx-small|x-small|small|medium|large|x-large|xx-large|smaller|larger|\d+\s*(?:pt|px|pc|em|ex|in|cm|mm|%))\s*(.*)$/i",$val,$match) ) {
      $this->_set_style("font_size", $match[1], $important);
      $val = $match[2];
      if ( preg_match("/^\/\s*(\d+\s*(?:pt|px|pc|em|ex|in|cm|mm|%))\s*(.*)$/i", $val, $match ) ) {
        $this->_set_style("line_height", $match[1], $important);
        $val = $match[2];
      }
      else {
        $this->_set_style("line_height", self::$_defaults["line_height"], $important);
      }
    }
    else {
      $this->_set_style("font_size", self::$_defaults["font_size"], $important);
      $this->_set_style("line_height", self::$_defaults["line_height"], $important);
    }
    if( strlen($val) != 0 ) {
      $this->_set_style("font_family", $val, $important);
    }
    else {
      $this->_set_style("font_family", self::$_defaults["font_family"], $important);
    }
  }
  function set_page_break_before($break) {
    if ( $break === "left" || $break === "right" ) {
      $break = "always";
    }
    $this->_prop_cache["page_break_before"] = null;
    $this->_props["page_break_before"] = $break;
  }
  function set_page_break_after($break) {
    if ( $break === "left" || $break === "right" ) {
      $break = "always";
    }
    $this->_prop_cache["page_break_after"] = null;
    $this->_props["page_break_after"] = $break;
  }
  function set_margin_top($val) {
    $this->_set_style_side_width_important('margin','top',$val);
  }
  function set_margin_right($val) {
    $this->_set_style_side_width_important('margin','right',$val);
  }
  function set_margin_bottom($val) {
    $this->_set_style_side_width_important('margin','bottom',$val);
  }
  function set_margin_left($val) {
    $this->_set_style_side_width_important('margin','left',$val);
  }
  function set_margin($val) {
    $val = str_replace("none", "0px", $val);
    $this->_set_style_type_important('margin','',$val);
  }
  function set_padding_top($val) {
    $this->_set_style_side_width_important('padding','top',$val);
  }
  function set_padding_right($val) {
    $this->_set_style_side_width_important('padding','right',$val);
  }
  function set_padding_bottom($val) {
    $this->_set_style_side_width_important('padding','bottom',$val);
  }
  function set_padding_left($val) {
    $this->_set_style_side_width_important('padding','left',$val);
  }
  function set_padding($val) {
    $val = str_replace("none", "0px", $val);
    $this->_set_style_type_important('padding','',$val);
  }
  protected function _set_border($side, $border_spec, $important) {
    $border_spec = preg_replace("/\s*\,\s*/", ",", $border_spec);
    $arr = explode(" ", $border_spec);
    $this->_set_style_side_type('border',$side,'_style',self::$_defaults['border_'.$side.'_style'],$important);
    $this->_set_style_side_type('border',$side,'_width',self::$_defaults['border_'.$side.'_width'],$important);
    $this->_set_style_side_type('border',$side,'_color',self::$_defaults['border_'.$side.'_color'],$important);
    foreach ($arr as $value) {
      $value = trim($value);
      if ( in_array($value, self::$BORDER_STYLES) ) {
        $this->_set_style_side_type('border',$side,'_style',$value,$important);
      }
      else if ( preg_match("/[.0-9]+(?:px|pt|pc|em|ex|%|in|mm|cm)|(?:thin|medium|thick)/", $value ) ) {
        $this->_set_style_side_type('border',$side,'_width',$value,$important);
      }
      else {
        $this->_set_style_side_type('border',$side,'_color',$value,$important);
      }
    }
    $this->_prop_cache['border_'.$side] = null;
    $this->_props['border_'.$side] = $border_spec;
  }
  function set_border_top($val) {
    $this->_set_border("top", $val, isset($this->_important_props['border_top'])); 
  }
  function set_border_right($val) {
    $this->_set_border("right", $val, isset($this->_important_props['border_right']));
  }
  function set_border_bottom($val) {
    $this->_set_border("bottom", $val, isset($this->_important_props['border_bottom']));
  }
  function set_border_left($val) {
    $this->_set_border("left", $val, isset($this->_important_props['border_left']));
  }
  function set_border($val) {
    $important = isset($this->_important_props["border"]);
    $this->_set_border("top", $val, $important);
    $this->_set_border("right", $val, $important);
    $this->_set_border("bottom", $val, $important);
    $this->_set_border("left", $val, $important);
    $this->_prop_cache["border"] = null;
    $this->_props["border"] = $val;
  }
  function set_border_width($val) {
    $this->_set_style_type_important('border','_width',$val);
  }
  function set_border_color($val) {
    $this->_set_style_type_important('border','_color',$val);
  }
  function set_border_style($val) {
    $this->_set_style_type_important('border','_style',$val);
  }
  function set_border_top_left_radius($val) {
    $this->_set_border_radius_corner($val, "top_left");
  }
  function set_border_top_right_radius($val) {
    $this->_set_border_radius_corner($val, "top_right");
  }
  function set_border_bottom_left_radius($val) {
    $this->_set_border_radius_corner($val, "bottom_left");
  }
  function set_border_bottom_right_radius($val) {
    $this->_set_border_radius_corner($val, "bottom_right");
  }
  function set_border_radius($val) {
    $val = preg_replace("/\s*\,\s*/", ",", $val); 
    $arr = explode(" ", $val);
    switch (count($arr)) {
      case 1: $this->_set_border_radii($arr[0],$arr[0],$arr[0],$arr[0]); break;
      case 2: $this->_set_border_radii($arr[0],$arr[1],$arr[0],$arr[1]); break;
      case 3: $this->_set_border_radii($arr[0],$arr[1],$arr[2],$arr[1]); break;
      case 4: $this->_set_border_radii($arr[0],$arr[1],$arr[2],$arr[3]); break;
    }
  }
  protected function _set_border_radii($val1, $val2, $val3, $val4) {
    $this->_set_border_radius_corner($val1, "top_left");
    $this->_set_border_radius_corner($val2, "top_right");
    $this->_set_border_radius_corner($val3, "bottom_right");
    $this->_set_border_radius_corner($val4, "bottom_left");
  }
  protected function _set_border_radius_corner($val, $corner) {
    $this->_has_border_radius = true;
    $this->_prop_cache["border_" . $corner . "_radius"] = null;
    $this->_props["border_" . $corner . "_radius"] = $this->length_in_pt($val);
  }
  function set_outline($val) {
    $important = isset($this->_important_props["outline"]);
    $props = array(
      "outline_style", 
      "outline_width", 
      "outline_color",
    );
    foreach($props as $prop) {
      $_val = self::$_defaults[$prop];
      if ( !isset($this->_important_props[$prop]) || $important) {
        $this->_prop_cache[$prop] = null;
        if ( $important ) {
          $this->_important_props[$prop] = true;
        }
        $this->_props[$prop] = $_val;
      }
    }
    $val = preg_replace("/\s*\,\s*/", ",", $val); 
    $arr = explode(" ", $val);
    foreach ($arr as $value) {
      $value = trim($value);
      if ( in_array($value, self::$BORDER_STYLES) ) {
        $this->set_outline_style($value);
      }
      else if ( preg_match("/[.0-9]+(?:px|pt|pc|em|ex|%|in|mm|cm)|(?:thin|medium|thick)/", $value ) ) {
        $this->set_outline_width($value);
      }
      else {
        $this->set_outline_color($value);
      }
    }
    $this->_prop_cache["outline"] = null;
    $this->_props["outline"] = $val;
  }
  function set_outline_width($val) {
    $this->_set_style_type_important('outline','_width',$val);
  }
  function set_outline_color($val) {
    $this->_set_style_type_important('outline','_color',$val);
  }
  function set_outline_style($val) {
    $this->_set_style_type_important('outline','_style',$val);
  }
  function set_border_spacing($val) {
    $arr = explode(" ", $val);
    if ( count($arr) == 1 ) {
      $arr[1] = $arr[0];
    }
    $this->_prop_cache["border_spacing"] = null;
    $this->_props["border_spacing"] = "$arr[0] $arr[1]";
  }
  function set_list_style_image($val) {
    $this->_prop_cache["list_style_image"] = null;
    $this->_props["list_style_image"] = $this->_image($val);
  }
  function set_list_style($val) {
    $important = isset($this->_important_props["list_style"]);
    $arr = explode(" ", str_replace(",", " ", $val));
    static $types = array(
      "disc", "circle", "square", 
      "decimal-leading-zero", "decimal", "1",
      "lower-roman", "upper-roman", "a", "A",
      "lower-greek", 
      "lower-latin", "upper-latin", 
      "lower-alpha", "upper-alpha", 
      "armenian", "georgian", "hebrew",
      "cjk-ideographic", "hiragana", "katakana",
      "hiragana-iroha", "katakana-iroha", "none"
    );
    static $positions = array("inside", "outside");
    foreach ($arr as $value) {
      if ( $value === "none" ) {
         $this->_set_style("list_style_type", $value, $important);
         $this->_set_style("list_style_image", $value, $important);
        continue;
      }
      if ( mb_substr($value, 0, 3) === "url" ) {
        $this->_set_style("list_style_image", $this->_image($value), $important);
        continue;
      }
      if ( in_array($value, $types) ) {
        $this->_set_style("list_style_type", $value, $important);
      }
      else if ( in_array($value, $positions) ) {
        $this->_set_style("list_style_position", $value, $important);
      }
    }
    $this->_prop_cache["list_style"] = null;
    $this->_props["list_style"] = $val;
  }
  function set_size($val) {
    $length_re = "/(\d+\s*(?:pt|px|pc|em|ex|in|cm|mm|%))/";
    $val = mb_strtolower($val);
    if ( $val === "auto" ) {
      return;
    }
    $parts = preg_split("/\s+/", $val);
    $computed = array();
    if ( preg_match($length_re, $parts[0]) ) {
      $computed[] = $this->length_in_pt($parts[0]);
      if ( isset($parts[1]) && preg_match($length_re, $parts[1]) ) {
        $computed[] = $this->length_in_pt($parts[1]);
      }
      else {
        $computed[] = $computed[0];
      }
    }
    elseif ( isset(CPDF_Adapter::$PAPER_SIZES[$parts[0]]) ) {
      $computed = array_slice(CPDF_Adapter::$PAPER_SIZES[$parts[0]], 2, 2);
      if ( isset($parts[1]) && $parts[1] === "landscape" ) {
        $computed = array_reverse($computed);
      }
    }
    else {
      return;
    }
    $this->_props["size"] = $computed;
  }
  function set_transform($val) {
    $number   = "\s*([^,\s]+)\s*";
    $tr_value = "\s*([^,\s]+)\s*";
    $angle    = "\s*([^,\s]+(?:deg|rad)?)\s*";
    if ( !preg_match_all("/[a-z]+\([^\)]+\)/i", $val, $parts, PREG_SET_ORDER) ) {
      return;
    }
    $functions = array(
      "translate"  => "\($tr_value(?:,$tr_value)?\)",
      "translateX" => "\($tr_value\)",
      "translateY" => "\($tr_value\)",
      "scale"      => "\($number(?:,$number)?\)",
      "scaleX"     => "\($number\)",
      "scaleY"     => "\($number\)",
      "rotate"     => "\($angle\)",
      "skew"       => "\($angle(?:,$angle)?\)",
      "skewX"      => "\($angle\)",
      "skewY"      => "\($angle\)",
    );
    $transforms = array();
    foreach($parts as $part) {
      $t = $part[0];
      foreach($functions as $name => $pattern) {
        if ( preg_match("/$name\s*$pattern/i", $t, $matches) ) {
          $values = array_slice($matches, 1);
          switch($name) {
            case "rotate":
            case "skew":
            case "skewX":
            case "skewY":
              foreach($values as $i => $value) {
                if ( strpos($value, "rad") ) {
                  $values[$i] = rad2deg(floatval($value));
                }
                else {
                  $values[$i] = floatval($value);
                }
              }
              switch($name) {
                case "skew":
                  if ( !isset($values[1]) ) {
                    $values[1] = 0;
                  }
                break;
                case "skewX":
                  $name = "skew";
                  $values = array($values[0], 0);
                break;
                case "skewY":
                  $name = "skew";
                  $values = array(0, $values[0]);
                break;
              }
            break;
            case "translate":
              $values[0] = $this->length_in_pt($values[0], $this->width);
              if ( isset($values[1]) ) {
                $values[1] = $this->length_in_pt($values[1], $this->height);
              }
              else {
                $values[1] = 0;
              }
            break;
            case "translateX":
              $name = "translate";
              $values = array($this->length_in_pt($values[0], $this->width), 0);
            break;
            case "translateY":
              $name = "translate";
              $values = array(0, $this->length_in_pt($values[0], $this->height));
            break;
            case "scale":
              if ( !isset($values[1]) ) {
                $values[1] = $values[0];
              }
            break;
            case "scaleX":
              $name = "scale";
              $values = array($values[0], 1.0);
            break;
            case "scaleY":
              $name = "scale";
              $values = array(1.0, $values[0]);
            break;
          }
          $transforms[] = array(
            $name, 
            $values,
          );
        }
      }
    }
    $this->_prop_cache["transform"] = null;
    $this->_props["transform"] = $transforms;
  }
  function set__webkit_transform($val) {
    $this->set_transform($val);
  }
  function set__webkit_transform_origin($val) {
    $this->set_transform_origin($val);
  }
  function set_transform_origin($val) {
    $values = preg_split("/\s+/", $val);
    if ( count($values) === 0) {
      return;
    }
    foreach($values as &$value) {
      if ( in_array($value, array("top", "left")) ) {
        $value = 0;
      }
      if ( in_array($value, array("bottom", "right")) ) {
        $value = "100%";
      }
    }
    if ( !isset($values[1]) ) {
      $values[1] = $values[0];
    }
    $this->_prop_cache["transform_origin"] = null;
    $this->_props["transform_origin"] = $values;
  }
  protected function parse_image_resolution($val) {
    $re = '/^\s*(\d+|normal|auto)\s*$/';
    if ( !preg_match($re, $val, $matches) ) {
      return null;
    }
    return $matches[1];
  }
  function set_background_image_resolution($val) {
    $parsed = $this->parse_image_resolution($val);
    $this->_prop_cache["background_image_resolution"] = null;
    $this->_props["background_image_resolution"] = $parsed;
  }
  function set_image_resolution($val) {
    $parsed = $this->parse_image_resolution($val);
    $this->_prop_cache["image_resolution"] = null;
    $this->_props["image_resolution"] = $parsed;
  }
  function set__dompdf_background_image_resolution($val) {
    $this->set_background_image_resolution($val);
  }
  function set__dompdf_image_resolution($val) {
    $this->set_image_resolution($val);
  }
  function set_z_index($val) {
    if ( round($val) != $val && $val !== "auto" ) {
      return;
    }
    $this->_prop_cache["z_index"] = null;
    $this->_props["z_index"] = $val;
  }
  function set_counter_increment($val) {
    $val = trim($val);
    $value = null;
    if ( in_array($val, array("none", "inherit")) ) {
      $value = $val;
    }
    else {
      if ( preg_match_all("/(".self::CSS_IDENTIFIER.")(?:\s+(".self::CSS_INTEGER."))?/", $val, $matches, PREG_SET_ORDER) ){
        $value = array();
        foreach($matches as $match) {
          $value[$match[1]] = isset($match[2]) ? $match[2] : 1;
        }
      }
    }
    $this->_prop_cache["counter_increment"] = null;
    $this->_props["counter_increment"] = $value;
  }
  function __toString() {
    return print_r(array_merge(array("parent_font_size" => $this->_parent_font_size),
                               $this->_props), true);
  }
  function debug_print() {
    print "parent_font_size:".$this->_parent_font_size . ";\n";
    foreach($this->_props as $prop => $val ) {
      print $prop.':'.$val;
      if (isset($this->_important_props[$prop])) {
        print '!important';
      }
      print ";\n";
    }
  }
}
