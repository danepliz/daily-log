<?php
/**
 *
 * CSS Crush
 * Extensible CSS preprocessor
 * 
 * @version    1.4.2
 * @license    http://www.opensource.org/licenses/mit-license.php (MIT)
 * @copyright  Copyright 2010-2012 Pete Boere
 * 
 * 
 * <?php
 *
 * // Basic usage
 * require_once 'CssCrush.php';
 * $global_css = CssCrush::file( '/css/global.css' );
 *
 * ?>
 *
 * <link rel="stylesheet" href="<?php echo $global_css; ?>" />
 *
 */

require_once APPPATH.'third_party/cssCrush/lib/Util.php';
require_once APPPATH.'third_party/cssCrush/lib/Core.php';
//echo dirname( __FILE__ );
//echo BASEPATH;
//CssCrush::init( dirname( __FILE__ ) );
CssCrush::init(APPPATH.'third_party/cssCrush');

require_once APPPATH.'third_party/cssCrush/lib/Rule.php';

require_once APPPATH.'third_party/cssCrush/lib/Function.php';
CssCrush_Function::init();

require_once APPPATH.'third_party/cssCrush/lib/Importer.php';
require_once APPPATH.'third_party/cssCrush/lib/Color.php';
require_once APPPATH.'third_party/cssCrush/lib/Hook.php';




