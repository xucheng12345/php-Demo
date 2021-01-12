<?php
namespace Thumb;

class Config{
    /**
     *如果图片不存在 默认加载的图片 
     */
    const defaultImg =  'images/default.jpg';
    /**
     * @const json 允许的图片大小 {宽:高} 如果是false 那么就禁用这个选项 如果是0  那么就不限制
     */
    const seze =  '{"120":130}';
    
    
    const percent = 0;
    
    
    const adaptive = false;
    
  
    const center = false;
    
 
    const rotate = 0;

}