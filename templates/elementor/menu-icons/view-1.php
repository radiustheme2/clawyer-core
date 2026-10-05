<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $hamburger              string
 * @var $search                 string
 * @var $login                  string
 * @var $login_label            string
 * @var $login_icon             string
 * @var $button                 string
 * @var $button_icon            string
 * @var $has_separator          string
 * @var $button_label           string
 */

$args = [
	'hamburg'       => $hamburger,
	'search'        => $search,
	'login'         => $login,
	'button'        => $button,
	'has_separator' => $has_separator
];

if ( $login_label ) {
	$args['login_label'] = $login_label;
	$args['login_icon'] = $login_icon['value'];
}

if ( $button_label ) {
	$args['button_label'] = $button_label;
	$args['button_icon'] = $button_icon['value'];
}
if ( get_template() === 'clawyer' ) {
	clawyer_menu_icons_group( $args );
}