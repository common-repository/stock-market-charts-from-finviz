<?php
/*
Plugin Name: Stock market charts from finviz
Description: shortcode 'finviz' to embed stock market charts from finviz.com
Plugin URI: https://wordpress.org/plugins/stock-market-charts-from-finviz/
Author: Moris Dov
Author URI: https://profiles.wordpress.org/morisdov
Version: 1.0.2
*/
////////////////////////////////////////////////////////////////////////////////////////
add_action('init', 'md_register_shortcode_finviz');

function md_register_shortcode_finviz()
{
    add_shortcode('finviz', 'md_shortcode_finviz');
}
function md_shortcode_finviz($attr = [], $content = null)
{
    try {
        $attr = array_change_key_case( (array) $attr, CASE_LOWER );
        // get shortcode attribute  ticker
        if (isset($attr['ticker'])) {
            if (strlen($attr['ticker']) <= 7) {
                $ticker = sanitize_text_field($attr['ticker']);
            }
        } else {
            $ticker = 'GE';
		}
		// get shortcode attribute  width
        if (isset($attr['width'])) {
            $attr['width'] = (int)$attr['width'];
            if ($attr['width'] > 10 && $attr['width'] < 1201) {
                $width = $attr['width'];
            } else {
                $width = false;
            }
        } else {
            $width = false;
        }

        // get saved plugin options
        $options = get_option('md_finviz', array('option1' => 0, 'loading' => 0, 'alt_text' => 'finviz dynamic chart for '));

		// option checkbox
        if (isset($options['option1']) && ($options['option1'] == 1 || $options['option1'] == '1') ) {
            $link = true;
        } else {
            $link = false;
        }
		// override saved option 'link'
		if (isset($attr['link'])) {
            $attr['link'] = strtolower($attr['link']);
			if ($attr['link'] == 'true' || $attr['link'] == 'on' || $attr['link'] == '1' ) {
				$link = true;
			} else {
				$link = false;
			}
		}
		// override saved option 'averages'
		if (isset($attr['averages'])) {
            $attr['averages'] = strtolower($attr['averages']);
			if ($attr['averages'] == 'true' || $attr['averages'] == 'on' || $attr['averages'] == '1' ) {
				$averages = true;
			} else {
				$averages = false;
			}
		} else {
			$averages = false;
		}
		// override saved option 'type'
		if (isset($attr['type'])) {
            $attr['type'] = strtolower($attr['type']);
			if ($attr['type'] == 'candle' || $attr['type'] == 'line' ) {
				// dont change
			} else {
				$attr['type'] = 'candle';
			}
		} else {
			$attr['type'] = 'candle';
		}
		// override saved option 'period'
		if (isset($attr['period'])) {
            $attr['period'] = strtolower($attr['period']);
			if ($attr['period'] == 'day' || $attr['period'] == 'week' || $attr['period'] == 'month' ) {
				// dont change
			} else {
				$attr['period'] = 'day';
			}
		} else {
			$attr['period'] = 'day';
		}		
		// override saved option 'loading'
		if (isset($attr['loading'])) {
            $attr['loading'] = strtolower($attr['loading']);
			if ($attr['loading'] == 'lazy') {
				// dont change
			} else {
				$attr['loading'] = (empty($options['loading'])) ? 0 : 'lazy';
			}
		} else {
			$attr['loading'] = (empty($options['loading'])) ? 0 : 'lazy';
		}
        
        // option alt image text
        if (isset($options['alt_text']) && strlen(trim($options['alt_text'])) > 1 ) {		
			//$options['alt_text'] = esc_attr($options['alt_text']);
            $alt_text = $options['alt_text'] . ' ' . $ticker;
			$alt_text = sanitize_text_field($alt_text);
        } else {
            $alt_text = "finviz dynamic chart for  $ticker ";
        }
		

		// final chart string construction
		$str = '<img class="finviz-image" src=\'';
		$str .= "https://charts2.finviz.com/chart.ashx?t=$ticker";
		
		if ($attr['period'] == 'day') {
            $str .= "&p=d";
        } elseif ($attr['period'] == 'week') {
			$str .= "&p=w";
		} elseif ($attr['period'] == 'month') {
			$str .= "&p=m";
		}
		if ($attr['type'] == 'candle') {
            $str .= "&ty=c";
        } elseif ($attr['type'] == 'line') {
			$str .= "&ty=l";
		}
        if ($averages && ( !isset($attr['period']) || ($attr['period'] != 'month' && $attr['period'] != 'week') ) ) {
            $str .= "&ta=1";
        } 
		$str .= "'";

		if ( $attr['loading'] === 'lazy' ) {
            $str .= " loading='lazy' ";
        } 
        if ($alt_text) {
            $str .= " alt='$alt_text' ";
        } 
        if ($width) {
            $str .= " width='$width' ";
        } 
		
		$str .= "  />";
        if ($link) {  // wrap image in hyperlink
            $str = '<a class="finviz-anchor" href=\'' . "https://www.finviz.com/quote.ashx?a=117036537&t=$ticker' target='_blank'>$str</a>";
        }


        return $str;

	} catch (exception $e) {
        error_log($e->getMessage());
    }
}


