<?php
/*
Plugin Name: WP Twitter Plugin
Plugin URI: http://wptwitterplugin.wordpress.com
Contributors: wptwitterplugin
Author: wptwitterplugin.wordpress.com
Author URI: http://wptwitterplugin.wordpress.com
Description: Simple Twitter Widget for WordPress.
Version: 1.02
*/
class wptwitterplugin_Widget extends WP_Widget {
	protected $defaults = array('title' => 'Follow Us on Twitter', 'username' => '', 'showname' => 'true', 'showcount' => 'true', 'size' => 'large');
	protected $bools = array('false','true');
	protected $sizes = array('small','large');
	function wptwitterplugin_Widget() {
		$this->WP_Widget('wptwitterplugin-Widget', __('Simple Twitter Widget', 'wptwitterplugin'), array('classname' => 'wptwitterplugin_Widget', 'description' => __('Simple Twitter Widget for WordPress')));
		$this->alt_option_name = 'wptwitterplugin_Widget';
	}
	function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args($instance, $this->defaults);
		if (isset($instance['username'])) {
			echo $before_widget.(isset($instance['title'])?$before_title.$instance['title'].$after_title:'').'<a href="https://twitter.com/'.$instance['username'].'" class="twitter-follow-button" data-size="'.$instance['size'].'" data-show-screen-name="'.$instance['showname'].'" data-show-count="'.$instance['showcount'].'">Follow @'.$instance['username'].'</a><script src="https://platform.twitter.com/widgets.js"></script>'.$after_widget;
		}
	}
	function field($name, $label, $value = '', $type = 'text') {
		$HTML = '<p><label for="'.$this->get_field_id($name).'">'.$label.':</label><br />';
		if (is_array($type)) {
			$HTML .= '<select name="'.$this->get_field_name($name).'" id="'.$this->get_field_id($name).'">';
			foreach ($type as $o)
				$HTML .= '<option value="'.$o.'"'.($value==$o?" selected":"").'>'.$o.'</option>';
			$HTML .= '</select>';
		} else
			$HTML .= '<input style="width: 100%;" type="'.$type.'" name="'.$this->get_field_name($name).'" id="'.$this->get_field_id($name).'" value="'.$value.'" /></p>';
		return $HTML;
	}
	function form($instance) {
		$instance = wp_parse_args($instance, $this->defaults);
		echo $this->field('title', __('Optional Widget Title'), esc_attr($instance['title'])).$this->field('username', __('Twitter Username'), esc_attr($instance['username'])).$this->field('showname', __('Show Twitter Username'), esc_attr($instance['showname']), $this->bools).$this->field('showcount', __('Show Follow Count'), esc_attr($instance['showcount']), $this->bools).$this->field('size', __('Twitter Button Size'), esc_attr($instance['size']), $this->sizes);
	}
	function update($new, $old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['username'] = strip_tags($new['username']);
		$instance['size'] = strip_tags($new['size']);
		$instance['showname'] = strip_tags($new['showname']);
		$instance['showcount'] = strip_tags($new['showcount']);
		return $instance;
	}
	function flush_widget_cache() {
		wp_cache_delete('wptwitterplugin_Widget', 'widget');
	}
}
add_action('widgets_init', create_function('', 'return register_widget("wptwitterplugin_Widget");'));
