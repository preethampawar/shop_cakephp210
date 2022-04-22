<?php
App::uses('AppModel', 'Model');

class Site extends AppModel
{
	const THEME_WHITE = 'THEME_WHITE';
	const THEME_WHITE_AND_RED = 'THEME_WHITE_AND_RED';
	const THEME_LIGHT = 'THEME_LIGHT';
	const THEME_DARK_GREY = 'THEME_DARK_GREY';
	const THEME_DARK = 'THEME_DARK';
	const THEME_PURPLE = 'THEME_PURPLE';
	const THEME_BLUE = 'THEME_BLUE';
	const THEME_GREEN = 'THEME_GREEN';
	const THEME_YELLOW = 'THEME_YELLOW';
	const THEME_RED = 'THEME_RED';

	const THEME_OPTIONS = [
		self::THEME_WHITE => self::THEME_WHITE,
		self::THEME_WHITE_AND_RED => self::THEME_WHITE_AND_RED,
		self::THEME_LIGHT => self::THEME_LIGHT,
		self::THEME_DARK_GREY => self::THEME_DARK_GREY,
		self::THEME_DARK => self::THEME_DARK,
		self::THEME_PURPLE => self::THEME_PURPLE,
		self::THEME_BLUE => self::THEME_BLUE,
		self::THEME_GREEN => self::THEME_GREEN,
		self::THEME_RED => self::THEME_RED,
		self::THEME_YELLOW => self::THEME_YELLOW,
	];

	var $name = 'Site';
	var $hasMany = ['Domain'];
	var $displayField = 'name';

	public function afterSave($created, $options = array()) {

	}

}
