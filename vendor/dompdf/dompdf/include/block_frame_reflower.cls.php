<?php
class Block_Frame_Reflower extends Frame_Reflower {
  const MIN_JUSTIFY_WIDTH = 0.80;
  protected $_frame;
  function __construct(Block_Frame_Decorator $frame) { parent::__construct($frame); }
  protected function _calculate_width($width) {
    $frame = $this->_frame;
    $style = $frame->get_style();
    $w = $frame->get_containing_block("w");
    if ( $style->position === "fixed" ) {
      $w = $frame->get_parent()->get_containing_block("w");
    }
    $rm = $style->length_in_pt($style->margin_right, $w);
    $lm = $style->length_in_pt($style->margin_left, $w);
    $left = $style->length_in_pt($style->left, $w);
    $right = $style->length_in_pt($style->right, $w);
    $dims = array($style->border_left_width,
                  $style->border_right_width,
                  $style->padding_left,
                  $style->padding_right,
                  $width !== "auto" ? $width : 0,
                  $rm !== "auto" ? $rm : 0,
                  $lm !== "auto" ? $lm : 0);
    if ( $frame->is_absolute() ) {
      $absolute = true;
      $dims[] = $left !== "auto" ? $left : 0;
      $dims[] = $right !== "auto" ? $right : 0;
    }
    else {
      $absolute = false;
    }
    $sum = $style->length_in_pt($dims, $w);
    $diff = $w - $sum;
    if ( $diff > 0 ) {
      if ( $absolute ) {
        if ( $width === "auto" && $left === "auto" && $right === "auto" ) {
          if ( $lm === "auto" ) $lm = 0;
          if ( $rm === "auto" ) $rm = 0;
          $left = 0;
          $right = 0;
          $width = $diff;
        }
        else if ( $width === "auto" ) {
          if ( $lm    === "auto" ) $lm = 0;
          if ( $rm    === "auto" ) $rm = 0;
          if ( $left  === "auto" ) $left = 0;
          if ( $right === "auto" ) $right = 0;
          $width = $diff;
        }
        else if ( $left === "auto" ) {
          if ( $lm    === "auto" ) $lm = 0;
          if ( $rm    === "auto" ) $rm = 0;
          if ( $right === "auto" ) $right = 0;
          $left = $diff;
        }
        else if ( $right === "auto" ) {
          if ( $lm === "auto" ) $lm = 0;
          if ( $rm === "auto" ) $rm = 0;
          $right = $diff;
        }
      }
      else {
        if ( $width === "auto" ) {
          $width = $diff;
        }
        else if ( $lm === "auto" && $rm === "auto" ) {
          $lm = $rm = round($diff / 2);
        }
        else if ( $lm === "auto" ) {
          $lm = $diff;
        }
        else if ( $rm === "auto" ) {
          $rm = $diff;
        }
      }
    }
    else if ($diff < 0) {
      $rm = $diff;
    }
    return array(
      "width"        => $width,
      "margin_left"  => $lm,
      "margin_right" => $rm,
      "left"         => $left,
      "right"        => $right,
    );
  }
  protected function _calculate_restricted_width() {
    $frame = $this->_frame;
    $style = $frame->get_style();
    $cb = $frame->get_containing_block();
    if ( $style->position === "fixed" ) {
      $cb = $frame->get_root()->get_containing_block();
    }
    if ( !isset($cb["w"]) ) {
      throw new DOMPDF_Exception("Box property calculation requires containing block width");
    }
    if ( $style->width === "100%" ) {
      $width = "auto";
    }
    else {
      $width = $style->length_in_pt($style->width, $cb["w"]);
    }
    extract($this->_calculate_width($width));
    $min_width = $style->length_in_pt($style->min_width, $cb["w"]);
    $max_width = $style->length_in_pt($style->max_width, $cb["w"]);
    if ( $max_width !== "none" && $min_width > $max_width ) {
      list($max_width, $min_width) = array($min_width, $max_width);
    }
    if ( $max_width !== "none" && $width > $max_width ) {
      extract($this->_calculate_width($max_width));
    }
    if ( $width < $min_width ) {
      extract($this->_calculate_width($min_width));
    }
    return array($width, $margin_left, $margin_right, $left, $right);
  }
  protected function _calculate_content_height() {
    $lines = $this->_frame->get_line_boxes();
    $height = 0;
    foreach ($lines as $line) {
      $height += $line->h;
    }
    return $height;
  }
  protected function _calculate_restricted_height() {
    $frame = $this->_frame;
    $style = $frame->get_style();
    $content_height = $this->_calculate_content_height();
    $cb = $frame->get_containing_block();
    $height = $style->length_in_pt($style->height, $cb["h"]);
    $top    = $style->length_in_pt($style->top, $cb["h"]);
    $bottom = $style->length_in_pt($style->bottom, $cb["h"]);
    $margin_top    = $style->length_in_pt($style->margin_top, $cb["h"]);
    $margin_bottom = $style->length_in_pt($style->margin_bottom, $cb["h"]);
    if ( $frame->is_absolute() ) {
      $dims = array($top !== "auto" ? $top : 0,
                    $style->margin_top !== "auto" ? $style->margin_top : 0,
                    $style->padding_top,
                    $style->border_top_width,
                    $height !== "auto" ? $height : 0,
                    $style->border_bottom_width,
                    $style->padding_bottom,
                    $style->margin_bottom !== "auto" ? $style->margin_bottom : 0,
                    $bottom !== "auto" ? $bottom : 0);
      $sum = $style->length_in_pt($dims, $cb["h"]);
      $diff = $cb["h"] - $sum; 
      if ( $diff > 0 ) {
        if ( $height === "auto" && $top === "auto" && $bottom === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $height = $diff;
        }
        else if ( $height === "auto" && $top === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $height = $content_height;
          $top = $diff - $content_height;
        }
        else if ( $height === "auto" && $bottom === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $height = $content_height;
          $bottom = $diff - $content_height;
        }
        else if ( $top === "auto" && $bottom === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $bottom = $diff;
        }
        else if ( $top === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $top = $diff;
        }
        else if ( $height === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $height = $diff;
        }
        else if ( $bottom === "auto" ) {
          if ( $margin_top    === "auto" ) $margin_top = 0;
          if ( $margin_bottom === "auto" ) $margin_bottom = 0;
          $bottom = $diff;
        }
        else {
          if ( $style->overflow === "visible" ) {
            if ( $margin_top    === "auto" ) $margin_top = 0;
            if ( $margin_bottom === "auto" ) $margin_bottom = 0;
            if ( $top           === "auto" ) $top = 0;
            if ( $bottom        === "auto" ) $bottom = 0;
            if ( $height        === "auto" ) $height = $content_height;
          }
        }
      }
    }
    else {
      if ( $height === "auto" && $content_height > $height ) {
        $height = $content_height;
      }
      if ( !($style->overflow === "visible" ||
             ($style->overflow === "hidden" && $height === "auto")) ) {
        $min_height = $style->min_height;
        $max_height = $style->max_height;
        if ( isset($cb["h"]) ) {
          $min_height = $style->length_in_pt($min_height, $cb["h"]);
          $max_height = $style->length_in_pt($max_height, $cb["h"]);
        }
        else if ( isset($cb["w"]) ) {
          if ( mb_strpos($min_height, "%") !== false ) {
            $min_height = 0;
          }
          else {
            $min_height = $style->length_in_pt($min_height, $cb["w"]);
          }
          if ( mb_strpos($max_height, "%") !== false ) {
            $max_height = "none";
          }
          else {
            $max_height = $style->length_in_pt($max_height, $cb["w"]);
          }
        }
        if ( $max_height !== "none" && $min_height > $max_height ) {
          list($max_height, $min_height) = array($min_height, $max_height);
        }
        if ( $max_height !== "none" && $height > $max_height ) {
          $height = $max_height;
        }
        if ( $height < $min_height ) {
          $height = $min_height;
        }
      }
    }
    return array($height, $margin_top, $margin_bottom, $top, $bottom);
  }
  protected function _text_align() {
    $style = $this->_frame->get_style();
    $w = $this->_frame->get_containing_block("w");
    $width = $style->length_in_pt($style->width, $w);
    switch ($style->text_align) {
      default:
      case "left":
        foreach ($this->_frame->get_line_boxes() as $line) {
          if ( !$line->left ) {
            continue;
          }
          foreach($line->get_frames() as $frame) {
            if ( $frame instanceof Block_Frame_Decorator) {
              continue;
            }
            $frame->set_position( $frame->get_position("x") + $line->left );
          }
        }
        return;
      case "right":
        foreach ($this->_frame->get_line_boxes() as $line) {
          $dx = $width - $line->w - $line->right;
          foreach($line->get_frames() as $frame) {
            if ($frame instanceof Block_Frame_Decorator) {
              continue;
            }
            $frame->set_position( $frame->get_position("x") + $dx );
          }
        }
        break;
      case "justify":
        $lines = $this->_frame->get_line_boxes(); 
        array_pop($lines);
        foreach($lines as $i => $line) {
          if ( $line->br ) {
            unset($lines[$i]);
          }
        }
        $space_width = Font_Metrics::get_text_width(" ", $style->font_family, $style->font_size);
        foreach ($lines as $line) {
          if ( $line->left ) {
            foreach ( $line->get_frames() as $frame ) {
              if ( !$frame instanceof Text_Frame_Decorator ) {
                continue;
              }
              $frame->set_position( $frame->get_position("x") + $line->left );
            }
          }
            if ( $line->wc > 1 ) {
              $spacing = ($width - ($line->left + $line->w + $line->right) + $space_width) / ($line->wc - 1);
            }
            else {
              $spacing = 0;
            }
            $dx = 0;
            foreach($line->get_frames() as $frame) {
              if ( !$frame instanceof Text_Frame_Decorator ) {
                continue;
              }
              $text = $frame->get_text();
              $spaces = mb_substr_count($text, " ");
              $char_spacing = $style->length_in_pt($style->letter_spacing);
              $_spacing = $spacing + $char_spacing;
              $frame->set_position( $frame->get_position("x") + $dx );
              $frame->set_text_spacing($_spacing);
              $dx += $spaces * $_spacing;
            }
            $line->w = $width;
        }
        break;
      case "center":
      case "centre":
        foreach ($this->_frame->get_line_boxes() as $line) {
          $dx = ($width + $line->left - $line->w - $line->right ) / 2;
          foreach ($line->get_frames() as $frame) {
            if ($frame instanceof Block_Frame_Decorator) {
              continue;
            }
            $frame->set_position( $frame->get_position("x") + $dx );
          }
        }
        break;
    }
  }
  function vertical_align() {
    $canvas = null;
    foreach ( $this->_frame->get_line_boxes() as $line ) {
      $height = $line->h;
      foreach ( $line->get_frames() as $frame ) {
        $style = $frame->get_style();
        if ( $style->display !== "inline" ) {
          continue;
        }
        $align = $frame->get_parent()->get_style()->vertical_align;
        if ( !isset($canvas) ) {
          $canvas = $frame->get_root()->get_dompdf()->get_canvas();
        }
        $baseline = $canvas->get_font_baseline($style->font_family, $style->font_size);
        $y_offset = 0;
        switch ($align) {
          case "baseline":
            $y_offset = $height*0.8 - $baseline; 
            break;
          case "middle":
            $y_offset = ($height*0.8 - $baseline) / 2;
            break;
          case "sub":
            $y_offset = 0.3 * $height;
            break;
          case "super":
            $y_offset = -0.2 * $height;
            break;
          case "text-top":
          case "top": 
            break;
          case "text-bottom":
          case "bottom":
            $y_offset = $height*0.8 - $baseline;
            break;
        }
        if ( $y_offset ) {
          $frame->move(0, $y_offset);
        }
      }
    }
  }
  function process_clear(Frame $child){
    $enable_css_float = $this->get_dompdf()->get_option("enable_css_float");
    if ( !$enable_css_float ) {
      return;
    }
    $child_style = $child->get_style();
    $root = $this->_frame->get_root();
    if ( $child_style->clear !== "none" ) {
      $lowest_y = $root->get_lowest_float_offset($child);
      if ( $lowest_y ) {
        if ( $child->is_in_flow() ) {
          $line_box = $this->_frame->get_current_line_box();
          $line_box->y = $lowest_y + $child->get_margin_height();
          $line_box->left = 0;
          $line_box->right = 0;
        }
        $child->move(0, $lowest_y - $child->get_position("y"));
      }
    }
  }
  function process_float(Frame $child, $cb_x, $cb_w){
    $enable_css_float = $this->_frame->get_dompdf()->get_option("enable_css_float");
    if ( !$enable_css_float ) {
      return;
    }
    $child_style = $child->get_style();
    $root = $this->_frame->get_root();
    if ( $child_style->float !== "none" ) {
      $root->add_floating_frame($child);
      $next = $child->get_next_sibling();
      if ( $next && $next instanceof Text_Frame_Decorator) {
        $next->set_text(ltrim($next->get_text()));
      }
      $line_box = $this->_frame->get_current_line_box();
      list($old_x, $old_y) = $child->get_position();
      $float_x = $cb_x;
      $float_y = $old_y;
      $float_w = $child->get_margin_width();
      if ( $child_style->clear === "none" ) {
        switch( $child_style->float ) {
          case "left": 
            $float_x += $line_box->left;
            break;
          case "right": 
            $float_x += ($cb_w - $line_box->right - $float_w);
            break;
        }
      }
      else {
        if ( $child_style->float === "right" ) {
          $float_x += ($cb_w - $float_w);
        }
      }
      if ( $cb_w < $float_x + $float_w - $old_x ) {
      }
      $line_box->get_float_offsets();
      if ( $child->_float_next_line ) {
        $float_y += $line_box->h;
      }
      $child->set_position($float_x, $float_y);
      $child->move($float_x - $old_x, $float_y - $old_y, true);
    }
  }
  function reflow(Block_Frame_Decorator $block = null) {
    $page = $this->_frame->get_root();
    $page->check_forced_page_break($this->_frame);
    if ( $page->is_full() ) {
      return;
    }
    $this->_set_content();
    $this->_collapse_margins();
    $style = $this->_frame->get_style();
    $cb = $this->_frame->get_containing_block();
    if ( $style->position === "fixed" ) {
      $cb = $this->_frame->get_root()->get_containing_block();
    }
    list($w, $left_margin, $right_margin, $left, $right) = $this->_calculate_restricted_width();
    $style->width = $w . "pt";
    $style->margin_left = $left_margin."pt";
    $style->margin_right = $right_margin."pt";
    $style->left = $left ."pt";
    $style->right = $right . "pt";
    $this->_frame->position();
    list($x, $y) = $this->_frame->get_position();
    $indent = $style->length_in_pt($style->text_indent, $cb["w"]);
    $this->_frame->increase_line_width($indent);
    $top = $style->length_in_pt(array($style->margin_top,
                                      $style->padding_top,
                                      $style->border_top_width), $cb["h"]);
    $bottom = $style->length_in_pt(array($style->border_bottom_width,
                                         $style->margin_bottom,
                                         $style->padding_bottom), $cb["h"]);
    $cb_x = $x + $left_margin + $style->length_in_pt(array($style->border_left_width, 
                                                           $style->padding_left), $cb["w"]);
    $cb_y = $y + $top;
    $cb_h = ($cb["h"] + $cb["y"]) - $bottom - $cb_y;
    $line_box = $this->_frame->get_current_line_box();
    $line_box->y = $cb_y;
    $line_box->get_float_offsets();
    foreach ( $this->_frame->get_children() as $child ) {
      if ( $page->is_full() ) {
        break;
      }
      $child->set_containing_block($cb_x, $cb_y, $w, $cb_h);
      $this->process_clear($child);
      $child->reflow($this->_frame);
      if ( $page->check_page_break($child) ) {
        break;
      }
      $this->process_float($child, $cb_x, $w);
    }
    list($height, $margin_top, $margin_bottom, $top, $bottom) = $this->_calculate_restricted_height();
    $style->height = $height;
    $style->margin_top = $margin_top;
    $style->margin_bottom = $margin_bottom;
    $style->top = $top;
    $style->bottom = $bottom;
    $needs_reposition = ($style->position === "absolute" && ($style->right !== "auto" || $style->bottom !== "auto"));
    if ( $needs_reposition ) {
      $orig_style = $this->_frame->get_original_style();
      if ( $orig_style->width === "auto" && ($orig_style->left === "auto" || $orig_style->right === "auto") ) {
        $width = 0;
        foreach ($this->_frame->get_line_boxes() as $line) {
          $width = max($line->w, $width);
        }
        $style->width = $width;
      }
      $style->left = $orig_style->left;
      $style->right = $orig_style->right;
    }
    $this->_text_align();
    $this->vertical_align();
    if ( $needs_reposition ) {
      list($x, $y) = $this->_frame->get_position();
      $this->_frame->position();
      list($new_x, $new_y) = $this->_frame->get_position();
      $this->_frame->move($new_x-$x, $new_y-$y, true);
    }
    if ( $block && $this->_frame->is_in_flow() ) {
      $block->add_frame_to_line($this->_frame);
      if ( $style->display === "block" ) {
        $block->add_line();
      }
    }
  }
}