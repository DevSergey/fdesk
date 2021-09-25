<?php
abstract class Frame_Decorator extends Frame {
  const DEFAULT_COUNTER = "-dompdf-default-counter";
  public $_counters = array(); 
  protected $_root;
  protected $_frame;
  protected $_positioner;
  protected $_reflower;
  protected $_dompdf;
  private $_block_parent;
  private $_positionned_parent;
  function __construct(Frame $frame, DOMPDF $dompdf) {
    $this->_frame = $frame;
    $this->_root = null;
    $this->_dompdf = $dompdf;
    $frame->set_decorator($this);
  }
  function dispose($recursive = false) {
    if ( $recursive ) {
      while ( $child = $this->get_first_child() ) {
        $child->dispose(true);
      }
    }
    $this->_root = null;
    unset($this->_root);
    $this->_frame->dispose(true);
    $this->_frame = null;
    unset($this->_frame);
    $this->_positioner = null;
    unset($this->_positioner);
    $this->_reflower = null;
    unset($this->_reflower);
  }
  function copy(DOMNode $node) {
    $frame = new Frame($node);
    $frame->set_style(clone $this->_frame->get_original_style());
    return Frame_Factory::decorate_frame($frame, $this->_dompdf, $this->_root);
  }
  function deep_copy() {
    $frame = new Frame($this->get_node()->cloneNode());
    $frame->set_style(clone $this->_frame->get_original_style());
    $deco = Frame_Factory::decorate_frame($frame, $this->_dompdf, $this->_root);
    foreach ($this->get_children() as $child) {
      $deco->append_child($child->deep_copy());
    }
    return $deco;
  }
  function reset() {
    $this->_frame->reset();
    $this->_counters = array();
    foreach ($this->get_children() as $child) {
      $child->reset();
    }
  }
  function get_id() {
    return $this->_frame->get_id();
  }
  function get_frame() {
    return $this->_frame;
  }
  function get_node() {
    return $this->_frame->get_node();
  }
  function get_style() {
    return $this->_frame->get_style();
  }
  function get_original_style() {
    return $this->_frame->get_original_style();
  }
  function get_containing_block($i = null) {
    return $this->_frame->get_containing_block($i);
  }
  function get_position($i = null) {
    return $this->_frame->get_position($i);
  }
  function get_dompdf() {
    return $this->_dompdf;
  }
  function get_margin_height() {
    return $this->_frame->get_margin_height();
  }
  function get_margin_width() {
    return $this->_frame->get_margin_width();
  }
  function get_padding_box() {
    return $this->_frame->get_padding_box();
  }
  function get_border_box() {
    return $this->_frame->get_border_box();
  }
  function set_id($id) {
    $this->_frame->set_id($id);
  }
  function set_style(Style $style) {
    $this->_frame->set_style($style);
  }
  function set_containing_block($x = null, $y = null, $w = null, $h = null) {
    $this->_frame->set_containing_block($x, $y, $w, $h);
  }
  function set_position($x = null, $y = null) {
    $this->_frame->set_position($x, $y);
  }
  function __toString() {
    return $this->_frame->__toString();
  }
  function prepend_child(Frame $child, $update_node = true) {
    while ( $child instanceof Frame_Decorator ) {
      $child = $child->_frame;
    }
    $this->_frame->prepend_child($child, $update_node);
  }
  function append_child(Frame $child, $update_node = true) {
    while ( $child instanceof Frame_Decorator ) {
      $child = $child->_frame;
    }
    $this->_frame->append_child($child, $update_node);
  }
  function insert_child_before(Frame $new_child, Frame $ref, $update_node = true) {
    while ( $new_child instanceof Frame_Decorator ) {
      $new_child = $new_child->_frame;
    }
    if ( $ref instanceof Frame_Decorator ) {
      $ref = $ref->_frame;
    }
    $this->_frame->insert_child_before($new_child, $ref, $update_node);
  }
  function insert_child_after(Frame $new_child, Frame $ref, $update_node = true) {
    while ( $new_child instanceof Frame_Decorator ) {
      $new_child = $new_child->_frame;
    }
    while ( $ref instanceof Frame_Decorator ) {
      $ref = $ref->_frame;
    }
    $this->_frame->insert_child_after($new_child, $ref, $update_node);
  }
  function remove_child(Frame $child, $update_node = true) {
    while  ( $child instanceof Frame_Decorator ) {
      $child = $child->_frame;
    }
    return $this->_frame->remove_child($child, $update_node);
  }
  function get_parent() {
    $p = $this->_frame->get_parent();
    if ( $p && $deco = $p->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() ) {
        $deco = $tmp;
      }
      return $deco;
    }
    else if ( $p ) {
      return $p;
    }
    return null;
  }
  function get_first_child() {
    $c = $this->_frame->get_first_child();
    if ( $c && $deco = $c->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() ) {
        $deco = $tmp;
      }
      return $deco;
    }
    else if ( $c ) {
      return $c;
    }
    return null;
  }
  function get_last_child() {
    $c = $this->_frame->get_last_child();
    if ( $c && $deco = $c->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() ) {
        $deco = $tmp;
      }
      return $deco;
    }
    else if ( $c ) {
      return $c;
    }
    return null;
  }
  function get_prev_sibling() {
    $s = $this->_frame->get_prev_sibling();
    if ( $s && $deco = $s->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() ) {
        $deco = $tmp;
      }
      return $deco;
    }
    else if ( $s ) {
      return $s;
    }
    return null;
  }
  function get_next_sibling() {
    $s = $this->_frame->get_next_sibling();
    if ( $s && $deco = $s->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() ) {
        $deco = $tmp;
      }
      return $deco;
    }
    else if ( $s ) {
      return $s;
    }
    return null;
  }
  function get_subtree() {
    return new FrameTreeList($this);
  }
  function set_positioner(Positioner $posn) {
    $this->_positioner = $posn;
    if ( $this->_frame instanceof Frame_Decorator ) {
      $this->_frame->set_positioner($posn);
    }
  }
  function set_reflower(Frame_Reflower $reflower) {
    $this->_reflower = $reflower;
    if ( $this->_frame instanceof Frame_Decorator ) {
      $this->_frame->set_reflower( $reflower );
    }
  }
  function get_reflower() {
    return $this->_reflower;
  }
  function set_root(Frame $root) {
    $this->_root = $root;
    if ( $this->_frame instanceof Frame_Decorator ) {
      $this->_frame->set_root($root);
    }
  }
  function get_root() {
    return $this->_root;
  }
  function find_block_parent() {
    $p = $this->get_parent();
    while ( $p ) {
      if ( $p->is_block() ) {
        break;
      }
      $p = $p->get_parent();
    }
    return $this->_block_parent = $p;
  }
  function find_positionned_parent() {
    $p = $this->get_parent();
    while ( $p ) {
      if ( $p->is_positionned() ) {
        break;
      }
      $p = $p->get_parent();
    }
    if ( !$p ) {
      $p = $this->_root->get_first_child(); 
    }
    return $this->_positionned_parent = $p;
  }
  function split(Frame $child = null, $force_pagebreak = false) {
    $style = $this->_frame->get_style();
    if ( $this->_frame->get_node()->nodeName !== "body" && $style->counter_increment && ($decrement = $style->counter_increment) !== "none" ) {
      $this->decrement_counters($decrement);
    }
    if ( is_null( $child ) ) {
      if ( !$this->is_text_node() && $this->get_node()->hasAttribute("dompdf_before_frame_id") ) {
        foreach($this->_frame->get_children() as $child) {
          if ( $this->get_node()->getAttribute("dompdf_before_frame_id") == $child->get_id() && $child->get_position('x') !== NULL ) {
            $style = $child->get_style();
            if ( $style->counter_increment && ($decrement = $style->counter_increment) !== "none" ) {
              $this->decrement_counters($decrement);
            }
          }
        }
      }
      $this->get_parent()->split($this, $force_pagebreak);
      return;
    }
    if ( $child->get_parent() !== $this ) {
      throw new DOMPDF_Exception("Unable to split: frame is not a child of this one.");
    }
    $node = $this->_frame->get_node();
    $split = $this->copy( $node->cloneNode() );
    $split->reset();
    $split->get_original_style()->text_indent = 0;
    $split->_splitted = true;
    if ( $node->nodeName !== "body" ) {
      $style = $this->_frame->get_style();
      $style->margin_bottom = 0;
      $style->padding_bottom = 0;
      $style->border_bottom = 0;
      $orig_style = $split->get_original_style();
      $orig_style->text_indent = 0;
      $orig_style->margin_top = 0;
      $orig_style->padding_top = 0;
      $orig_style->border_top = 0;
    }
    $this->get_parent()->insert_child_after($split, $this);
    $iter = $child;
    while ($iter) {
      $frame = $iter;
      $iter = $iter->get_next_sibling();
      $frame->reset();
      $split->append_child($frame);
    }
    $this->get_parent()->split($split, $force_pagebreak);
    if ( $style->counter_reset && ( $reset = $style->counter_reset ) !== "none" ) {
      $vars = preg_split( '/\s+/' , trim( $reset ) , 2 );
      $split->_counters[ '__' . $vars[0] ] = $this->lookup_counter_frame( $vars[0] )->_counters[$vars[0]];
    }
  }
  function reset_counter($id = self::DEFAULT_COUNTER, $value = 0) {
    $this->get_parent()->_counters[$id] = intval($value);
  }
  function decrement_counters($counters) {
    foreach($counters as $id => $increment) {
      $this->increment_counter($id, intval($increment) * -1);
    }
  }
  function increment_counters($counters) {
    foreach($counters as $id => $increment) {
      $this->increment_counter($id, intval($increment));
    }
  }
  function increment_counter($id = self::DEFAULT_COUNTER, $increment = 1) {
    $counter_frame = $this->lookup_counter_frame($id);
    if ( $counter_frame ) {
      if ( !isset($counter_frame->_counters[$id]) ) {
        $counter_frame->_counters[$id] = 0;
      }
      $counter_frame->_counters[$id] += $increment;
    }
  }
  function lookup_counter_frame($id = self::DEFAULT_COUNTER) {
    $f = $this->get_parent();
    while( $f ) {
      if( isset($f->_counters[$id]) ) {
        return $f;
      }
      $fp = $f->get_parent();
      if ( !$fp ) {
        return $f;
      }
      $f = $fp;
    }
  }
  function counter_value($id = self::DEFAULT_COUNTER, $type = "decimal") {
    $type = mb_strtolower($type);
    if ( !isset($this->_counters[$id]) ) {
      $this->_counters[$id] = 0;
    }
    $value = $this->_counters[$id];
    switch ($type) {
      default:
      case "decimal":
        return $value;
      case "decimal-leading-zero":
        return str_pad($value, 2, "0");
      case "lower-roman":
        return dec2roman($value);
      case "upper-roman":
        return mb_strtoupper(dec2roman($value));
      case "lower-latin":
      case "lower-alpha":
        return chr( ($value % 26) + ord('a') - 1);
      case "upper-latin":
      case "upper-alpha":
        return chr( ($value % 26) + ord('A') - 1);
      case "lower-greek":
        return unichr($value + 944);
      case "upper-greek":
        return unichr($value + 912);
    }
  }
  final function position() {
    $this->_positioner->position();
  }
  final function move($offset_x, $offset_y, $ignore_self = false) {
    $this->_positioner->move($offset_x, $offset_y, $ignore_self); 
  }
  final function reflow(Block_Frame_Decorator $block = null) {
    $this->_reflower->reflow($block);
  }
  final function get_min_max_width() {
    return $this->_reflower->get_min_max_width();
  }
}
