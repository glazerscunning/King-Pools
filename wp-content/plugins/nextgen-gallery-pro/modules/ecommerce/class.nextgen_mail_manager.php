<?php

class C_Nextgen_Mail_Content
{
	var $_list;
	var $_private;
	var $_template;
	
	function __construct()
	{
		$this->_list = array();
		$this->_private = array();
	}
	
	function is_property($name)
	{
		return isset($this->_list[$name]);
	}
	
	function is_property_private($name)
	{
		return isset($this->_private[$name]) && $this->_private[$name];
	}
	
	function get_property($name)
	{
		if (isset($this->_list[$name]))
		{
			return $this->_list[$name];
		}
		
		return null;
	}
	
	function set_property($name, $value)
	{
		$this->_list[$name] = $value;
		$this->_private[$name] = false;
	}
	
	function set_property_private($name, $value)
	{
		$this->_list[$name] = $value;
		$this->_private[$name] = true;
	}
	
	function get_subject()
	{
		return $this->get_property('subject');
	}
	
	function set_subject($subject)
	{
		$this->set_property_private('subject', $subject);
	}
	
	function get_sender()
	{
		return $this->get_property('sender');
	}
	
	function set_sender($sender)
	{
		$this->set_property_private('sender', $sender);
	}
	
	function load_template($template_text)
	{
		$this->_template = $template_text;
	}
	
	function evaluate_template($template_text = null)
	{
		if ($template_text == null)
		{
			$template_text = $this->_template;
		}
		
		$template_text = str_replace(array("\r\n", "\n"), "\n", $template_text);
		$matches = null;

		if (preg_match_all('/%%(\\w+)%%/', $template_text, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$var = $match[1];
				$parts = explode('_', $var);
				$root = array_shift($parts);
				$name = implode('_', $parts);
				$replace = null;
				
				$var_value = !$this->is_property_private($var) ? $this->get_property($var) : null;
				
				if ($var_value == null)
				{
					$var_meta = !$this->is_property_private($root) ? $this->get_property($root) : null;
					
					if ($var_meta != null && isset($var_meta[$name]))
					{
						$var_value = $var_meta[$name];
					}
				}
				
				if ($var_value == null)
				{
					// This is a place to have certain defaults set, or values which are not easily settable in a property list. It could also be extended in the future with custom callbacks etc.
					switch ($root)
					{
						case 'time':
						{
							switch ($name)
							{
								case 'now_utc':
								{
									// for clarification in case it's not obvious, this will replace the meta variable %%time_now_utc%% in the mail template
									$var_value = date(DATE_RFC850);
						
									break;
								}
							}
							
							break;
						}
					}
				}
				
				if ($var_value != null)
				{
					$replace = $var_value;
				}
	
				$template_text = str_replace($match[0], $replace, $template_text);
			}
		}
		
		return $template_text;
	}
}

/*
 * How you would send an e-mail

	$mailman = $registry->get_utility('I_Nextgen_Mail_Manager');
	$content = $mailman->create_content();
	$content->set_subject('test');
	$content->set_property('user', 'Test');
	$content->load_template('Hi %%user%%, test');

	$mailman->send_mail($content, 'some@email.com');
 */
class Mixin_Nextgen_Mail_Manager extends Mixin
{
	function create_content($type = null)
	{
		if ($type == null)
		{
			$type = 'C_Nextgen_Mail_Content';
		}
		
		return new $type;
	}
	
	function send_mail($content, $receiver, $subject = null, $sender = null, $mail_headers=array())
	{
		$mail_body = null;
		
		if (is_string($content))
		{
			$mail_body = $content;
		}
		else if ($content instanceof C_Nextgen_Mail_Content)
		{
			if ($subject == null)
			{
				$subject = $content->get_subject();
			}
			
			if ($sender == null)
			{
				$sender = $content->get_sender();
			}
			
			$mail_body = $content->evaluate_template();
		}
		
		if ($mail_body != null)
		{
			if ($sender != null)
			{
				$mail_headers['From'] = $sender;
			}
			
			wp_mail($receiver, $subject, $mail_body, $mail_headers);
		}
	}
}

class C_Nextgen_Mail_Manager extends C_Component
{
    static $_instances = array();

    function define($context=FALSE)
    {
			parent::define($context);

			$this->implement('I_Nextgen_Mail_Manager');
			$this->add_mixin('Mixin_Nextgen_Mail_Manager');
    }

    static function get_instance($context = False)
    {
			if (!isset(self::$_instances[$context]))
			{
					self::$_instances[$context] = new C_Nextgen_Mail_Manager($context);
			}

			return self::$_instances[$context];
    }
}
