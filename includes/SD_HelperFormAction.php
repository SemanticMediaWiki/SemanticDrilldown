<?php
/**
 * Handles the createfilter action - used for the helper form to create filters.
 * (Because of the nature of $wgActions, this action name had to be different
 * from the one used by Semantic Forms to do its own helper form display,
 * 'formcreate'.)
 * 
 * @author Yaron Koren
 * @file
 * @ingroup SD
 */

class SDHelperFormAction extends Action
{
	/**
	 * Return the name of the action this object responds to
	 * @return String lowercase
	 */
	public function getName(){
		return 'createfilter';
	}
	
	/**
	 * The main action entry point.  Do all output for display and send it to the context
	 * output.  Do not use globals $wgOut, $wgRequest, etc, in implementations; use
	 * $this->getOutput(), etc.
	 * @throws ErrorPageError
	 */
	public function show(){
		return self::displayForm($this, $this->page);
	}

	/**
	 * Execute the action in a silent fashion: do not display anything or release any errors.
	 * @return Bool whether execution was successful
	 */
	public function execute(){
		return true;
	}

	/**
	 * Adds an "action" (i.e., a tab) to edit the current article with
	 * a form
	 */
	static function displayTab( $obj, &$content_actions ) {
		if ( method_exists ( $obj, 'getTitle' ) ) {
			$title = $obj->getTitle();
		} else {
			$title = $obj->mTitle;
		}
		// Make sure that this page is in the "Filter"
		// namespace, and that it doesn't exist yet.
		if ( !isset( $title ) ||
			( $title->getNamespace() != SD_NS_FILTER ) ) {
			return true;
		}
		if ( $title->exists() ) {
			return true;
		}

		global $wgRequest, $wgUser;

		$user_can_edit = $wgUser->isAllowed( 'edit' ) && $title->userCan( 'edit' );
		if ( $user_can_edit ) {
			$form_create_tab_text = wfMsg( 'sd_formcreate' );
		} else {
			$form_create_tab_text = wfMsg( 'sd_viewform' );
		}
		$class_name = ( $wgRequest->getVal( 'action' ) == 'createfilter' ) ? 'selected' : '';
		$form_create_tab = array(
			'class' => $class_name,
			'text' => $form_create_tab_text,
			'href' => $title->getLocalURL( 'action=createfilter' )
		);

		// Find the location of the 'create' tab, and add 'create
		// with form' right before it.
		// This is a "key-safe" splice - it preserves both the keys
		// and the values of the array, by editing them separately
		// and then rebuilding the array. Based on the example at
		// http://us2.php.net/manual/en/function.array-splice.php#31234
		$tab_keys = array_keys( $content_actions );
		$tab_values = array_values( $content_actions );
		$edit_tab_location = array_search( 'edit', $tab_keys );

		// If there's no 'edit' tab, look for the 'view source' tab
		// instead.
		if ( $edit_tab_location == null ) {
			$edit_tab_location = array_search( 'viewsource', $tab_keys );
		}

		// This should rarely happen, but if there was no edit *or*
		// view source tab, set the location index to -1, so the
		// tab shows up near the end.
		if ( $edit_tab_location == null ) {
			$edit_tab_location = - 1;
		}
		array_splice( $tab_keys, $edit_tab_location, 0, 'form_edit' );
		array_splice( $tab_values, $edit_tab_location, 0, array( $form_create_tab ) );
		$content_actions = array();
		for ( $i = 0; $i < count( $tab_keys ); $i++ ) {
			$content_actions[$tab_keys[$i]] = $tab_values[$i];
		}

		global $wgUser;
		if ( ! $wgUser->isAllowed( 'viewedittab' ) ) {
			// The tab can have either of these two actions.
			unset( $content_actions['edit'] );
			unset( $content_actions['viewsource'] );
		}

		return true; // always return true, in order not to stop MW's hook processing!
	}

	/**
	 * Like displayTab(), but called with a different hook - this one is
	 * called for the 'Vector' skin, and others.
	 */
	static function displayTab2( $obj, &$links ) {
		// the old '$content_actions' array is thankfully just a
		// sub-array of this one
		return self::displayTab( $obj, $links['views'] );
	}

	/**
	 * The function called if we're in index.php (as opposed to one of the
	 * special pages)
	 */
	static function displayForm( $action, $article ) {
		if ( $article->getTitle()->getNamespace() != SD_NS_FILTER ) {
			return true;
		}

		//$output = $action->getOutput();

		$title = $article->getTitle();
		$createFilterPage = new SDCreateFilter();
		$createFilterPage->execute( $title->getText() );

		return false;
	}

	/**
	 * A dummy method - this is needed for MediaWiki 1.18, where
	 * Action::getRestriction() is abstract and needs to be implemented.
	 */
	public function getRestriction() {
	}
}
