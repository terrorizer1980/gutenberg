<?php
/**
 * Tests the Style Engine class and associated functionality.
 *
 * @package    Gutenberg
 * @subpackage style-engine
 */

require __DIR__ . '/../class-wp-style-engine.php';

/**
 * Tests for registering, storing and generating styles.
 */
class WP_Style_Engine_Test extends WP_UnitTestCase {
	/**
	 * Tests generating styles and classnames based on various manifestations of the $block_styles argument.
	 *
	 * @dataProvider data_generate_styles_fixtures
	 */
	function test_generate_styles( $block_styles, $options, $expected_output ) {
		$style_engine     = wp_get_style_engine();
		$generated_styles = $style_engine->generate(
			$block_styles,
			$options
		);
		$this->assertSame( $expected_output, $generated_styles );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_generate_styles_fixtures() {
		return array(
			'default_return_value'                         => array(
				'block_styles'    => array(),
				'options'         => null,
				'expected_output' => null,
			),

			'inline_invalid_block_styles_empty'            => array(
				'block_styles'    => array(),
				'options'         => array(),
				'expected_output' => null,
			),

			'inline_invalid_block_styles_unknown_style'    => array(
				'block_styles'    => array(
					'pageBreakAfter' => 'verso',
				),
				'options'         => array(),
				'expected_output' => array(),
			),

			'inline_invalid_block_styles_unknown_definition' => array(
				'block_styles'    => array(
					'pageBreakAfter' => 'verso',
				),
				'options'         => array(),
				'expected_output' => array(),
			),

			'inline_invalid_block_styles_unknown_property' => array(
				'block_styles'    => array(
					'spacing' => array(
						'gap' => '1000vw',
					),
				),
				'options'         => array(),
				'expected_output' => array(),
			),

			'valid_inline_css_and_classnames'              => array(
				'block_styles'    => array(
					'color'   => array(
						'text' => array(
							'slug' => 'texas-flood',
						),
					),
					'spacing' => array(
						'margin' => '111px',
					),
				),
				'options'         => array(),
				'expected_output' => array(
					'css'        => 'margin: 111px;',
					'classnames' => 'has-text-color has-texas-flood-color',
				),
			),

			'inline_valid_box_model_style'                 => array(
				'block_styles'    => array(
					'spacing' => array(
						'padding' => array(
							'top'    => '42px',
							'left'   => '2%',
							'bottom' => '44px',
							'right'  => '5rem',
						),
						'margin'  => array(
							'top'    => '12rem',
							'left'   => '2vh',
							'bottom' => '2px',
							'right'  => '10em',
						),
					),
				),
				'options'         => array(),
				'expected_output' => array(
					'css' => 'padding-top: 42px; padding-left: 2%; padding-bottom: 44px; padding-right: 5rem; margin-top: 12rem; margin-left: 2vh; margin-bottom: 2px; margin-right: 10em;',
				),
			),

			'inline_valid_typography_style'                => array(
				'block_styles'    => array(
					'typography' => array(
						'fontSize'       => 'clamp(2em, 2vw, 4em)',
						'fontFamily'     => 'Roboto,Oxygen-Sans,Ubuntu,sans-serif',
						'fontStyle'      => 'italic',
						'fontWeight'     => '800',
						'lineHeight'     => '1.3',
						'textDecoration' => 'underline',
						'textTransform'  => 'uppercase',
						'letterSpacing'  => '2',
					),
				),
				'options'         => array(),
				'expected_output' => array(
					'css' => 'font-family: Roboto,Oxygen-Sans,Ubuntu,sans-serif; font-style: italic; font-weight: 800; line-height: 1.3; text-decoration: underline; text-transform: uppercase; letter-spacing: 2;',
				),
			),
			'valid_classnames_deduped'                     => array(
				'block_styles'    => array(
					'color'      => array(
						'text'       => array(
							'slug' => 'copper-socks',
						),
						'background' => array(
							'slug' => 'splendid-carrot',
						),
						'gradient'   => array(
							'slug' => 'like-wow-dude',
						),
					),
					'typography' => array(
						'fontSize'   => array(
							'slug' => 'fantastic',
						),
						'fontFamily' => array(
							'slug' => 'totally-awesome',
						),
					),
				),
				'options'         => array(),
				'expected_output' => array(
					'classnames' => 'has-text-color has-copper-socks-color has-background has-splendid-carrot-background-color has-like-wow-dude-gradient-background has-fantastic-font-size has-totally-awesome-font-family',
				),
			),
			'valid_classnames_with_null_style_values'      => array(
				'block_styles'    => array(
					'color' => array(
						'text'       => array(
							'value' => '#fff',
							'slug'  => null,
						),
						'background' => array(
							'value' => null,
							'slug'  => null,
						),
					),
				),
				'options'         => array(),
				'expected_output' => array(
					'css'        => 'color: #fff;',
					'classnames' => 'has-text-color',
				),
			),
			'invalid_classnames_options'                   => array(
				'block_styles'    => array(
					'typography' => array(
						'fontSize'   => array(
							'tomodachi' => 'friends',
						),
						'fontFamily' => array(
							'oishii' => 'tasty',
						),
					),
				),
				'options'         => array(),
				'expected_output' => array(),
			),
		);
	}
}
