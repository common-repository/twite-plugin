<?php
/*
Plugin Name: Партнёрский виджет Твайт.ру
Plugin URI: http://www.twite.ru/partner.html
Description: Получайте 20% с каждого заказа на покупку фолловеров или поклонников, оформленного с помощью вашей партнёрской формы! Зарегистрируйтесь и вставьте виджет на страницы вашего сайта, чтобы начать получать деньги.
Version: 0.4
Author: Ilya Chekalskiy
Author URI: http://www.chekalskiy.ru/

-----------------------------------------------------
Copyright 2011  CHEKALSKIY ILYA  (email : ilya@chekalskiy.ru)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
-----------------------------------------------------
*/

function widget_twite_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ){
		return;	
	}

	function widget_twite_control() {
		$options = $newoptions = get_option('widget_twite');
		if ( $_POST['twite_submit'] ) {
			$newoptions['id'] = $_POST['twite_id'];			
		}

		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_twite', $options);
		}
		?>
		<div style="text-align:right">
		    <p style="text-align:left;"><label for="twite-intro">20% с каждого заказа, оформленного через вашу партнёрскую форму.<br/><a href="http://www.twite.ru/partner.html" target="_blank">Подробнее &rarr;</a></label></p>
			<label for="twite_id" style="line-height:35px;display:block;">ID магазина: <input type="text" id="twite_id" name="twite_id" value="<?php echo $options['id']; ?>" /></label>
			<input type="hidden" name="twite_submit" id="twite_submit" value="1" />
		</div>
		<?php
	}

	function widget_twite($args) {	
		extract($args);
		$defaults = array('id' => '');
		$options = (array) get_option('widget_twite');

		// Если настроек нет, ставим дефолтные
		foreach ( $defaults as $key => $value ){
			if ( !isset($options[$key]) || $options[$key] == ""){
				$options[$key] = $defaults[$key];
			}
		}
		
		$id = $options['id'];
		$title = 'Я рекомендую:';
		?>
		<?php echo $before_widget . $before_title . $title . $after_title; ?>
		<div style="margin: 5px auto; width: 215px;"><iframe frameborder="0" src="http://www.twite.ru/partner/store.php?p=vertical&k=<?php echo $id; ?>" width="215px" height="245px"></iframe>
		<a href="http://www.twite.ru/advertiser/facebook_pages.html?partner=<?php echo $id; ?>" title="Купить лайки Facebook">Купить лайки для facebook &rarr;</a>
		</div>
		<?php echo $after_widget; ?>
		<?php
	}

	register_sidebar_widget('Партнёрский виджет Твайт.ру', 'widget_twite');
	register_widget_control('Партнёрский виджет Твайт.ру', 'widget_twite_control');
}

//Converts all the occurances of [twitepartneriframe][/twitepartneriframe] to IFRAME HTML tags
function widget_twite_on_page($text){
	$regex = '#\[twitepartneriframe]((?:[^\[]|\[(?!/?twitepartneriframe])|(?R))+)\[/twitepartneriframe]#';
	if (is_array($text)) {
	    $param = explode(",", $text[1]);
        $text = '<iframe frameborder="0" src="http://www.twite.ru/partner/store.php?p=vertical&k='.$param[0].'" width="215px" height="265px"></iframe>';
    }
	return preg_replace_callback($regex, 'widget_twite_on_page', $text);
}

add_action('plugins_loaded', 'widget_twite_init');
add_filter('the_content', 'widget_twite_on_page');
?>