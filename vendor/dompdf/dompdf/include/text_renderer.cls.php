<?php
class Text_Renderer extends Abstract_Renderer {
  const DECO_THICKNESS = 0.02;     
  const UNDERLINE_OFFSET = 0.0;    
  const OVERLINE_OFFSET = 0.0;    
  const LINETHROUGH_OFFSET = 0.0; 
  const DECO_EXTENSION = 0.0;     
  function render(Frame $frame) {
    $text = $frame->get_text();
    if ( trim($text) === "" )
      return;
    $style = $frame->get_style();
    list($x, $y) = $frame->get_position();
    $cb = $frame->get_containing_block();
    if ( ($ml = $style->margin_left) === "auto" || $ml === "none" )
      $ml = 0;
    if ( ($pl = $style->padding_left) === "auto" || $pl === "none" )
      $pl = 0;
    if ( ($bl = $style->border_left_width) === "auto" || $bl === "none" )
      $bl = 0;
    $x += $style->length_in_pt( array($ml, $pl, $bl), $cb["w"] );
    $font = $style->font_family;
    $size = $frame_font_size = $style->font_size;
    $height = $style->height;
    $word_spacing = $frame->get_text_spacing() + $style->length_in_pt($style->word_spacing);
    $char_spacing = $style->length_in_pt($style->letter_spacing);
    $width = $style->width;
    $this->_canvas->text($x, $y, $text,
                         $font, $size,
                         $style->color, $word_spacing, $char_spacing);
    $line = $frame->get_containing_line();
    if ( false && $line->tallest_frame ) {
      $base_frame = $line->tallest_frame;
      $style = $base_frame->get_style();
      $size = $style->font_size;
      $height = $line->h * ($size / $style->line_height);
    }
    $line_thickness      = $size * self::DECO_THICKNESS;
    $underline_offset    = $size * self::UNDERLINE_OFFSET;
    $overline_offset     = $size * self::OVERLINE_OFFSET;
    $linethrough_offset  = $size * self::LINETHROUGH_OFFSET;
    $underline_position  = -0.08;
    if ( $this->_canvas instanceof CPDF_Adapter ) {
      $cpdf_font = $this->_canvas->get_cpdf()->fonts[$style->font_family];
      if (isset($cpdf_font["UnderlinePosition"])) {
        $underline_position = $cpdf_font["UnderlinePosition"]/1000;
      }
      if (isset($cpdf_font["UnderlineThickness"])) {
        $line_thickness = $size * ($cpdf_font["UnderlineThickness"]/1000);
      }
    }
    $descent = $size * $underline_position;
    $base    = $size;
    $p = $frame;
    $stack = array();
    while ( $p = $p->get_parent() )
      $stack[] = $p;
    while ( isset($stack[0]) ) {
      $f = array_pop($stack);
      if ( ($text_deco = $f->get_style()->text_decoration) === "none" )
        continue;
      $deco_y = $y; 
      $color = $f->get_style()->color;
      switch ($text_deco) {
      default:
        continue;
      case "underline":
        $deco_y += $base - $descent + $underline_offset + $line_thickness/2;
        break;
      case "overline":
        $deco_y += $overline_offset + $line_thickness/2;
        break;
      case "line-through":
        $deco_y += $base * 0.7 + $linethrough_offset;
        break;
      }
      $dx = 0;
      $x1 = $x - self::DECO_EXTENSION;
      $x2 = $x + $width + $dx + self::DECO_EXTENSION;
      $this->_canvas->line($x1, $deco_y, $x2, $deco_y, $color, $line_thickness);
    }
    if (DEBUG_LAYOUT && DEBUG_LAYOUT_LINES) {
      $text_width = Font_Metrics::get_text_width($text, $font, $frame_font_size);
      $this->_debug_layout(array($x, $y, $text_width+($line->wc-1)*$word_spacing, $frame_font_size), "orange", array(0.5, 0.5));
    }
  }
}
