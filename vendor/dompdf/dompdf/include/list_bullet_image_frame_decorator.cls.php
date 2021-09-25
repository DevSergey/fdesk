<?php
class List_Bullet_Image_Frame_Decorator extends Frame_Decorator {
  protected $_img;
  protected $_width;
  protected $_height;
  function __construct(Frame $frame, DOMPDF $dompdf) {
    $style = $frame->get_style();
    $url = $style->list_style_image;
    $frame->get_node()->setAttribute("src", $url);
    $this->_img = new Image_Frame_Decorator($frame, $dompdf);
    parent::__construct($this->_img, $dompdf);
    list($width, $height) = dompdf_getimagesize($this->_img->get_image_url());
    $dpi = $this->_dompdf->get_option("dpi");
    $this->_width = ((float)rtrim($width, "px") * 72) / $dpi;
    $this->_height = ((float)rtrim($height, "px") * 72) / $dpi;
  }
  function get_width() {
    return $this->_frame->get_style()->get_font_size()*List_Bullet_Frame_Decorator::BULLET_SIZE + 
      2 * List_Bullet_Frame_Decorator::BULLET_PADDING;
  }
  function get_height() {
    return $this->_height;
  }
  function get_margin_width() {
    if ( $this->_frame->get_style()->list_style_position === "outside" ||
         $this->_width == 0) 
      return 0;
    return $this->_width + 2 * List_Bullet_Frame_Decorator::BULLET_PADDING;
  }
  function get_margin_height() {
    return $this->_height + 2 * List_Bullet_Frame_Decorator::BULLET_PADDING;
  }
  function get_image_url() {
    return $this->_img->get_image_url();
  }
}