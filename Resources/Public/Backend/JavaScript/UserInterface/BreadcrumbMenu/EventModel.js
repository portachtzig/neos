Ext.namespace('F3.TYPO3.UserInterface.BreadcrumbMenu');

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @class F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel
 *
 * The event model for the BreacrumbMenuComponent. Handles the events within
 * the component.
 *
 * @namespace F3.TYPO3.UserInterface.BreadcrumbMenu
 * @extends Ext.tree.TreeEventModel
 */
F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel = function() {
	F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel.superclass.constructor.apply(this, arguments);
};

Ext.extend(F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel, Ext.tree.TreeEventModel, {

	/**
	 * @return {void}
	 */
	initEvents : function() {
		if (this.tree.trackMouseOver !== false) {
			this.tree.mon(this.tree.innerCt, {
				scope: this,
				mouseover: this.delegateOver,
				mouseout: this.delegateOut
			});
		}
		this.tree.mon(this.tree.getTreeEl(), {
			scope: this,
			click: this.delegateClick,
			dblclick: this.delegateDblClick,
			contextmenu: this.delegateContextMenu
		});
	},

	/**
	 * @param {Ext.EventObject} event
	 * @return {F3.TYPO3.UserInterface.BreadcrumbMenu.AsyncNode}
	 */
	getNode : function(event) {
		var target;
		if (target = event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-node-el', 10)) {
			var id = Ext.fly(target, '_treeEvents').getAttribute('tree-node-id', 'ext');
			if (id) {
				return this.tree.getNodeById(id);
			}
		}
		return null;
	},

	/**
	 * @param {Ext.EventObject} event
	 * @return {HTMLelement}
	 */
	getNodeTarget : function(event) {
		var target = event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-node-icon', 1);
		if (!target) {
			target = event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-node-el', 6);
		}
		return target;
	},

	/**
	 * @param {Ext.EventObject} event
	 * @param {HTMLelement} target
	 * @return {void}
	 */
	delegateOut : function(event, target) {
		if (!this.beforeEvent(event)) {
			return;
		}
		if (event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-ec-icon', 1)) {
			var n = this.getNode(event);
			this.onIconOut(event, n);
			if (n == this.lastEcOver) {
				delete this.lastEcOver;
			}
		}
		if ((target = this.getNodeTarget(event)) && !event.within(target, true)) {
			this.onNodeOut(event, this.getNode(event));
		}
	},

	/**
	 * @param {Ext.EventObject} event
	 * @param {HTMLelement} target
	 * @return {void}
	 */
	delegateOver : function(event, target) {
		if (!this.beforeEvent(event)) {
			return;
		}
		if (Ext.isGecko && !this.trackingDoc) { // prevent hanging in FF
			Ext.getBody().on('mouseover', this.trackExit, this);
			this.trackingDoc = true;
		}
		if (this.lastEcOver) { // prevent hung highlight
			this.onIconOut(event, this.lastEcOver);
			delete this.lastEcOver;
		}
		if (event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-ec-icon', 1)) {
			this.lastEcOver = this.getNode(event);
			this.onIconOver(event, this.lastEcOver);
		}
		if (target = this.getNodeTarget(event)) {
			this.onNodeOver(event, this.getNode(event));
		}
	},

	/**
	 * @param {Ext.EventObject} event
	 * @param {HTMLelement} target
	 * @return {void}
	 */
	delegateClick : function(event, target) {
		if (this.beforeEvent(event)) {
			if (event.getTarget('input[type=checkbox]', 1)) {
				this.onCheckboxClick(event, this.getNode(event));
			} else if (event.getTarget('.F3-TYPO3-UserInterface-BreadcrumbMenu-ec-icon', 1)) {
				this.onIconClick(event, this.getNode(event));
			} else if (this.getNodeTarget(event)) {
				this.onNodeClick(event, this.getNode(event));
			}
		} else {
			this.checkContainerEvent(event, 'click');
		}
	},

	/**
	 * @param {Ext.EventObject} event
	 * @param {F3.TYPO3.UserInterface.BreadcrumbMenu.AsyncNode} node
	 * @return {void}
	 */
	onIconOver : function(event, node) {
		node.ui.addClass('F3-TYPO3-UserInterface-BreadcrumbMenu-ec-over');
	},

	/**
	 * @param {Ext.EventObject} event
	 * @param {F3.TYPO3.UserInterface.BreadcrumbMenu.AsyncNode} node
	 * @return {void}
	 */
	onIconOut : function(event, node) {
		node.ui.removeClass('F3-TYPO3-UserInterface-BreadcrumbMenu-ec-over');
	}
});

Ext.reg('F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel', F3.TYPO3.UserInterface.BreadcrumbMenu.EventModel);