////////////////////////////////////////////////////////////////////////////////////////
// Add the admin options page
add_action('admin_init', 'md_finvizoptions_init');
add_action('admin_menu', 'md_finvizoptions_add_page');

// Init plugin options to white list our options
function md_finvizoptions_init()
{
    //register_setting('md_finviz_options_page', 'md_finviz', 'md_finvizoptions_validate');
	//register_setting('md_finviz_options_page', 'md_finviz' );
	register_setting('md_finviz_options_page', 'md_finviz', [
		'type'              => 'array',
		'sanitize_callback' => 'md_finviz_options_validate']);
	// Error: Options page md_finviz_options_page not found in the allowed options list
}
// Add menu page
function md_finvizoptions_add_page()
{
	$md_admin_page = add_options_page('Stock Market Charts', 'Stock Market Charts', 'manage_options', 'md_finvizoptions', 'md_finvizoptions_do_page');
	add_action('load-' . $md_admin_page, 'md_finviz_add_help');
}

// Draw the menu page itself
function md_finvizoptions_do_page()
{
    ?>
	<div class="wrap">
		<h2>Plugin - Stock market charts from finviz</h2><br/>
		<div style="">
		<h3>Shortcode  [finviz ticker=  ]  Instructions</h3>
		<p/> use shortcode <b>[finviz ticker=GE ]</b> to embed the stock chart of <a href="https://www.finviz.com/chart.ashx?t=GE" target="_blank">General Electric</a>.
		<p/> optional <b>link</b> attribute [finviz ticker=GE <b>link=true</b> ] to add a hyperlink to <a href="https://www.finviz.com/quote.ashx?t=GE" target="_blank">finviz.com/GE</a>.
		<br/> optional <b>link</b> attribute [finviz ticker=GE <b>link=false</b> ] to remove a default hyperlink if set.
		<br/> optional <b>width</b> attribute [finviz ticker=GE <b>width=500</b> ] to specify chart image width.
		<br/> optional <b>loading</b> attribute [finviz ticker=GE <b>loading=lazy</b> ] to lazy load the chart image.
		<br/> optional <b>type</b> attribute [finviz ticker=GE <b>type=line</b> ] for line chart or <b>type=candle</b> for candle chart.
		<br/> optional <b>averages</b> attribute [finviz ticker=GE <b>averages=true</b> ] to add trailing averages to the daily period chart.
		<br/> optional <b>period</b> attribute [finviz ticker=GE <b>period=day</b> ] or <b>period=week</b> or <b>period=month</b>.
		</div>
		<hr/>
		<h3>General Settings</h3>
		<form method="post" action="options.php">
	<?php 
			settings_fields('md_finviz_options_page');
			$options = get_option('md_finviz', array('option1' => 0, 'loading' => 0, 'alt_text' => '') );
			$options['option1'] = (empty($options['option1'])) ? 0 : $options['option1'];
			$options['loading'] = (empty($options['loading'])) ? 0 : $options['loading'];
			$options['alt_text'] = (empty($options['alt_text'])) ? '' : $options['alt_text'];
	
	?>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="md_finviz[option1]">Hyperlink charts to finviz.com</label></th>
					<td><input name="md_finviz[option1]" id="md_finviz[option1]" type="checkbox" value="1" <?php checked('1', $options['option1']); ?> />
					<p class="description">link = true ]</p></td></tr>
				<tr valign="top"><th scope="row"><label for="md_finviz[loading]">Lazy load chart images</label></th>
					<td><input name="md_finviz[loading]" id="md_finviz[loading]" type="checkbox" value="1" <?php checked('1', $options['loading']); ?> />
					<p class="description">loading = lazy ]</p></td></tr>
				<tr valign="top"><th scope="row"><label for="md_finviz[alt_text]">Chart images alt text</label><p class="description">prefix</p></th>
					<td><input type="text" name="md_finviz[alt_text]" id="md_finviz[alt_text]" value="<?php echo $options['alt_text']; ?>" />
					<p class="description">finviz dynamic chart for <code>TICKER</code></p></td></tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}


