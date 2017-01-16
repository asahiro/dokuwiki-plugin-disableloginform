<?php
/**
 * DokuWiki Plugin disableloginform (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  asahiro <asahiro.g@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_disableloginform extends DokuWiki_Action_Plugin {

  /**
   * Registers a callback function for a given event
   *
   * @param Doku_Event_Handler $controller DokuWiki's event controller object
   * @return void
   */
  public function register(Doku_Event_Handler $controller) {

     $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'BEFORE', $this, 'handle_html_loginform_output');

  }

  /**
   * [Custom event handler which performs action]
   *
   * @param Doku_Event $event  event object by reference
   * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
   *                           handler was registered]
   * @return void
   */

  public function handle_html_loginform_output(Doku_Event &$event, $param) {
    global $lang;
    $form =& $event->data;

    if(empty($form->_content) || !is_array($form->_content)){ return; }

    $startPos = findElementByAttribute('_legend' => $lang['btn_login']);// findElementByType('openfieldset');
    if($startPos === false){ return; }

    addHidden('id', null);
    addHidden('do', null);

    $endPos = $startPos;
    for ($i=$startPos+1; $i < count($form->_content); $i++) {
      $elem = $form->_content[$i];
      if(is_array($elem)){
        if      ($elem['_elem'] == 'openfieldset'){
          break;
        }else if($elem['_elem'] == 'closefieldset'){
          $endPos = $i;
          break;
        }
      }
    }
    array_splice( $form->_content, $startPos, ($endPos - $startPos + 1) );
    $register_link_content = '<p>'.$lang['reghere'].': '.tpl_actionlink('register','','','',true).'</p>';
    $resendpw_link_content = '<p>'.$lang['pwdforget'].': '.tpl_actionlink('resendpwd','','','',true).'</p>';
  }

}

// vim:ts=4:sw=4:et:
