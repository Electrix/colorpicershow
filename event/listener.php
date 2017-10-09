<?php
/**
*
* @package Color Picker Show extension
* @copyright (c) 2015 Electrix
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace electrix\cpickershow\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\twig\twig */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config		$config		Config object
	* @param \phpbb\request\request		$request	Request object
	* @param \phpbb\template\twig\twig	$template	Template object
	* @param \phpbb\user                $user		User object
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\twig\twig $template, \phpbb\user $user)
	{
		$this->config	= $config;
		$this->request	= $request;
		$this->template	= $template;
		$this->user		= $user;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'					=> 'load_user_data',
			'core.posting_modify_template_vars'			=> 'show_color_picker_now',
			'core.ucp_prefs_post_data'			=> 'add_user_prefs',
			'core.ucp_prefs_post_update_data'	=> 'update_user_prefs',
		);
	}

	/**
	* Add the necessary variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_user_prefs($event)
	{
		$data = $event['data'];

		$data = array_merge($data, array(
			'cpickershow'	=> $this->request->variable('cpickershow', (!empty($user->data['user_cpickershow'])) ? $user->data['user_cpickershow'] : 0),
		));

		$event->offsetSet('data', $data);

		$this->template->assign_vars(array(
			'S_COLOR_PICKER_SHOW'	=> $this->user->data['user_cpickershow'],
		));
	}

	/**
	* Update the sql data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function update_user_prefs($event)
	{
		$sql_ary	= $event['sql_ary'];
		$data		= $event['data'];

		$sql_ary = array_merge($sql_ary, array(
			'user_cpickershow'	=> $data['cpickershow'],
		));

		$event->offsetSet('sql_ary', $sql_ary);
	}

	/**
	* Load the necessary data during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_user_data($event)
	{
		// Switch the user vars
		$this->config['cpickershow']		= ($this->user->data['user_cpickershow'] > 0) ? $this->user->data['user_cpickershow'] : $this->config['cpickershow'];

		// Load the language file
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= array(
			'ext_name' => 'electrix/cpickershow',
			'lang_set' => 'cpickershow',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
	
	/**
	* Assign vars for temlate check
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function show_color_picker_now($event)
	{
		$this->template->assign_vars(array(
			'S_COLOR_PICKER_SHOW'	=> $this->user->data['user_cpickershow'],
		));
	}
}