// Sanitize and validate input. Accepts an array, return a sanitized array.
function md_finviz_options_validate($input)
{	
    //add_settings_error('prefix_messages', 'prefix_message', __('Email address is invalid', 'prefix'), 'error');
	//error_log('function md_finviz_options_validate()');
	//error_log(print_r($input,1));
	
    // Our first value is either 0 or 1
    if (array_key_exists('option1', $input)) {
        $input['option1'] = ($input['option1'] == 1 ? 1 : 0);
    }
    // Say our second option must be safe text with no HTML tags
    if (array_key_exists('alt_text', $input)) {
		$input['alt_text'] =  wp_strip_all_tags($input['alt_text']);
		$input['alt_text'] =  esc_attr($input['alt_text']);
		$input['alt_text'] =  sanitize_text_field($input['alt_text']);
    }

    return $input;
}


// Add help
add_action('admin_menu', function () {
	global $pagenow;
    if (in_array($pagenow, array( 'post.php', 'post-new.php' ), true)) {
        add_action('load-' . $pagenow, 'md_finviz_add_help');
    }
});
function md_finviz_add_help()
{
	$screen = get_current_screen();
	$screen->add_help_tab(array(                //add more help tabs as needed with unique id's
		'id'       => 'md-finviz-default-1',
		'title'    => __('Shortcode finviz'),
		'priority' => 20,
		'content'  => '
		<p/> use shortcode <b>[finviz ticker=GE ]</b> to embed the <a href="https://www.finviz.com/chart.ashx?t=GE" target="_blank">stock chart</a> of General Electric.
		<p/> optional <b>link</b> attribute [finviz ticker=GE <b>link=true</b> ] to add a hyperlink to <a href="https://www.finviz.com/quote.ashx?t=GE" target="_blank">finviz.com/GE</a>.
		<br/> optional <b>link</b> attribute [finviz ticker=GE <b>link=false</b> ] to remove a default hyperlink if set.
		<br/> optional <b>width</b> attribute [finviz ticker=GE <b>width=500</b> ] to specify chart image width.
		<br/> optional <b>loading</b> attribute [finviz ticker=GE <b>loading=lazy</b> ] to lazy load the chart image.
		<br/> optional <b>type</b> attribute [finviz ticker=GE <b>type=line</b> ] for line chart or <b>type=candle</b> for candle chart.
		<br/> optional <b>averages</b> attribute [finviz ticker=GE <b>averages=true</b> ] to add trailing averages to the daily period chart.
		<br/> optional <b>period</b> attribute [finviz ticker=GE <b>period=day</b> ] or <b>period=week</b> or <b>period=month</b>.
		'
	));                   
}

// all plugins list
add_filter( 'plugin_action_links', function ( $plugin_actions, $plugin_file ) {
	$new_actions = array();
	if ( $plugin_file === 'stock-market-charts-from-finviz/stock_market_charts_finviz.php' && current_user_can('manage_options') ) {
		$url = admin_url( 'options-general.php?page=md_finvizoptions' );           
		$new_actions['md_finviz_settings'] = sprintf( '<a href="%s">%s</a>', $url, 'Settings' );
	}
	return array_merge( $plugin_actions, $new_actions );
}, 10, 2 );

