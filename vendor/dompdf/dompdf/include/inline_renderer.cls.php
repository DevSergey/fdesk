<?php
class Inline_Renderer extends Abstract_Renderer {
  function render(Frame $frame) {
    $style = $frame->get_style();
    if ( !$frame->get_first_child() )
      return; 
    $bp = $style->get_border_properties();
    $widths = array($style->length_in_pt($bp["top"]["width"]),
                    $style->length_in_pt($bp["right"]["width"]),
                    $style->length_in_pt($bp["bottom"]["width"]),
                    $style->length_in_pt($bp["left"]["width"]));
    list($x, $y) = $frame->get_first_child()->get_position();
    $w = null;
    $h = 0;
    $this->_set_opacity( $frame->get_opacity( $style->opacity ) );
    $first_row = true;
    foreach ($frame->get_children() as $child) {
      list($child_x, $child_y, $child_w, $child_h) = $child->get_padding_box();
      if ( !is_null($w) && $child_x < $x + $w ) {
        if ( ($bg = $style->background_color) !== "transparent" )
          $this->_canvas->filled_rectangle( $x, $y, $w, $h, $bg);
        if ( ($url = $style->background_image) && $url !== "none" ) {
          $this->_background_image($url, $x, $y, $w, $h, $style);
        }
        if ( $first_row ) {
          if ( $bp["left"]["style"] !== "none" && $bp["left"]["color"] !== "transparent" && $bp["left"]["width"] > 0 ) {
            $method = "_border_" . $bp["left"]["style"];            
            $this->$method($x, $y, $h + $widths[0] + $widths[2], $bp["left"]["color"], $widths, "left");
          }
          $first_row = false;
        }
        if ( $bp["top"]["style"] !== "none" && $bp["top"]["color"] !== "transparent" && $bp["top"]["width"] > 0 ) {
          $method = "_border_" . $bp["top"]["style"];
          $this->$method($x, $y, $w + $widths[1] + $widths[3], $bp["top"]["color"], $widths, "top");
        }
        if ( $bp["bottom"]["style"] !== "none" && $bp["bottom"]["color"] !== "transparent" && $bp["bottom"]["width"] > 0 ) {
          $method = "_border_" . $bp["bottom"]["style"];
          $this->$method($x, $y + $h + $widths[0] + $widths[2], $w + $widths[1] + $widths[3], $bp["bottom"]["color"], $widths, "bottom");
        }
        $link_node = null;
        if ( $frame->get_node()->nodeName === "a" ) {
          $link_node = $frame->get_node();
        }
        else if ( $frame->get_parent()->get_node()->nodeName === "a" ){
          $link_node = $frame->get_parent()->get_node();
        }
        if ( $link_node && $href = $link_node->getAttribute("href") ) {
          $this->_canvas->add_link($href, $x, $y, $w, $h);
        }
        $x = $child_x;
        $y = $child_y;
        $w = $child_w;
        $h = $child_h;
        continue;
      }
      if ( is_null($w) )
        $w = $child_w;
      else
        $w += $child_w;
      $h = max($h, $child_h);
      if (DEBUG_LAYOUT && DEBUG_LAYOUT_INLINE) {
        $this->_debug_layout($child->get_border_box(), "blue");
        if (DEBUG_LAYOUT_PADDINGBOX) {
          $this->_debug_layout($child->get_padding_box(), "blue", array(0.5, 0.5));
        }
      }
    }
    if ( ($bg = $style->background_color) !== "transparent" ) 
      $this->_canvas->filled_rectangle( $x + $widths[3], $y + $widths[0], $w, $h, $bg);
    if ( ($url = $style->background_image) && $url !== "none" )           
      $this->_background_image($url, $x + $widths[3], $y + $widths[0], $w, $h, $style);
    $w += $widths[1] + $widths[3];
    $h += $widths[0] + $widths[2];
    $left_margin = $style->length_in_pt($style->margin_left);
    $x += $left_margin;
    if ( $first_row && $bp["left"]["style"] !== "none" && $bp["left"]["color"] !== "transparent" && $widths[3] > 0 ) {
      $method = "_border_" . $bp["left"]["style"];
      $this->$method($x, $y, $h, $bp["left"]["color"], $widths, "left");
    }
    if ( $bp["top"]["style"] !== "none" && $bp["top"]["color"] !== "transparent" && $widths[0] > 0 ) {
      $method = "_border_" . $bp["top"]["style"];
      $this->$method($x, $y, $w, $bp["top"]["color"], $widths, "top");
    }
    if ( $bp["bottom"]["style"] !== "none" && $bp["bottom"]["color"] !== "transparent" && $widths[2] > 0 ) {
      $method = "_border_" . $bp["bottom"]["style"];
      $this->$method($x, $y + $h, $w, $bp["bottom"]["color"], $widths, "bottom");
    }
    if ( $bp["right"]["style"] !== "none" && $bp["right"]["color"] !== "transparent" && $widths[1] > 0 ) {
      $method = "_border_" . $bp["right"]["style"];
      $this->$method($x + $w, $y, $h, $bp["right"]["color"], $widths, "right");
    }
    $link_node = null;
    if ( $frame->get_node()->nodeName === "a" ) {
      $link_node = $frame->get_node();
      if ( ($name = $link_node->getAttribute("name")) || ($name = $link_node->getAttribute("id")) ) {
        $this->_canvas->add_named_dest($name);
      }
    }
    if ( $frame->get_parent() && $frame->get_parent()->get_node()->nodeName === "a" ){
      $link_node = $frame->get_parent()->get_node();
    }
    if ( $link_node ) {
      if ( $href = $link_node->getAttribute("href") )
        $this->_canvas->add_link($href, $x, $y, $w, $h);
    }
  }
}
